<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EvalFlow') }} — {{ $title ?? 'Dashboard' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-accent: #1e293b;
            --sidebar-active: #4f46e5;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --brand: #4f46e5;
            --brand-light: #818cf8;
            --surface: #ffffff;
            --surface-alt: #f8fafc;
            --border: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--surface-alt); color: var(--text-primary); margin: 0; }

        /* ── Sidebar ─────────────────────────── */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform .25s ease;
        }
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 28px 24px 24px;
            border-bottom: 1px solid #1e293b;
        }
        .sidebar-logo-icon {
            width: 38px; height: 38px;
            background: var(--sidebar-active);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .sidebar-logo-text { color: #fff; font-weight: 700; font-size: 1.1rem; letter-spacing: -0.02em; }
        .sidebar-logo-sub { color: var(--sidebar-text); font-size: .7rem; text-transform: uppercase; letter-spacing: .05em; }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .sidebar-section-title { color: #475569; font-size: .65rem; text-transform: uppercase; letter-spacing: .08em; font-weight: 600; padding: 8px 12px; margin-top: 8px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            margin-bottom: 2px;
            transition: all .15s ease;
        }
        .sidebar-link:hover { background: var(--sidebar-accent); color: #cbd5e1; }
        .sidebar-link.active { background: var(--sidebar-active); color: var(--sidebar-text-active); }
        .sidebar-link .icon { font-size: 1rem; width: 20px; text-align: center; }
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid #1e293b;
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-avatar {
            width: 36px; height: 36px;
            background: var(--sidebar-active);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: .9rem;
        }
        .sidebar-user-name { color: #e2e8f0; font-size: .85rem; font-weight: 600; }
        .sidebar-user-role { color: var(--sidebar-text); font-size: .7rem; text-transform: capitalize; }
        .sidebar-logout { margin-left: auto; color: var(--sidebar-text); text-decoration: none; font-size: .8rem; padding: 4px 8px; border-radius: 6px; transition: all .15s; }
        .sidebar-logout:hover { background: #1e293b; color: #f87171; }

        /* ── Main Content ─────────────────────── */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .page-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 20px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page-header h1 { font-size: 1.3rem; font-weight: 700; color: var(--text-primary); margin: 0; }
        .page-header p { font-size: .85rem; color: var(--text-secondary); margin: 2px 0 0; }
        .page-body { padding: 28px 32px; flex: 1; }

        /* ── Cards ────────────────────────────── */
        .card {
            background: var(--surface);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header h3 { font-size: 1rem; font-weight: 700; margin: 0; color: var(--text-primary); }
        .card-body { padding: 24px; }

        /* ── Stats ────────────────────────────── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 20px; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
        .stat-card .stat-label { font-size: .8rem; color: var(--text-secondary); margin-top: 4px; font-weight: 500; }
        .stat-card .stat-icon { font-size: 1.5rem; margin-bottom: 12px; }

        /* ── Badges ───────────────────────────── */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 999px; font-size: .72rem; font-weight: 600; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-gray   { background: #f1f5f9; color: #475569; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }

        /* ── Forms ────────────────────────────── */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: .82rem; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 9px 13px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: .875rem;
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: var(--text-primary);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-control:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
        select.form-control { cursor: pointer; }
        .form-error { color: var(--danger); font-size: .78rem; margin-top: 4px; }

        /* ── Buttons ──────────────────────────── */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 8px; font-size: .85rem; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: all .15s ease; line-height: 1; }
        .btn-primary { background: var(--brand); color: #fff; }
        .btn-primary:hover { background: #4338ca; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-outline { background: transparent; color: var(--text-primary); border: 1.5px solid var(--border); }
        .btn-outline:hover { background: var(--surface-alt); }
        .btn-sm { padding: 6px 12px; font-size: .78rem; }
        .btn-xs { padding: 4px 10px; font-size: .72rem; }

        /* ── Tables ───────────────────────────── */
        .table-wrap { overflow-x: auto; }
        table.data-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        table.data-table th { padding: 12px 16px; text-align: left; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--text-secondary); border-bottom: 1.5px solid var(--border); background: var(--surface-alt); }
        table.data-table td { padding: 14px 16px; border-bottom: 1px solid var(--border); color: var(--text-primary); vertical-align: middle; }
        table.data-table tr:last-child td { border-bottom: none; }
        table.data-table tr:hover td { background: #f8fafc; }

        /* ── Alerts ───────────────────────────── */
        .alert { padding: 12px 18px; border-radius: 8px; font-size: .875rem; margin-bottom: 18px; display: flex; align-items: flex-start; gap: 10px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Misc helpers ─────────────────────── */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .gap-4 { gap: 16px; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
        .mb-4 { margin-bottom: 16px; }
        .text-sm { font-size: .85rem; }
        .text-muted { color: var(--text-secondary); }
        .font-bold { font-weight: 700; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .w-full { width: 100%; }
        @media (max-width: 900px) { .grid-2, .grid-3 { grid-template-columns: 1fr; } .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; } }
    </style>
</head>
<body>
    @auth
    @php $role = auth()->user()->role; @endphp
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">📋</div>
            <div>
                <div class="sidebar-logo-text">EvalFlow</div>
                <div class="sidebar-logo-sub">Evaluation System</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            @if($role === 'admin')
                <div class="sidebar-section-title">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="icon">🏠</span> Dashboard
                </a>
            @elseif($role === 'evaluator')
                <div class="sidebar-section-title">Evaluator</div>
                <a href="{{ route('evaluator.dashboard') }}" class="sidebar-link {{ request()->routeIs('evaluator.dashboard') ? 'active' : '' }}">
                    <span class="icon">✅</span> My Scripts
                </a>
            @elseif($role === 'student')
                <div class="sidebar-section-title">Student</div>
                <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <span class="icon">📚</span> My Exams
                </a>
            @endif

            <div class="sidebar-section-title" style="margin-top:20px;">Account</div>
            <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <span class="icon">👤</span> Profile
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">{{ $role }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="sidebar-logout" title="Logout">⏻</button>
            </form>
        </div>
    </aside>
    @endauth

    <div class="main-content">
        @isset($header)
        <div class="page-header">
            <div>
                <h1>{{ $header }}</h1>
                @isset($subheader)<p>{{ $subheader }}</p>@endisset
            </div>
            @isset($headerActions)
            <div class="flex gap-3">{{ $headerActions }}</div>
            @endisset
        </div>
        @endisset

        <div class="page-body">
            @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
            @endif

            {{ $slot }}
        </div>
    </div>
</body>
</html>
