@extends('layouts.app')

@section('title', $taskList->name)
@section('page-title', 'Task List Overview')

@section('content')
<!-- Header Card -->
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-1">
                        <i class="bi bi-folder2 me-1"></i> Task List
                    </span>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-1">
                        Owner: {{ $taskList->owner?->name ?? 'You' }}
                    </span>
                </div>
                <h3 class="fw-bold text-dark mb-1">{{ $taskList->name }}</h3>
                <p class="text-muted small mb-0">{{ $taskList->description ?? 'No description provided.' }}</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('tasks.create', ['task_list_id' => $taskList->id]) }}" class="btn btn-sm btn-jara">
                    <i class="bi bi-plus-lg me-1"></i> Add Task
                </a>
                @if(Auth::user()?->isAdmin() || $taskList->isOwnedBy(Auth::user()))
                    <a href="{{ route('task-lists.edit', $taskList) }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <form action="{{ route('task-lists.destroy', $taskList) }}" method="POST" onsubmit="return confirm('Delete this task list and all its tasks permanently?');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="row g-3 mt-3 pt-3 border-top">
            <div class="col-6 col-md-3">
                <div class="small text-muted fw-semibold">Progress</div>
                <div class="fs-5 fw-bold text-dark">{{ $progress }}%</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="small text-muted fw-semibold">Total Tasks</div>
                <div class="fs-5 fw-bold text-dark">{{ $totalCount }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="small text-muted fw-semibold">Completed</div>
                <div class="fs-5 fw-bold text-success">{{ $completedCount }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="small text-muted fw-semibold">Pending / In Progress</div>
                <div class="fs-5 fw-bold text-warning">{{ $pendingCount + $inProgressCount }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Tasks Listing -->
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Tasks in this List</h6>
        <a href="{{ route('task-lists.index') }}" class="btn btn-sm btn-light border">
            <i class="bi bi-arrow-left me-1"></i> Back to Lists
        </a>
    </div>
    <div class="card-body p-0">
        @if($tasks->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-clipboard-check fs-1 d-block mb-2"></i>
                <p class="mb-2">No tasks found in this task list.</p>
                <a href="{{ route('tasks.create', ['task_list_id' => $taskList->id]) }}" class="btn btn-sm btn-jara">
                    Create the first task
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th class="ps-4">Title</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Deadline</th>
                            <th>Assignee</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ $task->title }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-status-{{ str_replace(' ', '', $task->status) }} px-2 py-1">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-priority-{{ $task->priority }} px-2 py-1">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td>
                                    {{ $task->deadline ? $task->deadline->format('M d, Y') : '-' }}
                                </td>
                                <td>
                                    {{ $task->assignee?->name ?? 'Unassigned' }}
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-light border p-1 px-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $tasks->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
