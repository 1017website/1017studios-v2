<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — 1017Studios</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

    {{-- Load CSS via both relative and asset() for compatibility --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        /* ===== CRITICAL INLINE STYLES — login always looks correct ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:      #141414;
            --bg-2:    #1c1c1c;
            --bg-3:    #232323;
            --border:  rgba(255,255,255,0.08);
            --white:   #f0ede8;
            --mid-gray:#888;
            --accent:  #d4c5a9;
            --ease:    cubic-bezier(0.16, 1, 0.3, 1);
            --font-body: 'DM Sans', sans-serif;
            --font-display: 'Bebas Neue', sans-serif;
        }

        html, body {
            min-height: 100vh;
            background: var(--bg);
            color: var(--white);
            font-family: var(--font-body);
            cursor: none;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        /* Cursor */
        .cursor {
            width: 10px; height: 10px;
            background: var(--white);
            border-radius: 50%;
            position: fixed; top: 0; left: 0;
            pointer-events: none;
            z-index: 999999;
            transform: translate(-50%, -50%);
            mix-blend-mode: difference;
            will-change: left, top;
        }
        .cursor-follower {
            width: 38px; height: 38px;
            border: 1.5px solid rgba(240,237,232,0.28);
            border-radius: 50%;
            position: fixed; top: 0; left: 0;
            pointer-events: none;
            z-index: 999998;
            transform: translate(-50%, -50%);
            will-change: left, top;
            transition: width 0.28s var(--ease), height 0.28s var(--ease);
        }

        /* Noise overlay */
        .noise-overlay {
            position: fixed; inset: 0;
            pointer-events: none; z-index: 0;
            opacity: 0.022;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            background-size: 200px;
        }

        /* Login card */
        .login-card {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .login-logo {
            height: 50px;
            width: auto;
            filter: brightness(0) invert(0.92);
            margin-bottom: 1.5rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .login-label {
            font-size: 0.72rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(240,237,232,0.25);
        }

        /* Error box */
        .login-error {
            border: 1px solid rgba(220,80,80,0.25);
            background: rgba(220,80,80,0.06);
            padding: 0.9rem 1.2rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: rgba(230,110,110,0.9);
            border-radius: 2px;
        }

        /* Form */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }
        .form-label {
            font-size: 0.68rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--mid-gray);
        }
        .form-input {
            background: var(--bg-2);
            border: 1px solid var(--border);
            color: var(--white);
            padding: 13px 16px;
            font-family: var(--font-body);
            font-size: 0.92rem;
            outline: none;
            width: 100%;
            border-radius: 2px;
            transition: border-color 0.2s;
            /* Override browser autofill yellow */
            -webkit-box-shadow: 0 0 0 100px var(--bg-2) inset !important;
            -webkit-text-fill-color: var(--white) !important;
        }
        .form-input:focus { border-color: rgba(240,237,232,0.3); }
        .form-input::placeholder { color: rgba(240,237,232,0.18); }

        /* Remember me */
        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: none;
        }
        .remember-label input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: var(--white);
            cursor: none;
        }
        .remember-text {
            font-size: 0.82rem;
            color: rgba(240,237,232,0.4);
        }

        /* Submit button */
        .login-btn {
            width: 100%;
            padding: 15px;
            margin-top: 0.4rem;
            background: var(--white);
            color: var(--bg);
            border: 1px solid var(--white);
            font-family: var(--font-body);
            font-size: 0.78rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-weight: 600;
            cursor: none;
            transition: background 0.22s, color 0.22s;
            border-radius: 2px;
            position: relative;
            overflow: hidden;
        }
        .login-btn:hover {
            background: var(--bg-3);
            color: var(--white);
            border-color: rgba(240,237,232,0.25);
        }

        /* Back link */
        .login-back {
            text-align: center;
            margin-top: 2rem;
        }
        .login-back a {
            font-size: 0.76rem;
            color: rgba(240,237,232,0.22);
            letter-spacing: 0.06em;
            border-bottom: 1px solid rgba(240,237,232,0.1);
            padding-bottom: 1px;
            text-decoration: none;
            transition: color 0.2s;
        }
        .login-back a:hover { color: rgba(240,237,232,0.55); }

        /* Subtle background accent */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background: radial-gradient(ellipse 50% 60% at 50% 40%, rgba(212,197,169,0.04) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        @media (max-width: 480px) {
            body { cursor: auto; }
            .cursor, .cursor-follower { display: none; }
        }
    </style>
</head>
<body>

    {{-- Custom cursor --}}
    <div class="cursor" id="cursor"></div>
    <div class="cursor-follower" id="cursorFollower"></div>

    <div class="noise-overlay"></div>

    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo.png') }}" alt="1017Studios" class="login-logo">
            <p class="login-label">Admin Panel</p>
        </div>

        @if ($errors->any())
        <div class="login-error">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="login-form">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="admin@1017studios.com"
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                >
            </div>

            <label class="remember-label">
                <input type="checkbox" name="remember">
                <span class="remember-text">Ingat saya</span>
            </label>

            <button type="submit" class="login-btn">
                Login to Dashboard
            </button>
        </form>

        <div class="login-back">
            <a href="{{ route('home') }}">← Kembali ke Website</a>
        </div>
    </div>

    <script>
        // Self-contained cursor script for login page
        (function() {
            const cursor   = document.getElementById('cursor');
            const follower = document.getElementById('cursorFollower');
            if (!cursor || !follower) return;

            let mx = window.innerWidth / 2, my = window.innerHeight / 2;
            let fx = mx, fy = my;

            cursor.style.left   = mx + 'px';
            cursor.style.top    = my + 'px';
            follower.style.left = mx + 'px';
            follower.style.top  = my + 'px';

            document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });

            (function loop() {
                cursor.style.left = mx + 'px';
                cursor.style.top  = my + 'px';
                fx += (mx - fx) * 0.10;
                fy += (my - fy) * 0.10;
                follower.style.left = fx + 'px';
                follower.style.top  = fy + 'px';
                requestAnimationFrame(loop);
            })();

            document.addEventListener('mouseleave', () => { cursor.style.opacity = '0'; follower.style.opacity = '0'; });
            document.addEventListener('mouseenter', () => { cursor.style.opacity = '1'; follower.style.opacity = '1'; });
        })();
    </script>
</body>
</html>
