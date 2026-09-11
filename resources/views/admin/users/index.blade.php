@extends('layouts.app')

@section('title', 'Admin - User Management')
@section('page-title', 'User Management')

@section('content')
<div class="card shadow-sm border-0 mb-4 p-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <!-- Search and Role filter -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex flex-wrap align-items-center gap-2">
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search name or email..." value="{{ request('search') }}">
            </div>

            <select name="role" onchange="this.form.submit()" class="form-select form-select-sm" style="width: 140px;">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin Only</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User Only</option>
            </select>

            <button type="submit" class="btn btn-sm btn-dark">Search</button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-x-lg"></i></a>
            @endif
        </form>

        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-jara text-nowrap">
            <i class="bi bi-person-plus-fill me-1"></i> Add New User
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-people-fill text-primary me-2"></i> Registered Accounts ({{ $users->total() }})
        </h6>
    </div>

    <div class="card-body p-0">
        @if($users->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                <p class="mb-0">No users found matching query.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th class="text-center">Owned Lists</th>
                            <th class="text-center">Created Tasks</th>
                            <th class="text-center">Assigned Tasks</th>
                            <th>Joined Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle" style="width: 36px; height: 36px;">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">
                                                {{ $u->name }}
                                                @if((int)$u->id === (int)Auth::id())
                                                    <span class="badge bg-secondary-subtle text-secondary ms-1">You</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $u->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $u->isAdmin() ? 'danger' : 'primary' }} bg-opacity-15 text-{{ $u->isAdmin() ? 'danger' : 'primary' }} border border-{{ $u->isAdmin() ? 'danger' : 'primary' }}-subtle px-2 py-1">
                                        {{ ucfirst($u->role) }}
                                    </span>
                                </td>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ $u->owned_task_lists_count }}
                                </td>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ $u->created_tasks_count }}
                                </td>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ $u->assigned_tasks_count }}
                                </td>
                                <td class="small text-muted">
                                    {{ $u->created_at->format('M d, Y') }}
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-light border p-1 px-2" title="Edit User">
                                            <i class="bi bi-pencil text-warning"></i>
                                        </a>

                                        @if((int)$u->id !== (int)Auth::id())
                                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete user {{ $u->name }}? This will delete their personal task lists.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border p-1 px-2 text-danger" title="Delete User">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if($users->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
