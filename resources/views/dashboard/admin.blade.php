@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Administrator System Overview')

@section('content')
<div class="row g-3 mb-4">
    <!-- Total Users -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Total Registered Users</span>
                <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1">{{ $totalUsers }}</h3>
            <div class="small text-muted">
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle">{{ $adminCount }} Admins</span>
                <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle">{{ $regularUserCount }} Users</span>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Active Contributors</span>
                <div class="p-2 bg-success bg-opacity-10 text-success rounded-3">
                    <i class="bi bi-person-check-fill fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-success">{{ $activeUsers }}</h3>
            <small class="text-muted">Users with tasks or lists</small>
        </div>
    </div>

    <!-- Total Task Lists -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Total Task Lists</span>
                <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-3">
                    <i class="bi bi-collection-fill fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-warning">{{ $totalTaskLists }}</h3>
            <small class="text-muted">System-wide collaborative lists</small>
        </div>
    </div>

    <!-- Total Tasks -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-hover h-100 p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Total System Tasks</span>
                <div class="p-2 bg-info bg-opacity-10 text-info rounded-3">
                    <i class="bi bi-card-checklist fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1 text-info">{{ $totalTasks }}</h3>
            <small class="text-muted">{{ $completedTasks }} Completed ({{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%)</small>
        </div>
    </div>
</div>

<!-- Breakdown row: Status & Priority breakdown -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card h-100 shadow-sm p-4">
            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-pie-chart-fill text-primary"></i> Tasks by Status
            </h6>
            <div class="d-flex flex-column gap-3">
                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-semibold text-secondary">Completed</span>
                        <span class="fw-bold text-success">{{ $completedTasks }} ({{ $totalTasks > 0 ? round(($completedTasks/$totalTasks)*100) : 0 }}%)</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ $totalTasks > 0 ? ($completedTasks/$totalTasks)*100 : 0 }}%;"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-semibold text-secondary">In Progress</span>
                        <span class="fw-bold text-info">{{ $inProgressTasks }} ({{ $totalTasks > 0 ? round(($inProgressTasks/$totalTasks)*100) : 0 }}%)</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: {{ $totalTasks > 0 ? ($inProgressTasks/$totalTasks)*100 : 0 }}%;"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-semibold text-secondary">Pending</span>
                        <span class="fw-bold text-secondary">{{ $pendingTasks }} ({{ $totalTasks > 0 ? round(($pendingTasks/$totalTasks)*100) : 0 }}%)</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-secondary" style="width: {{ $totalTasks > 0 ? ($pendingTasks/$totalTasks)*100 : 0 }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100 shadow-sm p-4">
            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-speedometer2 text-danger"></i> Tasks by Priority
            </h6>
            <div class="row g-3 text-center">
                <div class="col-4">
                    <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger-subtle">
                        <span class="badge bg-danger mb-2">High</span>
                        <h4 class="fw-bold text-danger mb-0">{{ $highPriorityTasks }}</h4>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 rounded-3 bg-warning bg-opacity-10 border border-warning-subtle">
                        <span class="badge bg-warning text-dark mb-2">Medium</span>
                        <h4 class="fw-bold text-warning-emphasis mb-0">{{ $mediumPriorityTasks }}</h4>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 rounded-3 bg-primary bg-opacity-10 border border-primary-subtle">
                        <span class="badge bg-primary mb-2">Low</span>
                        <h4 class="fw-bold text-primary mb-0">{{ $lowPriorityTasks }}</h4>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-center">
                <small class="text-muted">Priorities help teams organize urgency across all shared projects.</small>
            </div>
        </div>
    </div>
</div>

<!-- User Management Overview & Recent Tasks -->
<div class="row g-4">
    <!-- Registered Users Overview -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i> Recently Registered Users</h6>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Manage Users</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-muted">
                            <tr>
                                <th>Name / Email</th>
                                <th>Role</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-circle" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'secondary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Global Tasks -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-activity me-2 text-info"></i> Recent System Activity</h6>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-light border">Browse Tasks</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-muted">
                            <tr>
                                <th>Task Title</th>
                                <th>List</th>
                                <th>Status</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTasks as $task)
                                <tr>
                                    <td>
                                        <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-dark text-decoration-none d-block text-truncate" style="max-width: 180px;">
                                            {{ $task->title }}
                                        </a>
                                        <small class="text-muted">By: {{ $task->creator->name }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">
                                            {{ $task->taskList->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-status-{{ str_replace(' ', '', $task->status) }}">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $task->deadline->format('M d, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
