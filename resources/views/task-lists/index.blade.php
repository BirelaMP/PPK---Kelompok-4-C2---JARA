@extends('layouts.app')

@section('title', 'Task Lists')
@section('page-title', 'Task Lists')

@section('content')
<!-- Page Header & Filter -->
<div class="card shadow-sm border-0 rounded-4 p-3 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <form method="GET" action="{{ route('task-lists.index') }}" class="d-flex flex-wrap gap-2 align-items-center flex-grow-1">
            <div class="input-group input-group-sm" style="max-width: 280px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search task lists..." value="{{ request('search') }}">
            </div>

            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('task-lists.index') }}" class="btn btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">All</a>
                <a href="{{ route('task-lists.index', ['filter' => 'owned']) }}" class="btn btn-outline-secondary {{ request('filter') === 'owned' ? 'active' : '' }}">My Lists</a>
                <a href="{{ route('task-lists.index', ['filter' => 'shared']) }}" class="btn btn-outline-secondary {{ request('filter') === 'shared' ? 'active' : '' }}">Shared</a>
            </div>

            @if(request()->anyFilled(['search', 'filter']))
                <a href="{{ route('task-lists.index') }}" class="btn btn-sm btn-light border" title="Reset Filters"><i class="bi bi-arrow-counterclockwise"></i></a>
            @endif
        </form>

        <a href="{{ route('task-lists.create') }}" class="btn btn-sm btn-jara text-nowrap">
            <i class="bi bi-plus-lg me-1"></i> Create List
        </a>
    </div>
</div>

@if($taskLists->isEmpty())
    <div class="card p-5 text-center shadow-sm border-0 rounded-4">
        <i class="bi bi-folder-x fs-1 text-muted mb-3"></i>
        <h5 class="fw-bold">No Task Lists Found</h5>
        <p class="text-muted small">You don't have any task lists matching your filter. Create a new list to organize your team tasks!</p>
        <div class="mt-2">
            <a href="{{ route('task-lists.create') }}" class="btn btn-jara">
                <i class="bi bi-plus-lg me-1"></i> Create New Task List
            </a>
        </div>
    </div>
@else
    <div class="row g-4 mb-4">
        @foreach($taskLists as $list)
            <div class="col-md-6 col-xl-4">
                <div class="card card-hover h-100 shadow-sm border-0 rounded-4 d-flex flex-column">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">
                                <a href="{{ route('task-lists.show', $list) }}" class="text-dark text-decoration-none">
                                    {{ $list->name }}
                                </a>
                            </h5>
                            <span class="badge bg-{{ $list->user_id === Auth::id() ? 'primary' : 'info' }} bg-opacity-10 text-{{ $list->user_id === Auth::id() ? 'primary' : 'info' }} border border-{{ $list->user_id === Auth::id() ? 'primary' : 'info' }}-subtle">
                                {{ $list->user_id === Auth::id() ? 'Owner' : 'Member' }}
                            </span>
                        </div>

                        <p class="text-muted small text-truncate-2 mb-3" style="min-height: 40px;">
                            {{ $list->description ?? 'No description provided for this task group.' }}
                        </p>

                        <!-- Progress Bar -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Progress</span>
                                <span class="fw-bold">{{ $list->progressPercentage() }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar progress-bar-jara" style="width: {{ $list->progressPercentage() }}%;"></div>
                            </div>
                        </div>

                        <!-- Stats & Team -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                            <div class="avatar-group d-flex align-items-center">
                                <div class="avatar-circle" title="Owner: {{ $list->owner?->name ?? 'User' }}" style="border: 2px solid #4f46e5;">
                                    {{ strtoupper(substr($list->owner?->name ?? 'U', 0, 1)) }}
                                </div>
                                @if(isset($list->members))
                                    @foreach($list->members->take(3) as $member)
                                        <div class="avatar-circle" title="Member: {{ $member->name }}">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($list->members->count() > 3)
                                        <span class="small text-muted ms-3">+{{ $list->members->count() - 3 }}</span>
                                    @endif
                                @endif
                            </div>

                            <div class="small fw-semibold text-secondary">
                                <i class="bi bi-check2-square me-1"></i> {{ $list->tasks_count ?? $list->tasks()->count() }} Tasks
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top py-2 px-4 d-flex justify-content-between align-items-center rounded-bottom-4">
                        <a href="{{ route('task-lists.show', $list) }}" class="btn btn-sm btn-link text-primary p-0 text-decoration-none fw-semibold">
                            View Details <i class="bi bi-arrow-right"></i>
                        </a>

                        @if(Auth::user()?->isAdmin() || $list->isOwnedBy(Auth::user()))
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light p-1" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li>
                                        <a class="dropdown-item small" href="{{ route('task-lists.edit', $list) }}">
                                            <i class="bi bi-pencil me-2 text-warning"></i> Edit List
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('task-lists.destroy', $list) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this list and all its tasks? This action is atomic and cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item small text-danger">
                                                <i class="bi bi-trash me-2"></i> Delete List
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $taskLists->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection
