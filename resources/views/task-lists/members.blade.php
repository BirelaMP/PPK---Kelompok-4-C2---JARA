@extends('layouts.app')

@section('title', 'Manage Team Members - ' . $taskList->name)
@section('page-title', 'Team Members: ' . $taskList->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="{{ route('task-lists.index') }}" class="text-decoration-none">Task Lists</a></li>
                <li class="breadcrumb-item"><a href="{{ route('task-lists.show', $taskList) }}" class="text-decoration-none">{{ $taskList->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Members</li>
            </ol>
        </nav>
        <h4 class="fw-bold text-dark mb-0">Team Collaboration & Members</h4>
    </div>

    <a href="{{ route('task-lists.show', $taskList) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Task List
    </a>
</div>

<div class="row g-4">
    <!-- Left Column: Current Members Table -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-people-fill text-primary me-2"></i> Current Team Members ({{ $taskList->members->count() + 1 }})
                </h6>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-muted">
                            <tr>
                                <th>Member Name & Email</th>
                                <th>Role in List</th>
                                <th class="text-center">Assigned Tasks in List</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Owner Row -->
                            <tr class="table-light">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle bg-primary text-white" style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($taskList->owner->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">
                                                {{ $taskList->owner->name }}
                                                @if((int)$taskList->owner->id === (int)Auth::id())
                                                    <span class="badge bg-secondary-subtle text-secondary ms-1">You</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $taskList->owner->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary px-3 py-1">
                                        <i class="bi bi-star-fill me-1"></i> Owner
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-secondary border">
                                        {{ $taskList->tasks()->where('assigned_to', $taskList->owner->id)->count() }} Tasks
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-light text-muted border">List Creator</span>
                                </td>
                            </tr>

                            <!-- Invited Collaborators -->
                            @forelse($taskList->members as $member)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-circle bg-info bg-opacity-25 text-info-emphasis" style="width: 38px; height: 38px;">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">
                                                    {{ $member->name }}
                                                    @if((int)$member->id === (int)Auth::id())
                                                        <span class="badge bg-secondary-subtle text-secondary ms-1">You</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted">{{ $member->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-20 text-info-emphasis border border-info-subtle px-3 py-1">
                                            <i class="bi bi-person-check me-1"></i> Collaborator
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-secondary border">
                                            {{ $member->assignedTasks ? $member->assignedTasks->count() : 0 }} Tasks
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if(Auth::user()->isAdmin() || $taskList->isOwnedBy(Auth::user()))
                                            <form action="{{ route('task-lists.members.remove', [$taskList, $member]) }}" method="POST" onsubmit="return confirm('Remove {{ $member->name }} from this list?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-person-x me-1"></i> Remove
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">No permissions</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-people fs-2 text-muted d-block mb-2"></i>
                                        <p class="mb-0">No collaborators have been added yet. Use the form on the right to invite team members!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Invite Member Card -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-plus-fill text-primary me-2"></i> Invite New Member</h6>
            </div>
            <div class="card-body p-4">
                @if(!Auth::user()->isAdmin() && !$taskList->isOwnedBy(Auth::user()))
                    <div class="alert alert-secondary small mb-0">
                        <i class="bi bi-info-circle me-1"></i> Only the task list owner or a system administrator can invite new members.
                    </div>
                @else
                    <p class="text-muted small mb-3">
                        Select an existing registered user to invite them to <strong>{{ $taskList->name }}</strong>.
                    </p>

                    @if($availableUsers->isEmpty())
                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-check-circle me-1"></i> All registered users in the system are already members of this task list!
                        </div>
                    @else
                        <form action="{{ route('task-lists.members.add', $taskList) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="user_id" class="form-label small fw-semibold text-secondary">User to Invite <span class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                    <option value="">-- Choose User --</option>
                                    @foreach($availableUsers as $candidate)
                                        <option value="{{ $candidate->id }}">
                                            {{ $candidate->name }} ({{ $candidate->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-jara w-100">
                                <i class="bi bi-envelope-plus me-1"></i> Send Invitation
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <!-- Task List Summary Info -->
        <div class="card shadow-sm border-0 p-3 bg-light">
            <h6 class="fw-bold text-dark mb-2">About Collaboration in JARA</h6>
            <ul class="small text-muted ps-3 mb-0">
                <li>Collaborators can view all tasks within this list.</li>
                <li>Collaborators can be assigned to specific tasks.</li>
                <li>Collaborators can update task progress and mark tasks completed.</li>
                <li>Only the list owner and administrators can edit list metadata or manage members.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
