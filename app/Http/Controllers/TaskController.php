<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->ensureAccess($project);
        $data = $request->validate(['title' => ['required', 'max:255'], 'description' => ['nullable'], 'priority' => ['required', 'in:low,medium,high'], 'deadline' => ['nullable', 'date']]);
        $project->tasks()->create($data);

        return to_route('lists.show', $project);
    }

    public function toggle(Project $project, Task $task): RedirectResponse
    {
        $this->ensureAccess($project);
        abort_unless($task->project_id === $project->id, 404);
        $task->update(['status' => $task->status === 'completed' ? 'pending' : 'completed']);

        return to_route('lists.show', $project);
    }

    private function ensureAccess(Project $project): void
    {
        abort_unless($project->owner_id === auth()->id() || $project->members()->whereKey(auth()->id())->exists(), 403);
    }
}
