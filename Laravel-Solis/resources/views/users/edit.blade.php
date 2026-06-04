@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-7 col-lg-6">
        <div class="card">
            <div class="card-header-clean pb-3 border-bottom" style="border-color:var(--border)!important">
                <i class="bi bi-pencil-square me-2 text-primary"></i>Edit User
            </div>
            <div class="card-body p-3 p-sm-4">

                {{-- User avatar preview --}}
                <div class="d-flex align-items-center gap-3 mb-4 p-3" style="background:var(--body-bg);border-radius:12px">
                    @if($user->profile_picture)
                        <img src="{{ $user->profile_picture }}" class="rounded-circle"
                            width="52" height="52" style="object-fit:cover;border:2px solid #e0e7ff;flex-shrink:0">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:52px;height:52px;background:linear-gradient(135deg,#6366f1,#8b5cf6);font-size:1.2rem;font-weight:700;color:#fff">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="fw-semibold" style="font-size:0.9rem">{{ $user->name }}</div>
                        <div class="text-muted" style="font-size:0.78rem">{{ $user->email }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">
                            New Password
                            <span class="text-muted fw-normal" style="font-size:0.75rem">(leave blank to keep current)</span>
                        </label>
                        <input type="password" name="password" class="form-control" placeholder="Enter new password">
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary btn-sm px-4">Save Changes</button>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
