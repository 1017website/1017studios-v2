@extends('layouts.admin')

@section('admin-content')

<h1 class="admin-page-title">My Profile</h1>
<p class="admin-page-sub">Kelola informasi akun dan keamanan kamu.</p>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;max-width:900px;align-items:start">

    {{-- ── Profile Info ───────────────────────────── --}}
    <div>
        <div class="admin-section-card">
            <div class="admin-section-card-title">Informasi Profil</div>

            @if(session('success'))
            <div class="flash-message" style="margin-bottom:1.2rem">✓ &nbsp;{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                <div class="admin-form">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
                    </div>
                    <div style="display:flex;gap:.75rem;flex-wrap:wrap">
                        <button type="submit" class="btn btn-dark" style="padding:12px 28px">
                            <span>Simpan Profil</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Info card --}}
        <div class="admin-section-card" style="margin-top:0">
            <div class="admin-section-card-title">Info Akun</div>
            <div style="display:flex;flex-direction:column;gap:.8rem">
                <div>
                    <div class="form-label" style="margin-bottom:3px">Member Sejak</div>
                    <div style="font-size:.9rem;color:rgba(240,237,232,.65)">{{ $user->created_at->format('d M Y') }}</div>
                </div>
                <div>
                    <div class="form-label" style="margin-bottom:3px">Terakhir Diperbarui</div>
                    <div style="font-size:.9rem;color:rgba(240,237,232,.65)">{{ $user->updated_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Change Password ────────────────────────── --}}
    <div class="admin-section-card">
        <div class="admin-section-card-title">Ganti Password</div>

        <form method="POST" action="{{ route('admin.profile.password') }}">
            @csrf
            <div class="admin-form">

                <div class="form-group">
                    <label class="form-label">Password Saat Ini *</label>
                    <div style="position:relative">
                        <input type="password" name="current_password" id="cur_pw" class="form-input"
                               placeholder="••••••••" required autocomplete="current-password"
                               style="padding-right:44px">
                        <button type="button" onclick="togglePw('cur_pw','cur_eye')"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--mid-gray);padding:0">
                            <svg id="cur_eye" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('current_password')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru *</label>
                    <div style="position:relative">
                        <input type="password" name="password" id="new_pw" class="form-input"
                               placeholder="Min. 8 karakter, huruf besar & angka" required autocomplete="new-password"
                               style="padding-right:44px" oninput="checkStrength(this.value)">
                        <button type="button" onclick="togglePw('new_pw','new_eye')"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--mid-gray);padding:0">
                            <svg id="new_eye" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror

                    {{-- Strength bar --}}
                    <div style="margin-top:.5rem">
                        <div style="height:3px;background:var(--border);border-radius:2px;overflow:hidden">
                            <div id="strengthBar" style="height:100%;width:0;transition:width .3s,background .3s;border-radius:2px"></div>
                        </div>
                        <div id="strengthText" style="font-size:.68rem;color:var(--mid-gray);margin-top:4px"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru *</label>
                    <div style="position:relative">
                        <input type="password" name="password_confirmation" id="conf_pw" class="form-input"
                               placeholder="Ulangi password baru" required autocomplete="new-password"
                               style="padding-right:44px">
                        <button type="button" onclick="togglePw('conf_pw','conf_eye')"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--mid-gray);padding:0">
                            <svg id="conf_eye" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div style="padding:.75rem 1rem;background:rgba(212,197,169,.05);border:1px solid rgba(212,197,169,.12);border-radius:2px;font-size:.8rem;color:rgba(240,237,232,.45)">
                    Password harus minimal 8 karakter, mengandung huruf besar dan angka.
                </div>

                <button type="submit" class="btn btn-dark" style="padding:12px 28px">
                    <span>Ganti Password</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle show/hide password
function togglePw(inputId, eyeId) {
    var input = document.getElementById(inputId);
    var eye   = document.getElementById(eyeId);
    if (input.type === 'password') {
        input.type = 'text';
        eye.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
        input.type = 'password';
        eye.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
}

// Password strength checker
function checkStrength(pw) {
    var bar  = document.getElementById('strengthBar');
    var text = document.getElementById('strengthText');
    if (!pw) { bar.style.width = '0'; text.textContent = ''; return; }

    var score = 0;
    if (pw.length >= 8)  score++;
    if (pw.length >= 12) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;

    var levels = [
        { pct:'20%', color:'rgba(220,80,80,.8)',   label:'Sangat Lemah' },
        { pct:'40%', color:'rgba(220,140,60,.8)',  label:'Lemah' },
        { pct:'60%', color:'rgba(220,200,60,.8)',  label:'Cukup' },
        { pct:'80%', color:'rgba(140,200,80,.8)',  label:'Kuat' },
        { pct:'100%',color:'rgba(80,200,120,.8)',  label:'Sangat Kuat' },
    ];
    var lvl = levels[Math.min(score - 1, 4)] || levels[0];
    bar.style.width      = lvl.pct;
    bar.style.background = lvl.color;
    text.textContent     = lvl.label;
    text.style.color     = lvl.color;
}
</script>

@endsection
