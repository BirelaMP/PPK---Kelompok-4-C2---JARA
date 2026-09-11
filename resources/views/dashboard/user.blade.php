@extends('layouts.app')

@section('title', 'User Dashboard')
@section('page-title', 'Overview Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <!-- Total Lists -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Task Lists</span>
                <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                    <i class="bi bi-collection-fill fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1">{{ $totalTaskLists }}</h3>
            <small class="text-muted">Owned & shared with you</small>
        </div>
    </div>

    <!-- Total Tasks -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Total Tasks</span>
                <div class="p-2 bg-info bg-opacity-10 text-info rounded-3">
                    <i class="bi bi-list-task fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1">{{ $totalTasks }}</h3>
            <small class="text-muted">Active in all your lists</small>
        </div>
    </div>

    <!-- Completed Tasks -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Completed</span>
                <div class="p-2 bg-success bg-opacity-10 text-success rounded-3">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-success">{{ $completedTasks }}</h3>
            <small class="text-muted">{{ $progressPercentage }}% completion rate</small>
        </div>
    </div>

    <!-- Pending / In Progress Tasks -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Pending & Ongoing</span>
                <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-3">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-warning">{{ $pendingTasks + $inProgressTasks }}</h3>
            <small class="text-muted">{{ $pendingTasks }} Pending, {{ $inProgressTasks }} In Progress</small>
        </div>
    </div>
</div>

<!-- Progress Bar Card -->
<div class="card mb-4 p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h6 class="fw-bold mb-0">Overall Task Completion Progress</h6>
            <small class="text-muted">{{ $completedTasks }} out of {{ $totalTasks }} tasks finished</small>
        </div>
        <span class="badge bg-primary fs-6 px-3 py-2">{{ $progressPercentage }}%</span>
    </div>
    <div class="progress" style="height: 12px;">
        <div class="progress-bar progress-bar-jara progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>

<div class="row g-4">
    <!-- Upcoming Deadlines -->
    <div class="col-lg-7">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-event text-danger fs-5"></i>
                    <h6 class="fw-bold mb-0">Upcoming Deadlines</h6>
                </div>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-light border text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                @if($upcomingDeadlines->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-check2-circle fs-1 text-success d-block mb-2"></i>
                        <p class="mb-0">No upcoming tasks or deadlines right now!</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-muted">
                                <tr>
                                    <th>Task Title</th>
                                    <th>List</th>
                                    <th>Priority</th>
                                    <th>Deadline</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingDeadlines as $task)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-decoration-none text-dark d-block text-truncate" style="max-width: 220px;">
                                                {{ $task->title }}
                                            </a>
                                            <small class="text-muted">
                                                Assignee: {{ $task->assignee ? $task->assignee->name : 'Unassigned' }}
                                            </small>
                                        </td>
                                        <td>
                                            <a href="{{ route('task-lists.show', $task->taskList) }}" class="badge bg-light text-secondary text-decoration-none border">
                                                {{ $task->taskList->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-priority-{{ $task->priority }}">
                                                {{ $task->priority }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($task->isOverdue())
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                    {{ $task->deadline->format('M d, Y') }} (Overdue)
                                                </span>
                                            @else
                                                <span class="text-secondary small fw-medium">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $task->deadline->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('tasks.update-status', $task) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm d-inline-block w-auto py-0 px-2" style="font-size: 0.75rem;">
                                                    <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Task Lists -->
    <div class="col-lg-5">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-folder2-open text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0">Active Task Lists</h6>
                </div>
                <a href="{{ route('task-lists.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg me-1"></i> New List
                </a>
            </div>
            <div class="card-body">
                @if($recentTaskLists->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-plus fs-1 text-muted d-block mb-2"></i>
                        <p class="mb-2">No task lists yet.</p>
                        <a href="{{ route('task-lists.create') }}" class="btn btn-sm btn-jara">Create your first list</a>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($recentTaskLists as $list)
                            <div class="p-3 border rounded-3 bg-light bg-opacity-50">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="fw-bold mb-0">
                                        <a href="{{ route('task-lists.show', $list) }}" class="text-decoration-none text-dark">
                                            {{ $list->name }}
                                        </a>
                                    </h6>
                                    <span class="badge bg-{{ $list->user_id === Auth::id() ? 'primary' : 'info' }} bg-opacity-25 text-{{ $list->user_id === Auth::id() ? 'primary' : 'info' }}-emphasis border">
                                        {{ $list->user_id === Auth::id() ? 'Owner' : 'Shared' }}
                                    </span>
                                </div>
                                <p class="text-muted small text-truncate mb-2">{{ $list->description ?? 'No description provided' }}</p>

                                <div class="d-flex justify-content-between align-items-center small text-muted mb-1">
                                    <span>{{ $list->tasks_count }} Tasks</span>
                                    <span>{{ $list->progressPercentage() }}% Done</span>
                                </div>
                                <div class="progress mb-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ $list->progressPercentage() }}%;"></div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <div class="avatar-group d-flex align-items-center">
                                        <div class="avatar-circle" title="Owner: {{ $list->owner->name }}">
                                            {{ strtoupper(substr($list->owner->name, 0, 1)) }}
                                        </div>
                                        @foreach($list->members->take(3) as $member)
                                            <div class="avatar-circle" title="Member: {{ $member->name }}">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endforeach
                                        @if($list->members->count() > 3)
                                            <span class="small text-muted ms-3">+{{ $list->members->count() - 3 }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('task-lists.show', $list) }}" class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold">
                                        Open List <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
