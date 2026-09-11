<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $accessibleListIds = TaskList::where('user_id', $user->id)
            ->orWhereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->pluck('id');
        $accessibleLists = TaskList::whereIn('id', $accessibleListIds)->orderBy('name')->get();

        $query = Task::with(['taskList', 'creator', 'assignee'])
            ->where(function ($q) use ($accessibleListIds, $user) {
                $q->whereIn('task_list_id', $accessibleListIds)
                    ->orWhere('created_by', $user->id)
                    ->orWhere('assigned_to', $user->id);
            });

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by list
        if ($request->filled('task_list_id')) {
            $query->where('task_list_id', $request->input('task_list_id'));
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by assigned to me
        if ($request->boolean('assigned_to_me')) {
            $query->where('assigned_to', $user->id);
        }

        $tasks = $query->orderByRaw("CASE status WHEN 'In Progress' THEN 1 WHEN 'Pending' THEN 2 WHEN 'Completed' THEN 3 ELSE 4 END")
            ->orderBy('deadline', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('tasks.index', compact('tasks', 'accessibleLists'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Request $request): View
    {
        $user = Auth::user();

        $taskLists = TaskList::where('user_id', $user->id)
            ->orWhereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->orderBy('name')
            ->get();

        // If user has no task list, create a default list so they can immediately add tasks
        if ($taskLists->isEmpty()) {
            $defaultList = TaskList::firstOrCreate(
                ['user_id' => $user->id, 'name' => 'General Tasks'],
                ['description' => 'Default task list for tasks']
            );
            $taskLists = collect([$defaultList]);
        }

        $users = User::orderBy('name')->get();
        $selectedListId = $request->input('task_list_id', $taskLists->first()?->id);

        return view('tasks.create', compact('taskLists', 'users', 'selectedListId'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(TaskStoreRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $taskList = TaskList::findOrFail($request->task_list_id);

        if (! $taskList->canAccess($user)) {
            abort(403, 'You do not have access to this task list.');
        }

        $task = Task::create([
            'task_list_id' => $request->task_list_id,
            'created_by' => $user->id,
            'assigned_to' => $request->assigned_to,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Task "'.$task->title.'" created successfully!');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task): View
    {
        $user = Auth::user();
        $task->load(['taskList.owner', 'taskList.members', 'creator', 'assignee']);

        if ($task->taskList && ! $task->taskList->canAccess($user) && $task->created_by !== $user->id && $task->assigned_to !== $user->id) {
            abort(403, 'You do not have access to view this task.');
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task): View
    {
        $user = Auth::user();
        $task->load(['taskList.members', 'taskList.owner']);

        if ($task->taskList && ! $task->taskList->canAccess($user) && $task->created_by !== $user->id) {
            abort(403, 'You do not have access to edit this task.');
        }

        $taskLists = TaskList::where('user_id', $user->id)
            ->orWhereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->orderBy('name')
            ->get();

        if ($taskLists->isEmpty() && $task->taskList) {
            $taskLists = collect([$task->taskList]);
        }

        $users = User::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'taskLists', 'users'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task): RedirectResponse
    {
        $user = Auth::user();
        $taskList = TaskList::findOrFail($request->task_list_id);

        if (! $taskList->canAccess($user) && $task->created_by !== $user->id) {
            abort(403, 'You do not have access to move or edit tasks in this task list.');
        }

        $task->update([
            'task_list_id' => $request->task_list_id,
            'assigned_to' => $request->assigned_to,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task "'.$task->title.'" updated successfully!');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $user = Auth::user();
        $taskList = $task->taskList;

        // Allowed if creator of task or owner of list
        $canDelete = (int) $task->created_by === (int) $user->id
            || ($taskList && (int) $taskList->user_id === (int) $user->id);

        if (! $canDelete) {
            abort(403, 'You do not have permission to delete this task.');
        }

        $title = $task->title;
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task "'.$title.'" was deleted.');
    }

    /**
     * Quick status update.
     */
    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $user = Auth::user();

        if ($task->taskList && ! $task->taskList->canAccess($user) && $task->created_by !== $user->id && $task->assigned_to !== $user->id) {
            abort(403, 'You do not have access to modify this task.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['Pending', 'In Progress', 'Completed'])],
        ]);

        $task->update(['status' => $validated['status']]);

        return back()->with('success', 'Task status updated to '.$task->status.'.');
    }
}
