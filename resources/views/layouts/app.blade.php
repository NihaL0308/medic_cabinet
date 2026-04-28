<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'medic_cabinet')</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0b5ed7;
            --bg: #f4f7ff;
            --sidebar: #0a58ca;
            --white: #ffffff;
            --text: #1e293b;
            --muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
        }

        /* ───── SIDEBAR ───── */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, var(--primary), var(--sidebar));
            color: white;
            display: flex;
            flex-direction: column;
        }

        [dir="rtl"] .sidebar {
            left: auto;
            right: 0;
        }

        .sidebar-brand {
            padding: 20px;
            font-weight: 600;
            font-size: 18px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .brand-dot {
            width: 10px;
            height: 10px;
            background: #fff;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .sidebar-nav {
            padding: 15px;
            flex: 1;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            margin-bottom: 6px;
            transition: 0.2s;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        .sidebar-link.active {
            background: #fff;
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar .btn-light {
            border-radius: 10px;
            font-weight: 600;
        }

        /* ───── MAIN ───── */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        [dir="rtl"] .main-content {
            margin-left: 0;
            margin-right: 260px;
        }

        /* ───── TOPBAR ───── */
        .topbar {
            background: white;
            padding: 12px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-title {
            font-weight: 500;
            color: var(--muted);
        }

        .user-menu-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f1f5ff;
            padding: 6px 12px;
            border-radius: 50px;
            cursor: pointer;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .user-name {
            font-size: 14px;
        }

        /* ───── CONTENT ───── */
        .page-content {
            padding: 20px;
            flex: 1;
        }

        /* ───── BUTTONS ───── */
        .btn-primary {
            background: var(--primary) !important;
            border: none !important;
            border-radius: 10px !important;
        }

        .btn-primary:hover {
            background: var(--primary-dark) !important;
        }

        /* ───── CARDS ───── */
        .card {
            border: none !important;
            border-radius: 14px !important;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05) !important;
        }

        /* ───── TABLE ───── */
        .table thead th {
            background: #f1f5ff;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
        }

        .table tbody tr:hover {
            background: #f8fbff;
        }

        /* ───── ALERT ───── */
        .alert {
            border-radius: 10px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ───── FOOTER ───── */
        .page-footer {
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: var(--muted);
            background: white;
            border-top: 1px solid #e5e7eb;
        }

    </style>

    @stack('styles')
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="brand-dot"></span>
        medic_cabinet
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('appointments.index') }}"
           class="sidebar-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
            {{ __('app.appointments') }}
        </a>

        @if(auth()->user()->isAdmin() || auth()->user()->isMedecin())
        <a href="{{ route('services.index') }}"
           class="sidebar-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
             {{ __('app.services') }}
        </a>
        @endif

        @if(auth()->user()->isAdmin())
        <a href="{{ route('users.index') }}"
           class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            {{ __('app.users') }}
        </a>
        @endif

    </nav>

    <div class="p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-light w-100">
                {{ __('app.logout') }}
            </button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<div class="main-content">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-title">@yield('page-title')</div>

        <div class="user-menu-btn dropdown">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <span class="user-name">{{ auth()->user()->name }}</span>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="page-content">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')

    </div>

    <!-- FOOTER -->
    <div class="page-footer">
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
