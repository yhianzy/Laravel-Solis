@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card overflow-hidden">
            <!-- Cover -->
            <div style="height:100px;background:linear-gradient(135deg,#6366f1,#06b6d4)"></div>
            <div class="card-body pt-0 px-4 pb-4">
                <div class="d-flex justify-content-between align-items-end mb-3" style="margin-top:-44px">
                    @if($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" class="rounded-circle"
                            width="88" height="88" style="object-fit:cover;border:4px solid #fff;box-shadow:0 4px 14px rgba(0,0,0,0.12)">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:88px;height:88px;background:#6366f1;border:4px solid #fff;box-shadow:0 4px 14px rgba(0,0,0,0.12);font-size:2rem;font-weight:700;color:#fff">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary px-3">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                </div>

                <h4 class="fw-700 mb-1" style="font-size:1.2rem">{{ $user->name }}</h4>
                <p class="text-muted mb-3" style="font-size:0.875rem">{{ $user->email }}</p>

                <div class="row g-3">
                    @if($user->gender)
                    <div class="col-md-6">
                        <div style="background:#f8faff;border-radius:10px;padding:12px 16px">
                            <div style="font-size:0.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">Gender</div>
                            <div style="font-size:0.9rem;font-weight:600;color:#1e293b;margin-top:3px">{{ $user->gender }}</div>
                        </div>
                    </div>
                    @endif
                    @if($user->address)
                    <div class="col-md-6">
                        <div style="background:#f8faff;border-radius:10px;padding:12px 16px">
                            <div style="font-size:0.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">Address</div>
                            <div style="font-size:0.9rem;font-weight:600;color:#1e293b;margin-top:3px">{{ $user->address }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div style="background:#f8faff;border-radius:10px;padding:12px 16px">
                            <div style="font-size:0.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">Member Since</div>
                            <div style="font-size:0.9rem;font-weight:600;color:#1e293b;margin-top:3px">{{ $user->created_at->format('F Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:#e0e7ff;border-radius:10px;padding:12px 16px">
                            <div style="font-size:0.7rem;font-weight:600;color:#6366f1;text-transform:uppercase;letter-spacing:0.5px">Movies Added</div>
                            <div style="font-size:1.4rem;font-weight:700;color:#4f46e5;margin-top:3px">{{ $user->movies()->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
