<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MovieList</title>
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
            width: 45%;
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
        .auth-feature {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 12px 16px;
            margin-top: 14px;
            width: 100%;
            max-width: 300px;
        }
        .auth-feature i { color: #a5b4fc; font-size: 1.1rem; }
        .auth-feature span { color: rgba(255,255,255,0.7); font-size: 0.82rem; }

        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .auth-card {
            width: 100%;
            max-width: 400px;
        }
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
        .divider { color: #94a3b8; font-size: 0.8rem; text-align: center; margin: 18px 0; }
        .link-accent { color: #6366f1; font-weight: 600; text-decoration: none; }
        .link-accent:hover { color: #4f46e5; }
        .toast-container { z-index: 9999; }
    </style>
</head>
<body>
    <div class="auth-left">
        <div class="logo-box"><i class="bi bi-film"></i></div>
        <h1>MovieList</h1>
        <p>Your personal movie collection management system</p>
        <div class="auth-feature"><i class="bi bi-collection-play"></i><span>Track your movie collection</span></div>
        <div class="auth-feature"><i class="bi bi-bar-chart-line"></i><span>View stats & analytics</span></div>
        <div class="auth-feature"><i class="bi bi-star"></i><span>Rate & review movies</span></div>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <h2>Welcome back 👋</h2>
            <p class="subtitle">Sign in to your MovieList account</p>

            @if($errors->any())
            <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:0.85rem;border-radius:9px">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check mb-0">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:0.82rem;color:#64748b">Remember me</label>
                    </div>
                </div>
                <button type="submit" class="btn-auth">Sign In</button>
            </form>

            <p class="divider">Don't have an account? <a href="{{ route('register') }}" class="link-accent">Create one</a></p>
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
