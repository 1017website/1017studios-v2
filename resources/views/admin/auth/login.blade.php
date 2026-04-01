<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — 1017Studios</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--black)">
<div class="noise-overlay"></div>

<div style="width:100%;max-width:420px;padding:clamp(1.5rem,5vw,2.5rem)">
    <div style="text-align:center;margin-bottom:3rem">
        <img src="{{ asset('images/logo.png') }}" alt="1017Studios" style="height:52px;filter:brightness(0) invert(1);margin-bottom:1.5rem">
        <p style="font-size:.8rem;letter-spacing:.15em;text-transform:uppercase;color:rgba(255,255,255,.3)">Admin Panel</p>
    </div>

    @if ($errors->any())
    <div style="border:1px solid rgba(255,80,80,.3);background:rgba(255,80,80,.05);padding:1rem 1.2rem;margin-bottom:1.5rem;font-size:.85rem;color:rgba(255,120,120,.9)">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}" style="display:flex;flex-direction:column;gap:1.2rem">
        @csrf
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="admin@1017studios.com">
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-input" required placeholder="••••••••">
        </div>
        <label style="display:flex;align-items:center;gap:.75rem;cursor:pointer">
            <input type="checkbox" name="remember" style="width:16px;height:16px;accent-color:white">
            <span style="font-size:.82rem;color:rgba(255,255,255,.5)">Remember me</span>
        </label>
        <button type="submit" class="btn btn-dark" style="width:100%;justify-content:center;margin-top:.5rem;padding:16px">
            <span>Login to Dashboard</span>
        </button>
    </form>

    <p style="text-align:center;margin-top:2rem">
        <a href="{{ route('home') }}" style="font-size:.78rem;color:rgba(255,255,255,.25);letter-spacing:.08em;border-bottom:1px solid rgba(255,255,255,.1)">← Back to Website</a>
    </p>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
