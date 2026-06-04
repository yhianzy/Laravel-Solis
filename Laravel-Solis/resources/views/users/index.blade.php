@extends('layouts.app')
@section('title', 'Users Management')

@section('content')

{{-- Top bar --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <p class="text-muted mb-0" style="font-size:0.875rem">{{ $users->count() }} registered user{{ $users->count() != 1 ? 's' : '' }}</p>
    <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus me-1"></i>
        <span class="d-none d-sm-inline">Add User</span>
        <span class="d-sm-none">Add</span>
    </button>
</div>

{{-- Desktop Table --}}
<div class="card d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($user->profile_picture)
                                <img src="{{ $user->profile_picture }}" class="rounded-circle" width="34" height="34"
                                    style="object-fit:cover;border:2px solid #e0e7ff;flex-shrink:0">
                            @else
                                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:700;color:#fff;flex-shrink:0">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                            @endif
                            <span class="fw-semibold" style="font-size:0.875rem">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="text-muted" style="font-size:0.85rem">{{ $user->email }}</td>
                    <td class="text-muted" style="font-size:0.85rem">{{ $user->created_at->format('M d, Y') }}</td>
                    <td style="white-space:nowrap">
                        <div class="d-flex gap-1">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Cards --}}
<div class="d-md-none">
    @forelse($users as $user)
    <div class="card mb-3">
        <div class="card-body p-3">
            <div class="d-flex align-items-center gap-3">
                {{-- Avatar --}}
                @if($user->profile_picture)
                    <img src="{{ $user->profile_picture }}" class="rounded-circle flex-shrink-0"
                        width="46" height="46" style="object-fit:cover;border:2px solid #e0e7ff">
                @else
                    <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center"
                        style="width:46px;height:46px;background:linear-gradient(135deg,#6366f1,#8b5cf6);font-size:1rem;font-weight:700;color:#fff">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                @endif

                {{-- Info --}}
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold" style="font-size:0.9rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $user->name }}
                    </div>
                    <div class="text-muted" style="font-size:0.78rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $user->email }}
                    </div>
                    <div class="text-muted" style="font-size:0.72rem;margin-top:2px">
                        <i class="bi bi-calendar3 me-1"></i>{{ $user->created_at->format('M d, Y') }}
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-1 flex-shrink-0">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
            No users found.
        </div>
    </div>
    @endforelse
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2 text-primary"></i>Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="John Doe" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="john@example.com" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimum 6 characters" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', () => new bootstrap.Modal(document.getElementById('addUserModal')).show());
</script>
@endif
@endsection
