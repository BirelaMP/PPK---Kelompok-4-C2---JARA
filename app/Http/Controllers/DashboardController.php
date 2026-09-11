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
     * Display the dashboard (admin or user).
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->userDashboard($user);
    }

    /**
     * Render the admin dashboard view.
     */
    protected function adminDashboard(): View
    {
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $regularUserCount = User::where('role', 'user')->count();

        // Active users: users with tasks or task lists
        $activeUsers = User::whereHas('createdTasks')
            ->orWhereHas('assignedTasks')
            ->orWhereHas('ownedTaskLists')
            ->orWhereHas('sharedTaskLists')
            ->distinct()
            ->count();

        $totalTaskLists = TaskList::count();
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'Completed')->count();
        $inProgressTasks = Task::where('status', 'In Progress')->count();
        $pendingTasks = Task::where('status', 'Pending')->count();

        $highPriorityTasks = Task::where('priority', 'High')->count();
        $mediumPriorityTasks = Task::where('priority', 'Medium')->count();
        $lowPriorityTasks = Task::where('priority', 'Low')->count();

        $recentUsers = User::latest()->take(5)->get();
        $recentTasks = Task::with(['taskList', 'creator', 'assignee'])->latest()->take(6)->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'adminCount',
            'regularUserCount',
            'activeUsers',
            'totalTaskLists',
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'pendingTasks',
            'highPriorityTasks',
            'mediumPriorityTasks',
            'lowPriorityTasks',
            'recentUsers',
            'recentTasks'
        ));
    }

    /**
     * Render the user dashboard view.
     */
    protected function userDashboard(User $user): View
    {
        // Get all task lists accessible by this user (owned or shared)
        $taskListIds = TaskList::where('user_id', $user->id)
            ->orWhereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->pluck('id');

        $totalTaskLists = $taskListIds->count();

        $tasksQuery = Task::whereIn('task_list_id', $taskListIds);
        $totalTasks = (clone $tasksQuery)->count();
        $completedTasks = (clone $tasksQuery)->where('status', 'Completed')->count();
        $inProgressTasks = (clone $tasksQuery)->where('status', 'In Progress')->count();
        $pendingTasks = (clone $tasksQuery)->where('status', 'Pending')->count();

        $progressPercentage = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        // Upcoming deadlines: tasks due soon (within next 7 days or not completed)
        $upcomingDeadlines = (clone $tasksQuery)
            ->with(['taskList', 'assignee'])
            ->where('status', '!=', 'Completed')
            ->orderBy('deadline', 'asc')
            ->take(6)
            ->get();

        // Recent task lists
        $recentTaskLists = TaskList::whereIn('id', $taskListIds)
            ->with(['owner', 'members', 'tasks'])
            ->withCount('tasks')
            ->latest()
            ->take(4)
            ->get();

        // Tasks assigned directly to this user
        $myAssignedTasksCount = Task::where('assigned_to', $user->id)
            ->where('status', '!=', 'Completed')
            ->count();

        return view('dashboard.user', compact(
            'totalTaskLists',
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'pendingTasks',
            'progressPercentage',
            'upcomingDeadlines',
            'recentTaskLists',
            'myAssignedTasksCount'
        ));
    }
}
