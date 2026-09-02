<!DOCTYPE html>
<html lang="id" class="cms-theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — {{ $pageTitle ?? 'Dashboard' }} | 1017Studios</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Instrument+Serif:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Critical cursor styles — inline so they always load */
        :root {
            --bg: #141414; --bg-2: #1c1c1c; --bg-3: #232323;
            --white: #f0ede8; --mid-gray: #888;
            --border: rgba(255,255,255,0.08);
            --accent: #d4c5a9;
            --ease: cubic-bezier(0.16,1,0.3,1);
        }
        body { background: var(--bg); color: var(--white); cursor: none; }
        .cursor {
            width: 10px; height: 10px; background: #f0ede8;
            border-radius: 50%; position: fixed; top: 0; left: 0;
            pointer-events: none; z-index: 999999;
            transform: translate(-50%,-50%);
            mix-blend-mode: difference; will-change: left, top;
            transition: width .18s, height .18s;
        }
        .cursor.hover { width: 18px; height: 18px; }
        .cursor-follower {
            width: 40px; height: 40px;
            border: 1.5px solid var(--cms-border);
            border-radius: 50%; position: fixed; top: 0; left: 0;
            pointer-events: none; z-index: 999998;
            transform: translate(-50%,-50%); will-change: left, top;
            transition: width .28s var(--ease), height .28s var(--ease), border-color .28s;
        }
        .cursor-follower.hover { width: 64px; height: 64px; border-color: var(--cms-border-strong); }
        @media (max-width: 768px) { body { cursor: auto; } .cursor, .cursor-follower { display: none !important; } }
    </style>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body>
<div class="cursor" id="cursor"></div>
<div class="cursor-follower" id="cursorFollower"></div>
<div class="noise-overlay"></div>
<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar-logo">
            <img src="{{ asset('images/logo.png') }}" alt="1017Studios">
        </div>

        <nav class="admin-nav">
            <div class="admin-nav-section">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.portfolio.index') }}" class="admin-nav-item {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="1"/><path d="M3 9h18M9 21V9"/></svg>
                Portfolio
            </a>
            <a href="{{ route('admin.clients.index') }}" class="admin-nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Our Client
            </a>
            <a href="{{ route('admin.services.index') }}" class="admin-nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M21 12h-2M19.07 19.07l-1.41-1.41M12 21v-2M4.93 19.07l1.41-1.41M3 12h2M4.93 4.93l1.41 1.41"/></svg>
                Services
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="admin-nav-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                Testimonials
            </a>

            <div class="admin-nav-section">Content</div>
            <a href="{{ route('admin.messages.index') }}" class="admin-nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                Messages
                @if(isset($unreadMessages) && $unreadMessages > 0)
                    <span style="margin-left:auto;background:rgba(255,255,255,0.15);padding:2px 8px;font-size:.65rem;letter-spacing:.08em">{{ $unreadMessages }}</span>
                @endif
            </a>
            <a href="{{ route('admin.settings') }}" class="admin-nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M21 12h-2M19.07 19.07l-1.41-1.41M12 21v-2M4.93 19.07l1.41-1.41M3 12h2M4.93 4.93l1.41 1.41"/></svg>
                Settings
            </a>

            {{-- ── NEW: SEO & Analytics ──────────────────────────────── --}}
            <div class="admin-nav-section">Growth</div>
            <a href="{{ route('admin.seo') }}" class="admin-nav-item {{ request()->routeIs('admin.seo') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    <path d="M11 8v3l2 2"/>
                </svg>
                SEO Settings
            </a>
            <a href="{{ route('admin.analytics') }}" class="admin-nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                Analytics
            </a>
            {{-- ────────────────────────────────────────────────────── --}}

            <div class="admin-nav-section">Account</div>
            <a href="{{ route('admin.users.index') }}" class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                Manage Users
            </a>
            <a href="{{ route('admin.profile') }}" class="admin-nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 10-16 0"/></svg>
                My Profile
            </a>
            <a href="{{ route('home') }}" target="_blank" class="admin-nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                View Website
            </a>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="admin-nav-item" style="width:100%;border:none;text-align:left;cursor:pointer;background:none;color:inherit;font-family:inherit;font-size:inherit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- MAIN -->
    <div class="admin-main">
        <div class="admin-topbar">
            <div style="font-size:.8rem;color:var(--mid-gray)">
                {{ $pageTitle ?? 'Dashboard' }}
            </div>
            <div style="display:flex;align-items:center;gap:1rem">
                <span style="font-size:.8rem;color:var(--cms-muted)">{{ Auth::user()->name ?? 'Admin' }}</span>
                <div style="width:34px;height:34px;border:1px solid var(--cms-border);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:.9rem">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="admin-content">
            @if (session('success'))
                <div class="flash-message" style="background:rgba(255,255,255,.06);border:1px solid var(--cms-border);padding:1rem 1.5rem;margin-bottom:1.5rem;font-size:.88rem;display:flex;justify-content:space-between;transition:opacity .4s">
                    <span>✓ &nbsp;{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="flash-message" style="background:rgba(255,80,80,.06);border:1px solid var(--cms-danger-border);padding:1rem 1.5rem;margin-bottom:1.5rem;font-size:.88rem;color:var(--cms-danger);transition:opacity .4s">
                    ✕ &nbsp;{{ session('error') }}\n                </div>
            @endif

            @yield('admin-content')
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
