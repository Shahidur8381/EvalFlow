<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EvalFlow') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-container {
            width: 100%;
            max-width: 420px;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .auth-logo-icon {
            width: 56px; height: 56px;
            background: #4f46e5;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 14px;
            box-shadow: 0 8px 32px rgba(79,70,229,.4);
        }
        .auth-logo-name { color: #fff; font-size: 1.5rem; font-weight: 800; letter-spacing: -0.03em; }
        .auth-logo-sub  { color: #94a3b8; font-size: .8rem; margin-top: 4px; }
        .auth-card {
            background: rgba(255,255,255,.05);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 16px;
            padding: 36px 32px;
        }
        .auth-card label {
            display: block;
            color: #cbd5e1;
            font-size: .82rem;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .auth-card input[type=text],
        .auth-card input[type=email],
        .auth-card input[type=password] {
            width: 100%;
            padding: 10px 14px;
            background: rgba(255,255,255,.08);
            border: 1.5px solid rgba(255,255,255,.15);
            border-radius: 8px;
            color: #fff;
            font-size: .9rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .auth-card input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.2); }
        .auth-card input::placeholder { color: #475569; }
        .auth-card .checkbox-label { display: flex; align-items: center; gap: 8px; color: #94a3b8; font-size: .85rem; cursor: pointer; }
        .auth-card input[type=checkbox] { width: 16px; height: 16px; accent-color: #4f46e5; }
        .btn-auth {
            width: 100%;
            padding: 11px;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background .15s;
        }
        .btn-auth:hover { background: #4338ca; }
        .auth-link { color: #818cf8; text-decoration: none; font-size: .85rem; }
        .auth-link:hover { color: #a5b4fc; }
        .error-msg { color: #f87171; font-size: .78rem; margin-top: 4px; }
        .status-msg { background: rgba(16,185,129,.15); border: 1px solid rgba(16,185,129,.3); color: #6ee7b7; border-radius: 8px; padding: 10px 14px; font-size: .85rem; margin-bottom: 16px; }
        .form-row { margin-bottom: 18px; }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-logo">
            <div class="auth-logo-icon">📋</div>
            <div class="auth-logo-name">EvalFlow</div>
            <div class="auth-logo-sub">Examination Evaluation System</div>
        </div>
        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
