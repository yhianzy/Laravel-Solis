<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieList - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:        #6366f1;
            --primary-dark:   #4f46e5;
            --primary-light:  #e0e7ff;
            --body-bg:        #f1f5f9;
            --card-bg:        #ffffff;
            --text-main:      #1e293b;
            --text-muted:     #64748b;
            --border:         #e2e8f0;
            --table-hover:    #f8faff;
            --input-bg:       #ffffff;
            --navbar-h:       64px;
        }
        [data-theme="dark"] {
            --body-bg:   #0f172a;
            --card-bg:   #1e293b;
            --text-main: #f1f5f9;
            --text-muted:#94a3b8;
            --border:    #334155;
            --table-hover:#263348;
            --input-bg:  #0f172a;
        }

        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: var(--body-bg); color: var(--text-main); transition: background .2s, color .2s; margin: 0; padding-top: var(--navbar-h); }

        /* ══════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════ */
        .navbar-wrap {
            position: fixed; top: 0; left: 0; right: 0; z-index: 300;
            height: var(--navbar-h);
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #1e1b4b 100%);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            box-shadow: 0 4px 24px rgba(0,0,0,0.28);
            backdrop-filter: blur(12px);
        }
        .navbar-inner {
            height: 100%; max-width: 1400px; margin: 0 auto;
            padding: 0 24px; display: flex; align-items: center; gap: 6px;
        }

        .nb-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; margin-right: 12px; flex-shrink: 0;
        }
        .nb-brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #818cf8 0%, #6366f1 50%, #4f46e5 100%);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; color: #fff;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.12) inset,
                        0 4px 16px rgba(99,102,241,0.55),
                        0 1px 3px rgba(0,0,0,0.3);
            position: relative; overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .nb-brand-icon::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 50%;
            background: linear-gradient(180deg, rgba(255,255,255,0.22), transparent);
            border-radius: 12px 12px 0 0;
        }
        .nb-brand-icon i { position: relative; z-index: 1; }
        .nb-brand:hover .nb-brand-icon {
            transform: scale(1.06);
            box-shadow: 0 0 0 1px rgba(255,255,255,0.18) inset,
                        0 6px 22px rgba(99,102,241,0.7),
                        0 1px 3px rgba(0,0,0,0.3);
        }
        .nb-brand-text {
            color: #fff; font-size: 1.1rem; font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #fff 30%, #c7d2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Separator */
        .nb-sep {
            width: 1px; height: 26px;
            background: linear-gradient(180deg, transparent, rgba(255,255,255,0.18), transparent);
            margin: 0 10px; flex-shrink: 0;
        }

        /* Group label */
        .nb-group-label {
            font-size: 0.6rem; font-weight: 700; letter-spacing: 1.2px;
            text-transform: uppercase; color: rgba(255,255,255,0.28);
            padding: 0 2px; flex-shrink: 0;
        }

        /* Nav links */
        .nb-link {
            position: relative; display: flex; align-items: center; gap: 7px;
            color: rgba(255,255,255,0.6); font-size: 0.84rem; font-weight: 500;
            padding: 8px 13px; border-radius: 10px; text-decoration: none;
            transition: all .2s; white-space: nowrap; flex-shrink: 0;
        }
        .nb-link i { font-size: 0.95rem; }
        .nb-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .nb-link.active {
            background: rgba(99,102,241,0.22);
            color: #a5b4fc;
            box-shadow: inset 0 0 0 1px rgba(99,102,241,0.35);
        }
        .nb-link.active::after {
            content: ''; position: absolute; bottom: -1px; left: 50%;
            transform: translateX(-50%);
            width: 20px; height: 2px;
            background: linear-gradient(90deg, transparent, #6366f1, transparent);
            border-radius: 2px;
        }

        /* Right side */
        .nb-right { margin-left: auto; display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

        /* Icon buttons */
        .nb-icon-btn {
            width: 36px; height: 36px; border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.7); font-size: 0.95rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .2s; text-decoration: none;
        }
        .nb-icon-btn:hover {
            background: rgba(255,255,255,0.14);
            border-color: rgba(255,255,255,0.22);
            color: #fff;
        }
        .nb-icon-btn.danger:hover {
            background: rgba(239,68,68,0.2);
            border-color: rgba(239,68,68,0.4);
            color: #fca5a5;
        }

        /* User pill */
        .nb-user {
            display: flex; align-items: center; gap: 9px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 40px; padding: 4px 14px 4px 5px;
            cursor: pointer; transition: all .2s;
        }
        .nb-user:hover { background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.2); }
        .nb-user-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.72rem; font-weight: 700;
            overflow: hidden; flex-shrink: 0;
        }
        .nb-user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .nb-user-name { color: rgba(255,255,255,0.82); font-size: 0.82rem; font-weight: 600; }

        /* Hamburger */
        .nb-hamburger {
            display: none; width: 36px; height: 36px; border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.14);
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.8); font-size: 1.1rem;
            align-items: center; justify-content: center;
            cursor: pointer; transition: all .2s;
        }
        .nb-hamburger:hover { background: rgba(255,255,255,0.12); }

        /* ══════════════════════════════════════
           MOBILE DRAWER
        ══════════════════════════════════════ */
        .nb-drawer {
            display: none; position: fixed;
            top: var(--navbar-h); left: 0; right: 0; bottom: 0; z-index: 299;
            background: linear-gradient(160deg, #1e1b4b, #0f172a);
            padding: 16px; flex-direction: column; gap: 3px; overflow-y: auto;
        }
        .nb-drawer.open { display: flex; }
        .nb-drawer .nb-link {
            font-size: 0.9rem; padding: 12px 16px; border-radius: 11px;
            color: rgba(255,255,255,0.65);
        }
        .nb-drawer .nb-link.active {
            background: rgba(99,102,241,0.2);
            color: #a5b4fc;
        }
        .nb-drawer-section {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 1.2px;
            text-transform: uppercase; color: rgba(255,255,255,0.28);
            padding: 14px 6px 5px;
        }
        .nb-drawer-footer {
            margin-top: auto; padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex; gap: 8px;
        }
        .nb-drawer-footer button, .nb-drawer-footer .btn-logout-full {
            flex: 1; padding: 10px; border-radius: 10px; font-size: 0.82rem;
            font-weight: 600; display: flex; align-items: center;
            justify-content: center; gap: 8px; cursor: pointer; transition: all .2s;
        }
        .nb-drawer-footer .btn-dark-m {
            background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.14);
            color: rgba(255,255,255,0.75);
        }
        .nb-drawer-footer .btn-dark-m:hover { background: rgba(99,102,241,0.2); color: #a5b4fc; }
        .nb-drawer-footer .btn-logout-full {
            background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3);
            color: #fca5a5;
        }
        .nb-drawer-footer .btn-logout-full:hover { background: rgba(239,68,68,0.22); }

        /* ══════════════════════════════════════
           PAGE LAYOUT
        ══════════════════════════════════════ */
        .page-wrap { max-width: 1400px; margin: 0 auto; padding: 28px 24px; }

        /* Page header */
        .page-hdr {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 22px; gap: 12px;
        }
        .page-hdr-left { display: flex; align-items: center; gap: 10px; }
        .page-hdr-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 11px; display: flex; align-items: center;
            justify-content: center; font-size: 1rem; color: #fff;
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }
        .page-hdr-title { font-size: 1.15rem; font-weight: 700; color: var(--text-main); }
        .page-hdr-sub { font-size: 0.78rem; color: var(--text-muted); margin-top: 1px; }

        /* ══════════════════════════════════════
           CARDS
        ══════════════════════════════════════ */
        .card { border: 1px solid var(--border); box-shadow: 0 1px 6px rgba(0,0,0,0.05); border-radius: 14px; background: var(--card-bg); }
        .card-header-clean { padding: 16px 18px 0; font-weight: 700; font-size: 0.9rem; color: var(--text-main); background: transparent; border: none; }

        /* Stat cards */
        .stat-card { border-radius: 14px; padding: 20px; color: #fff; border: none; position: relative; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.08); }
        .stat-card .stat-icon { font-size: 1.8rem; opacity: 0.22; position: absolute; bottom: 10px; right: 16px; }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1; }
        .stat-card .stat-label { font-size: 0.75rem; opacity: 0.78; margin-top: 4px; }

        /* Table */
        .table-responsive { border-radius: 14px; overflow: hidden; }
        .table thead th { background: var(--body-bg); color: var(--text-muted); font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border); padding: 11px 14px; white-space: nowrap; }
        .table tbody td { padding: 11px 14px; vertical-align: middle; border-color: var(--border); font-size: 0.85rem; color: var(--text-main); }
        .table tbody tr:hover { background: var(--table-hover); }

        /* Buttons */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); color: #fff; }

        /* Badges */
        .badge-genre    { background: var(--primary-light); color: #3730a3; font-weight: 600; font-size: 0.7rem; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
        .badge-rating   { background: #fef3c7; color: #92400e; font-weight: 600; font-size: 0.7rem; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
        .badge-watched  { background: #d1fae5; color: #065f46; font-size: 0.7rem; font-weight: 600; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
        .badge-unwatched{ background: #fee2e2; color: #991b1b; font-size: 0.7rem; font-weight: 600; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
        .badge-watchlist{ background: #e0e7ff; color: #3730a3; font-size: 0.7rem; font-weight: 600; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }

        /* Forms */
        .form-control, .form-select { border-color: var(--border); border-radius: 8px; font-size: 0.875rem; padding: 8px 12px; background: var(--input-bg); color: var(--text-main); }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
        .form-label { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 4px; }

        /* Modal */
        .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); background: var(--card-bg); }
        .modal-header { border-bottom: 1px solid var(--border); padding: 16px 20px; }
        .modal-title  { font-weight: 700; font-size: 0.95rem; color: var(--text-main); }
        .modal-footer { border-top: 1px solid var(--border); }

        /* Movie card */
        .movie-card { border-radius: 14px; overflow: hidden; border: 1px solid var(--border); background: var(--card-bg); transition: transform .2s, box-shadow .2s; cursor: pointer; height: 100%; }
        .movie-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.12); }
        .movie-card .poster-wrap { width: 100%; height: 200px; overflow: hidden; background: linear-gradient(135deg,#6366f1,#06b6d4); display: flex; align-items: center; justify-content: center; }
        .movie-card .poster-wrap img { width: 100%; height: 200px; object-fit: cover; }
        .movie-card .poster-wrap i { font-size: 3rem; color: rgba(255,255,255,0.35); }
        .movie-card .card-body { padding: 12px; }
        .movie-card .movie-title { font-weight: 700; font-size: 0.875rem; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Fav button */
        .fav-btn { background: none; border: none; padding: 4px 6px; font-size: 1rem; line-height: 1; cursor: pointer; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; height: 31px; width: 31px; }
        .fav-btn.active { color: #f59e0b; }
        .fav-btn:not(.active) { color: #cbd5e1; }
        .fav-btn:hover { background: #fef3c7; color: #f59e0b; }

        /* Pagination */
        .pagination .page-link { border-color: var(--border); color: var(--primary); background: var(--card-bg); font-size: 0.85rem; }
        .pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

        /* Toast */
        .toast-container { z-index: 9999; }
        .toast { border-radius: 12px; font-size: 0.875rem; }

        /* Dark overrides */
        [data-theme="dark"] .card { background: var(--card-bg); border-color: var(--border); }
        [data-theme="dark"] .table thead th { background: #0f172a; }
        [data-theme="dark"] .table tbody td { border-color: var(--border); }
        [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background: var(--input-bg); border-color: var(--border); color: var(--text-main); }
        [data-theme="dark"] .modal-content { background: var(--card-bg); }
        [data-theme="dark"] .modal-header, [data-theme="dark"] .modal-footer { border-color: var(--border); }

        /* Responsive */
        @media (max-width: 991px) {
            .nb-desktop { display: none !important; }
            .nb-hamburger { display: flex; }
            .nb-user-name { display: none; }
        }
        @media (min-width: 992px) {
            .nb-drawer { display: none !important; }
        }
        @media (max-width: 767px) {
            .page-wrap { padding: 16px; }
            .stat-card { padding: 16px; }
            .stat-card .stat-value { font-size: 1.4rem; }
            .table thead th, .table tbody td { padding: 9px 10px; font-size: 0.78rem; }
        }
        @media (max-width: 575px) {
            .modal-dialog { margin: 10px; }
            .navbar-inner { padding: 0 14px; }
        }
    </style>
</head>
<body>

<!-- ════════════════════ NAVBAR ════════════════════ -->
<header class="navbar-wrap">
    <div class="navbar-inner">

        <!-- Brand -->
        <a href="{{ route('dashboard') }}" class="nb-brand">
            <div class="nb-brand-icon"><i class="bi bi-film"></i></div>
            <div class="nb-brand-text">MovieList</div>
        </a>

        <div class="nb-sep"></div>

        <!-- Desktop links -->
        <div class="nb-desktop d-flex align-items-center gap-1">
            <span class="nb-group-label">Menu</span>

            <a href="{{ route('dashboard') }}" class="nb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('movies.index') }}" class="nb-link {{ request()->routeIs('movies.*') ? 'active' : '' }}">
                <i class="bi bi-camera-video-fill"></i> My Movies
            </a>

            <div class="nb-sep"></div>
            <span class="nb-group-label">Admin</span>

            <a href="{{ route('users.index') }}" class="nb-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Users
            </a>
            <a href="{{ route('profile.show') }}" class="nb-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profile
            </a>
        </div>

        <!-- Right -->
        <div class="nb-right">

            <!-- User pill -->
            <a href="{{ route('profile.show') }}" class="nb-user text-decoration-none">
                <div class="nb-user-avatar">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ Auth::user()->profile_picture }}" alt="">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <span class="nb-user-name">{{ Auth::user()->name }}</span>
            </a>

            <!-- Dark mode -->
            <button class="nb-icon-btn" onclick="toggleDark()" id="darkBtn" title="Toggle dark mode">
                <i class="bi bi-moon-fill" id="darkIcon"></i>
            </button>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="nb-icon-btn danger" title="Sign out">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>

            <!-- Hamburger -->
            <button class="nb-hamburger" onclick="toggleDrawer()" id="hamburgerBtn">
                <i class="bi bi-list" id="hamburgerIcon"></i>
            </button>
        </div>
    </div>
</header>

<!-- ════════════════════ MOBILE DRAWER ════════════════════ -->
<div class="nb-drawer" id="mobileDrawer">
    <div class="nb-drawer-section">Main Menu</div>
    <a href="{{ route('dashboard') }}" class="nb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('movies.index') }}" class="nb-link {{ request()->routeIs('movies.*') ? 'active' : '' }}">
        <i class="bi bi-camera-video-fill"></i> My Movies
    </a>
    <div class="nb-drawer-section">Administration</div>
    <a href="{{ route('users.index') }}" class="nb-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> Users
    </a>
    <a href="{{ route('profile.show') }}" class="nb-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i> My Profile
    </a>
    <div class="nb-drawer-footer">
        <button class="btn-dark-m" onclick="toggleDark()">
            <i class="bi bi-moon-fill" id="darkIconM"></i>
            <span id="darkLabelM">Dark Mode</span>
        </button>
        <form method="POST" action="{{ route('logout') }}" class="flex-1 mb-0">
            @csrf
            <button class="btn-logout-full w-100">
                <i class="bi bi-box-arrow-right"></i> Sign Out
            </button>
        </form>
    </div>
</div>

<!-- ════════════════════ PAGE ════════════════════ -->
<div class="page-wrap">
    <!-- Page header -->
    <div class="page-hdr">
        <div class="page-hdr-left">
            <div class="page-hdr-icon">
                @if(request()->routeIs('dashboard'))        <i class="bi bi-grid-1x2-fill"></i>
                @elseif(request()->routeIs('movies.*'))     <i class="bi bi-camera-video-fill"></i>
                @elseif(request()->routeIs('users.*'))      <i class="bi bi-people-fill"></i>
                @elseif(request()->routeIs('profile.*'))    <i class="bi bi-person-circle"></i>
                @else                                        <i class="bi bi-circle-fill"></i>
                @endif
            </div>
            <div>
                <div class="page-hdr-title">@yield('title', 'Dashboard')</div>
                <div class="page-hdr-sub">
                    @if(request()->routeIs('dashboard'))        Overview &amp; statistics
                    @elseif(request()->routeIs('movies.*'))     Your personal movie collection
                    @elseif(request()->routeIs('users.*'))      Manage registered users
                    @elseif(request()->routeIs('profile.*'))    Your account settings
                    @else                                        MovieList
                    @endif
                </div>
            </div>
        </div>
    </div>

    @yield('content')
</div>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    @if(session('success'))
    <div class="toast align-items-center text-bg-success border-0 show" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.querySelectorAll('.toast').forEach(t => new bootstrap.Toast(t, {delay:4000}).show());

function toggleDrawer() {
    const open = document.getElementById('mobileDrawer').classList.toggle('open');
    document.getElementById('hamburgerIcon').className = open ? 'bi bi-x-lg' : 'bi bi-list';
}

function toggleDark() {
    const dark = document.documentElement.getAttribute('data-theme') !== 'dark';
    document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
    localStorage.setItem('theme', dark ? 'dark' : 'light');
    document.getElementById('darkIcon').className  = dark ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    const m = document.getElementById('darkIconM');
    const l = document.getElementById('darkLabelM');
    if (m) m.className = dark ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    if (l) l.textContent = dark ? 'Light Mode' : 'Dark Mode';
}
(function(){
    const t = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', t);
    if (t === 'dark') {
        const i = document.getElementById('darkIcon');
        const m = document.getElementById('darkIconM');
        const l = document.getElementById('darkLabelM');
        if (i) i.className = 'bi bi-sun-fill';
        if (m) m.className = 'bi bi-sun-fill';
        if (l) l.textContent = 'Light Mode';
    }
})();
</script>
@yield('scripts')
</body>
</html>
