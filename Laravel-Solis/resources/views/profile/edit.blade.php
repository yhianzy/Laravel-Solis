@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header-clean pb-3 border-bottom" style="border-color:#e2e8f0!important">
                <i class="bi bi-person-gear me-2 text-primary"></i>Edit Profile
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <!-- Avatar upload -->
                    <div class="d-flex align-items-center gap-4 mb-4 p-3" style="background:#f8faff;border-radius:12px;border:1px dashed #c7d2fe">
                        @if($user->profile_picture)
                            <img src="{{ Storage::url($user->profile_picture) }}" class="rounded-circle"
                                width="72" height="72" style="object-fit:cover;border:3px solid #e0e7ff">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width:72px;height:72px;background:#6366f1;font-size:1.6rem;font-weight:700;color:#fff;flex-shrink:0">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <label class="form-label mb-1">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control form-control-sm @error('profile_picture') is-invalid @enderror" accept="image/*">
                            @error('profile_picture')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="text-muted mt-1" style="font-size:0.75rem">JPG, PNG or GIF — max 2MB</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select gender</option>
                                @foreach(['Male','Female','Other'] as $g)
                                    <option value="{{ $g }}" {{ old('gender', $user->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control"
                                value="{{ old('address', $user->address) }}" placeholder="Optional">
                        </div>
                    </div>

                    <div class="p-3 mb-4" style="background:#f8faff;border-radius:12px;border:1px solid #e0e7ff">
                        <p class="mb-3" style="font-size:0.82rem;font-weight:600;color:#6366f1">Change Password <span class="text-muted fw-normal">(leave blank to keep current)</span></p>
                        <div class="row">
                            <div class="col-md-6 mb-0">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min 6 characters">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-4">Save Changes</button>
                        <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
