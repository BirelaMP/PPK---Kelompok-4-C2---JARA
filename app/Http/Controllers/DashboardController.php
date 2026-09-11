<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the task management dashboard.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $taskListIds = TaskList::where('user_id', $user->id)
            ->orWhereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->pluck('id');

        $tasksQuery = Task::where(function ($q) use ($taskListIds, $user) {
            $q->whereIn('task_list_id', $taskListIds)
                ->orWhere('created_by', $user->id)
                ->orWhere('assigned_to', $user->id);
        });

        $totalTasks = (clone $tasksQuery)->count();
        $completedTasks = (clone $tasksQuery)->where('status', 'Completed')->count();
        $inProgressTasks = (clone $tasksQuery)->where('status', 'In Progress')->count();
        $pendingTasks = (clone $tasksQuery)->where('status', 'Pending')->count();

        $progressPercentage = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        $highPriorityCount = (clone $tasksQuery)->where('priority', 'High')->count();
        $mediumPriorityCount = (clone $tasksQuery)->where('priority', 'Medium')->count();
        $lowPriorityCount = (clone $tasksQuery)->where('priority', 'Low')->count();

        // Upcoming deadlines: tasks not completed ordered by deadline
        $upcomingDeadlines = (clone $tasksQuery)
            ->with(['taskList', 'assignee', 'creator'])
            ->where('status', '!=', 'Completed')
            ->orderBy('deadline', 'asc')
            ->take(6)
            ->get();

        // Tasks assigned directly to this user
        $myAssignedTasksCount = Task::where('assigned_to', $user->id)
            ->where('status', '!=', 'Completed')
            ->count();

        return view('dashboard.user', compact(
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'pendingTasks',
            'progressPercentage',
            'highPriorityCount',
            'mediumPriorityCount',
            'lowPriorityCount',
            'upcomingDeadlines',
            'myAssignedTasksCount'
        ));
    }
}
