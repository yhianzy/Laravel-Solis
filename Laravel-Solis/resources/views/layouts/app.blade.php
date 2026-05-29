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
            --sidebar-bg:    #1e1b4b;
            --sidebar-hover: rgba(99,102,241,0.18);
            --body-bg:       #f1f5f9;
            --card-bg:       #ffffff;
            --text-main:     #1e293b;
            --text-muted:    #64748b;
            --border:        #e2e8f0;
            --table-hover:   #f8faff;
            --input-bg:      #ffffff;
            --sidebar-width: 255px;
        }
        [data-theme="dark"] {
            --body-bg:    #0f172a;
            --card-bg:    #1e293b;
            --text-main:  #f1f5f9;
            --text-muted: #94a3b8;
            --border:     #334155;
            --table-hover:#263348;
            --input-bg:   #0f172a;
            --sidebar-bg: #0c0a1e;
        }
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: var(--body-bg); color: var(--text-main); transition: background 0.2s, color 0.2s; margin: 0; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 200;
            display: flex; flex-direction: column;
            transition: transform 0.25s ease;
            overflow-y: auto;
        }
        .sidebar.collapsed { transform: translateX(-100%); }
        .sidebar-brand {
            display: flex; align-items: center; gap: 10px;
            padding: 20px 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none; flex-shrink: 0;
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px; background: var(--primary);
            border-radius: 10px; display: flex; align-items: center;
            justify-content: center; font-size: 1rem; color: #fff; flex-shrink: 0;
        }
        .sidebar-brand .brand-text { color: #fff; font-size: 1.05rem; font-weight: 700; }
        .sidebar-brand .brand-sub  { color: rgba(255,255,255,0.4); font-size: 0.68rem; display: block; }
        .sidebar-section {
            padding: 16px 14px 5px; font-size: 0.65rem; font-weight: 600;
            letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,0.3);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.6); padding: 10px 14px; border-radius: 9px;
            margin: 2px 8px; font-size: 0.85rem; font-weight: 500;
            display: flex; align-items: center; gap: 10px; transition: all 0.18s;
            text-decoration: none;
        }
        .sidebar .nav-link i { font-size: 1rem; flex-shrink: 0; }
        .sidebar .nav-link:hover  { background: var(--sidebar-hover); color: #fff; }
        .sidebar .nav-link.active { background: var(--primary); color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.4); }
        .sidebar-footer { margin-top: auto; padding: 12px; border-top: 1px solid rgba(255,255,255,0.08); flex-shrink: 0; }
        .btn-dark-toggle {
            background: transparent; border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.6); border-radius: 8px; font-size: 0.78rem;
            padding: 7px 12px; width: 100%; text-align: left;
            display: flex; align-items: center; gap: 8px; transition: all 0.18s;
            cursor: pointer; margin-bottom: 8px;
        }
        .btn-dark-toggle:hover { background: rgba(99,102,241,0.2); color: #c7d2fe; }
        .btn-logout {
            background: transparent; border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.6); border-radius: 8px; font-size: 0.78rem;
            padding: 7px 12px; width: 100%; text-align: left;
            display: flex; align-items: center; gap: 8px; transition: all 0.18s;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.15); color: #fca5a5; border-color: rgba(239,68,68,0.3); }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 199;
        }
        .sidebar-overlay.show { display: block; }

        /* ── Main ── */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; transition: margin-left 0.25s; }

        /* ── Topbar ── */
        .topbar {
            background: var(--card-bg); padding: 12px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 99; transition: background 0.2s;
            gap: 12px;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .topbar-title { font-size: 1rem; font-weight: 700; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .btn-menu {
            display: none; background: none; border: 1px solid var(--border);
            border-radius: 8px; padding: 6px 10px; cursor: pointer; color: var(--text-main);
            flex-shrink: 0;
        }
        .user-badge {
            display: flex; align-items: center; gap: 8px;
            background: var(--body-bg); border: 1px solid var(--border);
            border-radius: 50px; padding: 4px 12px 4px 5px;
            font-size: 0.8rem; font-weight: 500; color: var(--text-main);
            white-space: nowrap; flex-shrink: 0;
        }
        .user-badge img { width: 26px; height: 26px; border-radius: 50%; object-fit: cover; }
        .user-badge .avatar-placeholder {
            width: 26px; height: 26px; border-radius: 50%; background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.72rem; font-weight: 700; flex-shrink: 0;
        }
        .user-name { display: inline; }

        /* ── Cards ── */
        .card { border: 1px solid var(--border); box-shadow: 0 1px 6px rgba(0,0,0,0.05); border-radius: 14px; background: var(--card-bg); transition: background 0.2s; }
        .card-header-clean { padding: 16px 18px 0; font-weight: 700; font-size: 0.9rem; color: var(--text-main); background: transparent; border: none; }

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

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .btn-menu { display: flex; }
            .user-name { display: none; }
        }
        @media (max-width: 767px) {
            .topbar { padding: 10px 14px; }
            .main-content > .p-4 { padding: 14px !important; }
            .stat-card { padding: 16px; }
            .stat-card .stat-value { font-size: 1.4rem; }
            .table thead th, .table tbody td { padding: 9px 10px; font-size: 0.78rem; }
        }
        @media (max-width: 575px) {
            .modal-dialog { margin: 10px; }
        }
    </style>
</head>
<body>

<!-- Sidebar overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-film"></i></div>
        <div>
            <div class="brand-text">MovieList</div>
            <span class="brand-sub">Management System</span>
        </div>
    </a>
    <div class="sidebar-section">Main Menu</div>
    <nav>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('movies.index') }}" class="nav-link {{ request()->routeIs('movies.*') ? 'active' : '' }}">
            <i class="bi bi-camera-video"></i> My Movies
        </a>
    </nav>
    <div class="sidebar-section">Administration</div>
    <nav>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
        <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> My Profile
        </a>
    </nav>
    <div class="sidebar-footer">
        <button class="btn-dark-toggle" onclick="toggleDark()" id="darkBtn">
            <i class="bi bi-moon" id="darkIcon"></i>
            <span id="darkLabel">Dark Mode</span>
        </button>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-logout"><i class="bi bi-box-arrow-left"></i> Sign Out</button>
        </form>
    </div>
</div>

<div class="main-content" id="mainContent">
    <div class="topbar">
        <div class="topbar-left">
            <button class="btn-menu" onclick="openSidebar()"><i class="bi bi-list fs-5"></i></button>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
        </div>
        <div class="user-badge">
            @if(Auth::user()->profile_picture)
                <img src="{{ Storage::url(Auth::user()->profile_picture) }}" alt="avatar">
            @else
                <div class="avatar-placeholder">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
            @endif
            <span class="user-name">{{ Auth::user()->name }}</span>
        </div>
    </div>
    <div class="p-4">@yield('content')</div>
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

    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    function toggleDark() {
        const dark = document.documentElement.getAttribute('data-theme') !== 'dark';
        document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
        localStorage.setItem('theme', dark ? 'dark' : 'light');
        document.getElementById('darkIcon').className  = dark ? 'bi bi-sun' : 'bi bi-moon';
        document.getElementById('darkLabel').textContent = dark ? 'Light Mode' : 'Dark Mode';
    }
    (function(){
        const t = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', t);
        if (t === 'dark') {
            const i = document.getElementById('darkIcon');
            const l = document.getElementById('darkLabel');
            if (i) i.className = 'bi bi-sun';
            if (l) l.textContent = 'Light Mode';
        }
    })();
</script>
@yield('scripts')
</body>
</html>
