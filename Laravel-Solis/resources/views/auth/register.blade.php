<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MovieList</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            background: #f1f5f9;
        }
        .auth-left {
            width: 40%;
            background: linear-gradient(145deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute;
            width: 350px; height: 350px;
            border-radius: 50%;
            background: rgba(99,102,241,0.15);
            top: -80px; right: -80px;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            width: 250px; height: 250px;
            border-radius: 50%;
            background: rgba(99,102,241,0.1);
            bottom: -60px; left: -60px;
        }
        .auth-left .logo-box {
            width: 64px; height: 64px;
            background: #6366f1;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: #fff;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(99,102,241,0.4);
        }
        .auth-left h1 { color: #fff; font-weight: 800; font-size: 2rem; margin-bottom: 12px; }
        .auth-left p { color: rgba(255,255,255,0.55); font-size: 0.95rem; text-align: center; max-width: 280px; }

        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow-y: auto;
        }
        .auth-card { width: 100%; max-width: 420px; }
        .auth-card h2 { font-weight: 700; font-size: 1.5rem; color: #1e293b; }
        .auth-card .subtitle { color: #64748b; font-size: 0.875rem; margin-bottom: 28px; }
        .form-label { font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 5px; }
        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            padding: 10px 14px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        }
        .btn-auth {
            background: #6366f1;
            border: none;
            border-radius: 9px;
            padding: 11px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #fff;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-auth:hover { background: #4f46e5; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.35); }
        .link-accent { color: #6366f1; font-weight: 600; text-decoration: none; }
        .link-accent:hover { color: #4f46e5; }
        .toast-container { z-index: 9999; }
    </style>
</head>
<body>
    <div class="auth-left">
        <div class="logo-box"><i class="bi bi-film"></i></div>
        <h1>MovieList</h1>
        <p>Join thousands of movie lovers tracking their collections</p>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <h2>Create account 🎬</h2>
            <p class="subtitle">Fill in the details below to get started</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="John Doe" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="you@example.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimum 6 characters" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                </div>
                <button type="submit" class="btn-auth">Create Account</button>
            </form>

            <p class="text-center mt-3 mb-0" style="font-size:0.85rem;color:#64748b">
                Already have an account? <a href="{{ route('login') }}" class="link-accent">Sign in</a>
            </p>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @if(session('success'))
        <div class="toast align-items-center text-bg-success border-0 show">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>document.querySelectorAll('.toast').forEach(t => new bootstrap.Toast(t,{delay:4000}).show());</script>
</body>
</html>
