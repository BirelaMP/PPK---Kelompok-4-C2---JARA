@extends('layouts.app')

@section('title', 'Task Dashboard')
@section('page-title', 'Overview Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <!-- Total Tasks -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Total Tasks</span>
                <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                    <i class="bi bi-list-task fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1">{{ $totalTasks }}</h3>
            <small class="text-muted">Managed tasks</small>
        </div>
    </div>

    <!-- In Progress Tasks -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">In Progress</span>
                <div class="p-2 bg-info bg-opacity-10 text-info rounded-3">
                    <i class="bi bi-arrow-repeat fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-info">{{ $inProgressTasks }}</h3>
            <small class="text-muted">Currently active</small>
        </div>
    </div>

    <!-- Pending Tasks -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Pending</span>
                <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-3">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-warning">{{ $pendingTasks }}</h3>
            <small class="text-muted">Awaiting start</small>
        </div>
    </div>

    <!-- Completed Tasks -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3 shadow-sm border-0">
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
</div>

<!-- Progress Bar Card -->
<div class="card mb-4 p-4 shadow-sm border-0">
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
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-event text-danger fs-5"></i>
                    <h6 class="fw-bold mb-0">Upcoming Deadlines</h6>
                </div>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-light border text-decoration-none">View All Tasks</a>
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
                                    <th>Task</th>
                                    <th>Priority</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingDeadlines as $task)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-dark text-decoration-none">
                                                {{ $task->title }}
                                            </a>
                                            @if($task->taskList)
                                                <div class="small text-muted">{{ $task->taskList->name }}</div>
                                            @endif
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
                                                <span class="small text-secondary">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $task->deadline->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('tasks.update-status', $task) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm py-0 px-2" style="font-size: 0.75rem; width: 110px;">
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

    <!-- Tasks by Priority & Quick Actions -->
    <div class="col-lg-5">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-fill text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0">Tasks by Priority</h6>
                </div>
                <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-jara">
                    <i class="bi bi-plus-lg me-1"></i> New Task
                </a>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3 mb-4">
                    <!-- High Priority -->
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 border bg-danger bg-opacity-10">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge badge-priority-High px-3 py-2">High</span>
                            <span class="fw-semibold text-danger">High Priority Tasks</span>
                        </div>
                        <span class="fs-5 fw-bold text-danger">{{ $highPriorityCount }}</span>
                    </div>

                    <!-- Medium Priority -->
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 border bg-warning bg-opacity-10">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge badge-priority-Medium px-3 py-2">Medium</span>
                            <span class="fw-semibold text-warning-emphasis">Medium Priority Tasks</span>
                        </div>
                        <span class="fs-5 fw-bold text-warning-emphasis">{{ $mediumPriorityCount }}</span>
                    </div>

                    <!-- Low Priority -->
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 border bg-info bg-opacity-10">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge badge-priority-Low px-3 py-2">Low</span>
                            <span class="fw-semibold text-info-emphasis">Low Priority Tasks</span>
                        </div>
                        <span class="fs-5 fw-bold text-info-emphasis">{{ $lowPriorityCount }}</span>
                    </div>
                </div>

                <!-- Quick Navigation Links -->
                <div class="p-3 bg-light rounded-3 border">
                    <div class="fw-semibold small text-secondary mb-2">Quick Task Management</div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-list-check me-1"></i> Manage All Tasks
                        </a>
                        <a href="{{ route('tasks.index', ['priority' => 'High']) }}" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-exclamation-circle me-1"></i> View Urgent (High Priority) Tasks
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
