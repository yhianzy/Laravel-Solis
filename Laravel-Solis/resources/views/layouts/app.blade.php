<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieList - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:       #6366f1;
            --primary-dark:  #4f46e5;
            --primary-light: #e0e7ff;
            --nav-bg:        #1e1b4b;
            --nav-hover:     rgba(99,102,241,0.25);
            --body-bg:       #f1f5f9;
            --card-bg:       #ffffff;
            --text-main:     #1e293b;
            --text-muted:    #64748b;
            --border:        #e2e8f0;
            --table-hover:   #f8faff;
            --input-bg:      #ffffff;
            --navbar-height: 62px;
        }
        [data-theme="dark"] {
            --body-bg:    #0f172a;
            --card-bg:    #1e293b;
            --text-main:  #f1f5f9;
            --text-muted: #94a3b8;
            --border:     #334155;
            --table-hover:#263348;
            --input-bg:   #0f172a;
            --nav-bg:     #0c0a1e;
        }
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: var(--body-bg); color: var(--text-main); transition: background 0.2s, color 0.2s; margin: 0; padding-top: var(--navbar-height); }

        /* ── Top Navbar ── */
        .top-navbar {
            background: var(--nav-bg);
            height: var(--navbar-height);
            position: fixed; top: 0; left: 0; right: 0; z-index: 200;
            display: flex; align-items: center;
            padding: 0 20px;
            gap: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.18);
        }
        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; flex-shrink: 0; margin-right: 8px;
        }
        .nav-brand .brand-icon {
            width: 34px; height: 34px; background: var(--primary);
            border-radius: 9px; display: flex; align-items: center;
            justify-content: center; font-size: 0.95rem; color: #fff;
        }
        .nav-brand .brand-text { color: #fff; font-size: 1rem; font-weight: 700; white-space: nowrap; }

        /* Nav divider */
        .nav-divider {
            width: 1px; height: 28px; background: rgba(255,255,255,0.12);
            margin: 0 6px; flex-shrink: 0;
        }

        /* Nav links */
        .top-navbar .nav-link {
            color: rgba(255,255,255,0.65); padding: 7px 12px; border-radius: 8px;
            font-size: 0.85rem; font-weight: 500; display: flex; align-items: center;
            gap: 7px; transition: all 0.18s; text-decoration: none; white-space: nowrap;
            flex-shrink: 0;
        }
        .top-navbar .nav-link i { font-size: 0.95rem; }
        .top-navbar .nav-link:hover { background: var(--nav-hover); color: #fff; }
        .top-navbar .nav-link.active { background: var(--primary); color: #fff; box-shadow: 0 3px 10px rgba(99,102,241,0.4); }

        /* Section label */
        .nav-section-label {
            font-size: 0.6rem; font-weight: 600; letter-spacing: 1px;
            text-transform: uppercase; color: rgba(255,255,255,0.28);
            padding: 0 4px; flex-shrink: 0;
        }

        /* Right side */
        .nav-right { margin-left: auto; display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

        /* Dark toggle */
        .btn-dark-toggle {
            background: transparent; border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.65); border-radius: 8px; font-size: 0.8rem;
            padding: 6px 11px; display: flex; align-items: center; gap: 6px;
            cursor: pointer; transition: all 0.18s; white-space: nowrap;
        }
        .btn-dark-toggle:hover { background: var(--nav-hover); color: #c7d2fe; }

        /* User badge */
        .user-badge {
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);
            border-radius: 50px; padding: 4px 12px 4px 5px;
            font-size: 0.8rem; font-weight: 500; color: rgba(255,255,255,0.85);
            white-space: nowrap;
        }
        .user-badge img { width: 26px; height: 26px; border-radius: 50%; object-fit: cover; }
        .user-badge .avatar-placeholder {
            width: 26px; height: 26px; border-radius: 50%; background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.72rem; font-weight: 700;
        }

        /* Logout */
        .btn-nav-logout {
            background: transparent; border: 1px solid rgba(239,68,68,0.4);
            color: #fca5a5; border-radius: 8px; font-size: 0.8rem;
            padding: 6px 11px; display: flex; align-items: center; gap: 6px;
            cursor: pointer; transition: all 0.18s;
        }
        .btn-nav-logout:hover { background: rgba(239,68,68,0.18); color: #fecaca; }

        /* Mobile hamburger */
        .btn-hamburger {
            display: none; background: none; border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.75); border-radius: 8px; padding: 6px 10px;
            cursor: pointer; font-size: 1rem;
        }

        /* Mobile nav drawer */
        .mobile-drawer {
            display: none; position: fixed; top: var(--navbar-height); left: 0; right: 0; bottom: 0;
            background: var(--nav-bg); z-index: 199; padding: 16px;
            flex-direction: column; gap: 4px; overflow-y: auto;
        }
        .mobile-drawer.open { display: flex; }
        .mobile-drawer .nav-link {
            color: rgba(255,255,255,0.65); padding: 11px 14px; border-radius: 9px;
            font-size: 0.9rem; font-weight: 500; display: flex; align-items: center;
            gap: 10px; transition: all 0.18s; text-decoration: none;
        }
        .mobile-drawer .nav-link:hover { background: var(--nav-hover); color: #fff; }
        .mobile-drawer .nav-link.active { background: var(--primary); color: #fff; }
        .mobile-drawer-section {
            font-size: 0.65rem; font-weight: 600; letter-spacing: 1px;
            text-transform: uppercase; color: rgba(255,255,255,0.3);
            padding: 14px 6px 4px;
        }
        .mobile-drawer-footer {
            margin-top: auto; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.08);
            display: flex; flex-direction: column; gap: 8px;
        }
        .mobile-drawer-footer .btn-dark-toggle,
        .mobile-drawer-footer .btn-nav-logout { width: 100%; justify-content: flex-start; }

        /* ── Page content ── */
        .page-content { padding: 24px; }

        /* ── Cards ── */
        .card { border: 1px solid var(--border); box-shadow: 0 1px 6px rgba(0,0,0,0.05); border-radius: 14px; background: var(--card-bg); transition: background 0.2s; }
        .card-header-clean { padding: 16px 18px 0; font-weight: 700; font-size: 0.9rem; color: var(--text-main); background: transparent; border: none; }

        /* ── Page header ── */
        .page-header {
            background: var(--card-bg); border-bottom: 1px solid var(--border);
            padding: 14px 24px; margin: -24px -24px 24px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }
        .page-header-title { font-size: 1rem; font-weight: 700; color: var(--text-main); }

        /* ── Stat Cards ── */
        .stat-card { border-radius: 14px; padding: 20px; color: #fff; border: none; position: relative; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.08); }
        .stat-card .stat-icon { font-size: 1.8rem; opacity: 0.25; position: absolute; bottom: 10px; right: 16px; }
        .stat-card .stat-value { font-size: 1.7rem; font-weight: 700; line-height: 1; }
        .stat-card .stat-label { font-size: 0.75rem; opacity: 0.8; margin-top: 4px; }

        /* ── Table ── */
        .table-responsive { border-radius: 14px; overflow: hidden; }
        .table thead th {
            background: var(--body-bg); color: var(--text-muted);
            font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.5px; border-bottom: 1px solid var(--border); padding: 11px 14px;
            white-space: nowrap;
        }
        .table tbody td { padding: 11px 14px; vertical-align: middle; border-color: var(--border); font-size: 0.85rem; color: var(--text-main); }
        .table tbody tr:hover { background: var(--table-hover); }

        /* ── Buttons ── */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); color: #fff; }

        /* ── Badges ── */
        .badge-genre    { background: var(--primary-light); color: var(--primary-dark); font-weight: 600; font-size: 0.7rem; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
        .badge-rating   { background: #fef3c7; color: #92400e; font-weight: 600; font-size: 0.7rem; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
        .badge-watched  { background: #d1fae5; color: #065f46; font-size: 0.7rem; font-weight: 600; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
        .badge-unwatched{ background: #fee2e2; color: #991b1b; font-size: 0.7rem; font-weight: 600; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
        .badge-watchlist{ background: #e0e7ff; color: #3730a3; font-size: 0.7rem; font-weight: 600; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }

        /* ── Forms ── */
        .form-control, .form-select {
            border-color: var(--border); border-radius: 8px; font-size: 0.875rem;
            padding: 8px 12px; background: var(--input-bg); color: var(--text-main);
        }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
        .form-label { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 4px; }

        /* ── Modal ── */
        .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); background: var(--card-bg); }
        .modal-header { border-bottom: 1px solid var(--border); padding: 16px 20px; }
        .modal-title  { font-weight: 700; font-size: 0.95rem; color: var(--text-main); }
        .modal-footer { border-top: 1px solid var(--border); }

        /* ── Movie card view ── */
        .movie-card { border-radius: 14px; overflow: hidden; border: 1px solid var(--border); background: var(--card-bg); transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; height: 100%; }
        .movie-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.12); }
        .movie-card .poster-wrap { width: 100%; height: 200px; overflow: hidden; background: linear-gradient(135deg,#6366f1,#06b6d4); display: flex; align-items: center; justify-content: center; }
        .movie-card .poster-wrap img { width: 100%; height: 200px; object-fit: cover; }
        .movie-card .poster-wrap i { font-size: 3rem; color: rgba(255,255,255,0.35); }
        .movie-card .card-body { padding: 12px; }
        .movie-card .movie-title { font-weight: 700; font-size: 0.875rem; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* ── Fav button ── */
        .fav-btn { background: none; border: none; padding: 4px 6px; font-size: 1rem; line-height: 1; cursor: pointer; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; height: 31px; width: 31px; }
        .fav-btn.active { color: #f59e0b; }
        .fav-btn:not(.active) { color: #cbd5e1; }
        .fav-btn:hover { background: #fef3c7; color: #f59e0b; }

        /* ── Pagination ── */
        .pagination .page-link { border-color: var(--border); color: var(--primary); background: var(--card-bg); font-size: 0.85rem; }
        .pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

        /* ── Toast ── */
        .toast-container { z-index: 9999; }
        .toast { border-radius: 12px; font-size: 0.875rem; }

        /* ── Dark mode overrides ── */
        [data-theme="dark"] .card { background: var(--card-bg); border-color: var(--border); }
        [data-theme="dark"] .table thead th { background: #0f172a; }
        [data-theme="dark"] .table tbody td { border-color: var(--border); }
        [data-theme="dark"] .table tbody tr:hover { background: var(--table-hover); }
        [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background: var(--input-bg); border-color: var(--border); color: var(--text-main); }
        [data-theme="dark"] .modal-content { background: var(--card-bg); }
        [data-theme="dark"] .modal-header, [data-theme="dark"] .modal-footer { border-color: var(--border); }
        [data-theme="dark"] .page-header { background: var(--card-bg); border-color: var(--border); }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .nav-desktop { display: none !important; }
            .btn-hamburger { display: flex; }
        }
        @media (min-width: 992px) {
            .btn-hamburger { display: none; }
            .mobile-drawer { display: none !important; }
        }
        @media (max-width: 767px) {
            .page-content { padding: 14px; }
            .page-header { padding: 12px 14px; margin: -14px -14px 14px; }
            .stat-card { padding: 16px; }
            .stat-card .stat-value { font-size: 1.4rem; }
            .table thead th, .table tbody td { padding: 9px 10px; font-size: 0.78rem; }
            .user-name { display: none; }
        }
        @media (max-width: 575px) {
            .modal-dialog { margin: 10px; }
        }
    </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="top-navbar">

    <!-- Brand -->
    <a href="{{ route('dashboard') }}" class="nav-brand">
        <div class="brand-icon"><i class="bi bi-film"></i></div>
        <span class="brand-text">MovieList</span>
    </a>

    <div class="nav-divider"></div>

    <!-- Desktop nav links -->
    <div class="nav-desktop d-flex align-items-center gap-1">
        <span class="nav-section-label">Menu</span>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('movies.index') }}" class="nav-link {{ request()->routeIs('movies.*') ? 'active' : '' }}">
            <i class="bi bi-camera-video"></i> My Movies
        </a>

        <div class="nav-divider"></div>
        <span class="nav-section-label">Admin</span>

        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
        <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Profile
        </a>
    </div>

    <!-- Right side -->
    <div class="nav-right">
        <!-- User badge -->
        <div class="user-badge">
            @if(Auth::user()->profile_picture)
                <img src="{{ Auth::user()->profile_picture }}" alt="avatar">
            @else
                <div class="avatar-placeholder">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
            @endif
            <span class="user-name">{{ Auth::user()->name }}</span>
        </div>

        <!-- Dark toggle -->
        <button class="btn-dark-toggle" onclick="toggleDark()" id="darkBtn">
            <i class="bi bi-moon" id="darkIcon"></i>
        </button>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mb-0">
            @csrf
            <button class="btn-nav-logout"><i class="bi bi-box-arrow-right"></i></button>
        </form>

        <!-- Hamburger (mobile) -->
        <button class="btn-hamburger" onclick="toggleDrawer()" id="hamburgerBtn">
            <i class="bi bi-list" id="hamburgerIcon"></i>
        </button>
    </div>
</nav>

<!-- Mobile Drawer -->
<div class="mobile-drawer" id="mobileDrawer">
    <div class="mobile-drawer-section">Main Menu</div>
    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2"></i> Dashboard
    </a>
    <a href="{{ route('movies.index') }}" class="nav-link {{ request()->routeIs('movies.*') ? 'active' : '' }}">
        <i class="bi bi-camera-video"></i> My Movies
    </a>

    <div class="mobile-drawer-section">Administration</div>
    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Users
    </a>
    <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i> My Profile
    </a>

    <div class="mobile-drawer-footer">
        <button class="btn-dark-toggle" onclick="toggleDark()">
            <i class="bi bi-moon" id="darkIconMobile"></i>
            <span id="darkLabelMobile">Dark Mode</span>
        </button>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-nav-logout w-100 justify-content-start" style="gap:8px">
                <i class="bi bi-box-arrow-right"></i> Sign Out
            </button>
        </form>
    </div>
</div>

<!-- Page Content -->
<div class="page-content">
    <div class="page-header">
        <div class="page-header-title">@yield('title', 'Dashboard')</div>
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
        const drawer = document.getElementById('mobileDrawer');
        const icon   = document.getElementById('hamburgerIcon');
        const open   = drawer.classList.toggle('open');
        icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
    }

    function toggleDark() {
        const dark = document.documentElement.getAttribute('data-theme') !== 'dark';
        document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
        localStorage.setItem('theme', dark ? 'dark' : 'light');
        const icon  = document.getElementById('darkIcon');
        const iconM = document.getElementById('darkIconMobile');
        const labelM = document.getElementById('darkLabelMobile');
        if (icon)  icon.className  = dark ? 'bi bi-sun' : 'bi bi-moon';
        if (iconM) iconM.className = dark ? 'bi bi-sun' : 'bi bi-moon';
        if (labelM) labelM.textContent = dark ? 'Light Mode' : 'Dark Mode';
    }
    (function(){
        const t = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', t);
        if (t === 'dark') {
            const icon  = document.getElementById('darkIcon');
            const iconM = document.getElementById('darkIconMobile');
            const labelM = document.getElementById('darkLabelMobile');
            if (icon)  icon.className  = 'bi bi-sun';
            if (iconM) iconM.className = 'bi bi-sun';
            if (labelM) labelM.textContent = 'Light Mode';
        }
    })();
</script>
@yield('scripts')
</body>
</html>
