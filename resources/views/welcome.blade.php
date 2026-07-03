<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EvalFlow — A modern, role-based examination and evaluation management system. Streamline exam creation, student submissions, and evaluator grading.">
    <title>EvalFlow — Modern Examination Evaluation System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand:       #4f46e5;
            --brand-light: #818cf8;
            --brand-glow:  rgba(79,70,229,0.35);
            --dark:        #0f172a;
            --dark-2:      #1e293b;
            --dark-3:      #334155;
            --text:        #f8fafc;
            --text-muted:  #94a3b8;
            --border:      rgba(255,255,255,0.08);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: var(--text);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ── Background ──────────────────────── */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(79,70,229,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79,70,229,.06) 1px, transparent 1px);
            background-size: 60px 60px;
            z-index: 0;
            pointer-events: none;
        }
        .bg-glow-1 {
            position: fixed;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(79,70,229,0.18) 0%, transparent 70%);
            top: -150px; left: -100px;
            z-index: 0;
            pointer-events: none;
            animation: float1 8s ease-in-out infinite;
        }
        .bg-glow-2 {
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(139,92,246,0.14) 0%, transparent 70%);
            bottom: -100px; right: -100px;
            z-index: 0;
            pointer-events: none;
            animation: float2 10s ease-in-out infinite;
        }
        @keyframes float1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(30px,30px)} }
        @keyframes float2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-20px,20px)} }

        /* ── Navbar ──────────────────────────── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 0 40px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(15,23,42,0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            transition: all .3s;
        }
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .navbar-logo-icon {
            width: 36px; height: 36px;
            background: var(--brand);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            box-shadow: 0 0 20px var(--brand-glow);
        }
        .navbar-logo-name {
            color: #fff;
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: -0.03em;
        }
        .navbar-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            padding: 7px 16px;
            border-radius: 8px;
            transition: all .15s;
        }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,.06); }
        .btn-nav {
            padding: 8px 20px;
            background: var(--brand);
            color: #fff;
            text-decoration: none;
            font-size: .9rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all .15s;
            box-shadow: 0 0 20px var(--brand-glow);
        }
        .btn-nav:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 28px var(--brand-glow); }

        /* ── Hero ────────────────────────────── */
        .hero {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 40px 80px;
            text-align: center;
        }
        .hero-inner { max-width: 860px; }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(79,70,229,.15);
            border: 1px solid rgba(79,70,229,.3);
            color: var(--brand-light);
            padding: 6px 16px;
            border-radius: 999px;
            font-size: .82rem;
            font-weight: 600;
            margin-bottom: 28px;
            animation: fadeUp .6s ease forwards;
        }
        .hero-badge-dot { width: 7px; height: 7px; background: var(--brand-light); border-radius: 50%; animation: pulse 2s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        .hero-title {
            font-size: clamp(2.8rem, 6vw, 5rem);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -0.04em;
            margin-bottom: 24px;
            animation: fadeUp .7s ease .1s both;
        }
        .hero-title .gradient-text {
            background: linear-gradient(135deg, #818cf8 0%, #4f46e5 40%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 1.15rem;
            color: var(--text-muted);
            max-width: 580px;
            margin: 0 auto 40px;
            line-height: 1.7;
            animation: fadeUp .7s ease .2s both;
        }
        .hero-cta {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeUp .7s ease .3s both;
        }
        .btn-primary-lg {
            padding: 14px 32px;
            background: var(--brand);
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 10px;
            transition: all .2s;
            box-shadow: 0 0 30px var(--brand-glow);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary-lg:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 8px 40px var(--brand-glow); }
        .btn-ghost-lg {
            padding: 14px 32px;
            background: rgba(255,255,255,.06);
            color: #e2e8f0;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            border: 1px solid var(--border);
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-ghost-lg:hover { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.2); transform: translateY(-2px); }

        @keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }

        /* ── Hero Preview Window ─────────────── */
        .hero-preview {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 70px auto 0;
            padding: 0 40px;
            animation: fadeUp .8s ease .4s both;
        }
        .browser-frame {
            background: var(--dark-2);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 40px 120px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.05);
        }
        .browser-topbar {
            background: #1e293b;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border);
        }
        .browser-dots { display: flex; gap: 6px; }
        .browser-dot { width: 12px; height: 12px; border-radius: 50%; }
        .browser-dot.red { background: #ef4444; }
        .browser-dot.yellow { background: #f59e0b; }
        .browser-dot.green { background: #10b981; }
        .browser-url {
            flex: 1;
            background: rgba(255,255,255,.06);
            border-radius: 6px;
            padding: 5px 14px;
            font-size: .78rem;
            color: var(--text-muted);
            font-family: 'Inter', monospace;
            margin-left: 8px;
        }
        .browser-content {
            display: grid;
            grid-template-columns: 220px 1fr;
            min-height: 380px;
        }
        .mock-sidebar {
            background: #0f172a;
            padding: 20px 14px;
            border-right: 1px solid var(--border);
        }
        .mock-logo-row { display: flex; gap: 10px; align-items: center; margin-bottom: 24px; padding: 0 8px; }
        .mock-icon { width: 30px; height: 30px; background: var(--brand); border-radius: 7px; }
        .mock-name { height: 10px; background: #334155; border-radius: 4px; width: 80px; }
        .mock-nav-item { height: 34px; border-radius: 7px; margin-bottom: 4px; display: flex; align-items: center; gap: 10px; padding: 0 10px; }
        .mock-nav-item.active { background: var(--brand); }
        .mock-nav-dot { width: 14px; height: 14px; border-radius: 4px; background: rgba(255,255,255,.2); }
        .mock-nav-label { height: 8px; border-radius: 3px; background: rgba(255,255,255,.2); flex: 1; }
        .mock-nav-item:not(.active) .mock-nav-dot { background: #334155; }
        .mock-nav-item:not(.active) .mock-nav-label { background: #334155; }
        .mock-main { padding: 22px; }
        .mock-header { height: 22px; background: #334155; border-radius: 5px; width: 200px; margin-bottom: 6px; }
        .mock-subheader { height: 10px; background: #1e293b; border-radius: 4px; width: 280px; margin-bottom: 22px; }
        .mock-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 20px; }
        .mock-stat { background: var(--dark-2); border: 1px solid var(--border); border-radius: 10px; padding: 14px; }
        .mock-stat-num { height: 20px; background: #334155; border-radius: 4px; width: 40px; margin-bottom: 6px; }
        .mock-stat-lbl { height: 8px; background: #1e293b; border-radius: 3px; width: 60px; }
        .mock-table { background: var(--dark-2); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
        .mock-table-header { background: #1e293b; height: 36px; display: flex; align-items: center; padding: 0 16px; gap: 8px; }
        .mock-table-hcell { height: 7px; background: #334155; border-radius: 3px; }
        .mock-table-row { height: 44px; border-bottom: 1px solid var(--border); display: flex; align-items: center; padding: 0 16px; gap: 12px; }
        .mock-table-row:last-child { border-bottom: none; }
        .mock-cell-lg { height: 9px; border-radius: 4px; background: #1e293b; }
        .mock-badge { height: 18px; border-radius: 999px; }
        .mock-badge.green { background: rgba(16,185,129,.25); width: 55px; }
        .mock-badge.yellow { background: rgba(245,158,11,.25); width: 65px; }
        .mock-badge.blue { background: rgba(79,70,229,.25); width: 45px; }

        /* ── Features ────────────────────────── */
        section { position: relative; z-index: 1; }

        .features-section {
            padding: 100px 40px;
            max-width: 1160px;
            margin: 0 auto;
        }
        .section-tag {
            display: inline-block;
            background: rgba(79,70,229,.12);
            color: var(--brand-light);
            border: 1px solid rgba(79,70,229,.25);
            padding: 5px 14px;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        .section-title {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
            line-height: 1.15;
        }
        .section-sub {
            font-size: 1rem;
            color: var(--text-muted);
            max-width: 520px;
            margin-bottom: 56px;
            line-height: 1.7;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .feature-card {
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            transition: all .25s ease;
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79,70,229,.06) 0%, transparent 60%);
            opacity: 0;
            transition: opacity .25s;
        }
        .feature-card:hover { border-color: rgba(79,70,229,.4); transform: translateY(-4px); box-shadow: 0 20px 60px rgba(0,0,0,.3); }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon {
            width: 48px; height: 48px;
            background: rgba(79,70,229,.15);
            border: 1px solid rgba(79,70,229,.25);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            margin-bottom: 18px;
        }
        .feature-title { font-size: 1rem; font-weight: 700; margin-bottom: 8px; }
        .feature-desc { font-size: .875rem; color: var(--text-muted); line-height: 1.65; }

        /* ── Roles Section ───────────────────── */
        .roles-section {
            padding: 80px 40px 100px;
            max-width: 1160px;
            margin: 0 auto;
        }
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 24px;
            margin-top: 50px;
        }
        .role-card {
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px 28px;
            text-align: center;
            transition: all .25s;
            position: relative;
            overflow: hidden;
        }
        .role-card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 0 0 20px 20px;
            opacity: 0;
            transition: opacity .25s;
        }
        .role-card.admin::after { background: linear-gradient(90deg, #4f46e5, #818cf8); }
        .role-card.student::after { background: linear-gradient(90deg, #10b981, #6ee7b7); }
        .role-card.evaluator::after { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
        .role-card:hover { transform: translateY(-6px); box-shadow: 0 24px 60px rgba(0,0,0,.4); }
        .role-card:hover::after { opacity: 1; }
        .role-emoji { font-size: 3rem; margin-bottom: 18px; display: block; }
        .role-name { font-size: 1.2rem; font-weight: 800; margin-bottom: 10px; }
        .role-desc { font-size: .875rem; color: var(--text-muted); line-height: 1.65; margin-bottom: 20px; }
        .role-abilities { list-style: none; text-align: left; }
        .role-abilities li { font-size: .83rem; color: var(--text-muted); padding: 6px 0; border-bottom: 1px solid var(--border); display: flex; gap: 8px; }
        .role-abilities li:last-child { border-bottom: none; }
        .role-abilities li::before { content: '→'; color: var(--brand-light); font-weight: 700; }

        /* ── How it Works ────────────────────── */
        .how-section {
            padding: 80px 40px 100px;
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }
        .steps { display: flex; flex-direction: column; gap: 0; margin-top: 50px; text-align: left; }
        .step { display: grid; grid-template-columns: 80px 1fr; gap: 24px; align-items: start; padding: 30px 0; border-bottom: 1px solid var(--border); position: relative; }
        .step:last-child { border-bottom: none; }
        .step-num {
            width: 56px; height: 56px;
            background: rgba(79,70,229,.15);
            border: 1px solid rgba(79,70,229,.3);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            font-weight: 900;
            color: var(--brand-light);
        }
        .step-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 6px; }
        .step-desc { font-size: .875rem; color: var(--text-muted); line-height: 1.7; }

        /* ── CTA ─────────────────────────────── */
        .cta-section {
            padding: 80px 40px 120px;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .cta-box {
            max-width: 700px;
            margin: 0 auto;
            background: rgba(79,70,229,.08);
            border: 1px solid rgba(79,70,229,.2);
            border-radius: 24px;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
        }
        .cta-box::before {
            content: '';
            position: absolute;
            top: -80px; left: 50%; transform: translateX(-50%);
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(79,70,229,.25) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-title { font-size: 2.2rem; font-weight: 900; letter-spacing: -0.03em; margin-bottom: 14px; }
        .cta-sub { color: var(--text-muted); font-size: 1rem; margin-bottom: 32px; }

        /* ── Footer ──────────────────────────── */
        footer {
            position: relative;
            z-index: 1;
            border-top: 1px solid var(--border);
            padding: 32px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .footer-logo-icon { width: 28px; height: 28px; background: var(--brand); border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 14px; }
        .footer-logo-text { color: #fff; font-weight: 700; font-size: .95rem; }
        .footer-text { color: var(--text-muted); font-size: .82rem; }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 900px) {
            .navbar { padding: 0 20px; }
            .hero { padding: 100px 20px 60px; }
            .hero-preview { padding: 0 20px; }
            .features-grid, .roles-grid { grid-template-columns: 1fr; }
            .browser-content { grid-template-columns: 1fr; }
            .mock-sidebar { display: none; }
            .features-section, .roles-section, .how-section { padding-left: 20px; padding-right: 20px; }
            footer { flex-direction: column; gap: 12px; text-align: center; }
        }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-glow-1"></div>
<div class="bg-glow-2"></div>

<!-- Navbar -->
<nav class="navbar">
    <a href="/" class="navbar-logo">
        <div class="navbar-logo-icon">📋</div>
        <span class="navbar-logo-name">EvalFlow</span>
    </a>
    <div class="navbar-links">
        <a href="#features" class="nav-link">Features</a>
        <a href="#roles" class="nav-link">Roles</a>
        <a href="#how-it-works" class="nav-link">How it Works</a>
        @auth
            <a href="{{ url('/dashboard') }}" class="btn-nav">Go to Dashboard →</a>
        @else
            <a href="{{ route('login') }}" class="nav-link">Sign In</a>
            <a href="{{ route('register') }}" class="btn-nav">Get Started →</a>
        @endauth
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-badge">
            <div class="hero-badge-dot"></div>
            Now in Active Development — Sprint 3 Complete
        </div>

        <h1 class="hero-title">
            The Modern<br>
            <span class="gradient-text">Examination Evaluation</span><br>
            Platform
        </h1>

        <p class="hero-sub">
            EvalFlow streamlines the entire exam lifecycle — from creating question papers and collecting student submissions to assigning evaluators and delivering results. All in one place.
        </p>

        <div class="hero-cta">
            @auth
            <a href="{{ url('/dashboard') }}" class="btn-primary-lg">Go to Dashboard →</a>
            @else
            <a href="{{ route('login') }}" class="btn-primary-lg">🚀 Sign In to Start</a>
            <a href="#features" class="btn-ghost-lg">Explore Features ↓</a>
            @endauth
        </div>
    </div>
</section>

<!-- App Preview -->
<div class="hero-preview">
    <div class="browser-frame">
        <div class="browser-topbar">
            <div class="browser-dots">
                <div class="browser-dot red"></div>
                <div class="browser-dot yellow"></div>
                <div class="browser-dot green"></div>
            </div>
            <div class="browser-url">http://127.0.0.1:8000/admin/dashboard</div>
        </div>
        <div class="browser-content">
            <div class="mock-sidebar">
                <div class="mock-logo-row">
                    <div class="mock-icon"></div>
                    <div class="mock-name"></div>
                </div>
                <div class="mock-nav-item active"><div class="mock-nav-dot"></div><div class="mock-nav-label"></div></div>
                <div class="mock-nav-item"><div class="mock-nav-dot"></div><div class="mock-nav-label" style="width:60%"></div></div>
                <div class="mock-nav-item"><div class="mock-nav-dot"></div><div class="mock-nav-label" style="width:50%"></div></div>
                <div class="mock-nav-item"><div class="mock-nav-dot"></div><div class="mock-nav-label" style="width:70%"></div></div>
            </div>
            <div class="mock-main">
                <div class="mock-header"></div>
                <div class="mock-subheader"></div>
                <div class="mock-stats">
                    <div class="mock-stat"><div class="mock-stat-num"></div><div class="mock-stat-lbl"></div></div>
                    <div class="mock-stat"><div class="mock-stat-num" style="background:#4f46e5;opacity:.6"></div><div class="mock-stat-lbl"></div></div>
                    <div class="mock-stat"><div class="mock-stat-num" style="background:#f59e0b;opacity:.4"></div><div class="mock-stat-lbl"></div></div>
                    <div class="mock-stat"><div class="mock-stat-num" style="background:#10b981;opacity:.4"></div><div class="mock-stat-lbl"></div></div>
                </div>
                <div class="mock-table">
                    <div class="mock-table-header">
                        <div class="mock-table-hcell" style="width:100px"></div>
                        <div class="mock-table-hcell" style="width:60px;margin-left:20px"></div>
                        <div class="mock-table-hcell" style="width:40px;margin-left:20px"></div>
                        <div class="mock-table-hcell" style="width:70px;margin-left:20px"></div>
                    </div>
                    <div class="mock-table-row">
                        <div class="mock-cell-lg" style="width:130px"></div>
                        <div class="mock-badge blue" style="margin-left:12px"></div>
                        <div class="mock-badge green" style="margin-left:12px"></div>
                        <div class="mock-cell-lg" style="width:70px;margin-left:12px"></div>
                    </div>
                    <div class="mock-table-row">
                        <div class="mock-cell-lg" style="width:110px"></div>
                        <div class="mock-badge blue" style="margin-left:12px"></div>
                        <div class="mock-badge yellow" style="margin-left:12px"></div>
                        <div class="mock-cell-lg" style="width:80px;margin-left:12px"></div>
                    </div>
                    <div class="mock-table-row">
                        <div class="mock-cell-lg" style="width:120px"></div>
                        <div class="mock-badge blue" style="margin-left:12px"></div>
                        <div class="mock-badge green" style="margin-left:12px"></div>
                        <div class="mock-cell-lg" style="width:60px;margin-left:12px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features -->
<section class="features-section" id="features">
    <div class="section-tag">Features</div>
    <h2 class="section-title">Everything you need to<br>run a paperless exam</h2>
    <p class="section-sub">From question creation to final results — every step of the evaluation process is handled efficiently and transparently.</p>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">📝</div>
            <div class="feature-title">Structured Question Papers</div>
            <div class="feature-desc">Admins build exam papers question by question. Each question has a specific mark. Total marks are auto-calculated — no manual math.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">⏱️</div>
            <div class="feature-title">Strict Time Windows</div>
            <div class="feature-desc">Exams have a defined start and end time. Students can only submit within the active window — enforced at the server level, no workarounds.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📄</div>
            <div class="feature-title">PDF Answer Scripts</div>
            <div class="feature-desc">Students upload their handwritten or typed answer sheets as a single PDF (up to 20MB). One submission per student per exam, period.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">👁️</div>
            <div class="feature-title">Inline PDF Viewer</div>
            <div class="feature-desc">Evaluators view student scripts directly in the browser — no downloads needed. The grading form sits right beside the document for a seamless workflow.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <div class="feature-title">Role-Based Access Control</div>
            <div class="feature-desc">Three distinct roles: Admin, Student, Evaluator. Each user only sees what they are authorized to see — enforced by middleware at every route.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">👨‍💼</div>
            <div class="feature-title">Evaluator Assignment</div>
            <div class="feature-desc">Admin assigns exactly one evaluator per exam. Only that evaluator can see and grade that exam's submissions. Other evaluators see nothing.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <div class="feature-title">Live Result Tracking</div>
            <div class="feature-desc">Students see their submission status in real time: Upcoming → Active → Submitted → Awaiting Evaluation → Evaluated (mark: xx/xx).</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🛡️</div>
            <div class="feature-title">Password Strength Enforcement</div>
            <div class="feature-desc">Custom middleware rejects weak passwords at registration. Requires uppercase, lowercase, numbers, symbols, and data breach checking.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🇧🇩</div>
            <div class="feature-title">Bangladesh Timezone</div>
            <div class="feature-desc">All exam times are computed and displayed in Asia/Dhaka (GMT+6) ensuring students and evaluators always see the correct local time.</div>
        </div>
    </div>
</section>

<!-- Roles -->
<section class="roles-section" id="roles">
    <div class="section-tag">Three Panels</div>
    <h2 class="section-title">A role for every<br>person in the process</h2>
    <p class="section-sub">EvalFlow is designed around three distinct user roles, each with a focused, purpose-built dashboard.</p>

    <div class="roles-grid">
        <div class="role-card admin">
            <span class="role-emoji">🏛️</span>
            <div class="role-name">Administrator</div>
            <div class="role-desc">The control tower. Admins set up the entire exam lifecycle from start to finish.</div>
            <ul class="role-abilities">
                <li>Create and manage courses</li>
                <li>Create exams with time windows</li>
                <li>Add/remove questions with marks</li>
                <li>Delete exams</li>
                <li>Assign evaluators to exams</li>
                <li>Monitor all submissions</li>
            </ul>
        </div>

        <div class="role-card student">
            <span class="role-emoji">📚</span>
            <div class="role-name">Student</div>
            <div class="role-desc">Participants in the exam. Students view question papers and upload their answers.</div>
            <ul class="role-abilities">
                <li>View all scheduled exams</li>
                <li>Read the full question paper</li>
                <li>Upload PDF answer script (once)</li>
                <li>Track submission status</li>
                <li>View final evaluated mark</li>
                <li>See past exam history</li>
            </ul>
        </div>

        <div class="role-card evaluator">
            <span class="role-emoji">✅</span>
            <div class="role-name">Evaluator</div>
            <div class="role-desc">Graders who mark student scripts. Evaluators only see the papers assigned to them.</div>
            <ul class="role-abilities">
                <li>See only assigned exam scripts</li>
                <li>Open PDF inline in browser</li>
                <li>Reference question mark breakdown</li>
                <li>Assign marks to each script</li>
                <li>Track grading progress</li>
                <li>Edit grades if needed</li>
            </ul>
        </div>
    </div>
</section>

<!-- How it Works -->
<section class="how-section" id="how-it-works">
    <div class="section-tag">Workflow</div>
    <h2 class="section-title">How EvalFlow works</h2>
    <p class="section-sub" style="margin:0 auto 10px">The entire examination cycle from setup to results in five simple steps.</p>

    <div class="steps">
        <div class="step">
            <div class="step-num">1</div>
            <div>
                <div class="step-title">Admin Creates the Exam</div>
                <div class="step-desc">Admin logs in, creates a course, then creates an exam with a specific time window. Questions are added one by one — each with a body and a mark. The system calculates total marks automatically.</div>
            </div>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <div>
                <div class="step-title">Admin Assigns an Evaluator</div>
                <div class="step-desc">From the exam detail page, Admin selects which evaluator will grade this exam's submissions. Only one evaluator can be assigned at a time. The assignment can be changed at any point.</div>
            </div>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <div>
                <div class="step-title">Student Reads & Submits</div>
                <div class="step-desc">During the exam window, students see the full question paper on their dashboard. They upload a single PDF of their answer script. Late or duplicate submissions are blocked automatically.</div>
            </div>
        </div>
        <div class="step">
            <div class="step-num">4</div>
            <div>
                <div class="step-title">Evaluator Grades the Script</div>
                <div class="step-desc">The assigned evaluator sees a list of scripts to grade. Opening each one shows a split view: the student's PDF on the left and a grading form with the question mark reference on the right.</div>
            </div>
        </div>
        <div class="step">
            <div class="step-num">5</div>
            <div>
                <div class="step-title">Student Sees the Result</div>
                <div class="step-desc">Once marked, the student's dashboard immediately shows the evaluated mark (e.g., 78/100) under the relevant exam. The full exam history is always visible.</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-box">
        <h2 class="cta-title">Ready to get started?</h2>
        <p class="cta-sub">Sign in with your assigned credentials or register a new account to try EvalFlow today.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
            @auth
            <a href="{{ url('/dashboard') }}" class="btn-primary-lg">Open Dashboard →</a>
            @else
            <a href="{{ route('login') }}" class="btn-primary-lg">🚀 Sign In</a>
            <a href="{{ route('register') }}" class="btn-ghost-lg">Create Account</a>
            @endauth
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <a href="/" class="footer-logo">
        <div class="footer-logo-icon">📋</div>
        <span class="footer-logo-text">EvalFlow</span>
    </a>
    <div class="footer-text">© {{ date('Y') }} EvalFlow. Built with Laravel 11.</div>
    <div class="footer-text">Sprint 3 — Evaluation Engine</div>
</footer>

</body>
</html>
