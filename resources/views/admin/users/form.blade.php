@extends('layouts.admin')

@section('admin-content')

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 class="admin-page-title">{{ isset($user) ? 'Edit User' : 'Add User' }}</h1>
        <p class="admin-page-sub">{{ isset($user) ? 'Perbarui informasi user.' : 'Tambahkan admin baru ke panel ini.' }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="action-btn">← Back</a>
</div>

<div style="max-width:560px">
    <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}">
        @csrf
        @if(isset($user)) @method('PUT') @endif

        <div class="admin-section-card">
            <div class="admin-section-card-title">Informasi User</div>
            <div class="admin-form">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-input"
                           value="{{ old('name', $user->name ?? '') }}" required autofocus>
                    @error('name')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-input"
                           value="{{ old('email', $user->email ?? '') }}" required>
                    @error('email')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
                </div>

                @if(!isset($user))
                {{-- Password hanya saat create --}}
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-input"
                           placeholder="Min. 8 karakter" required autocomplete="new-password">
                    @error('password')<span style="color:rgba(255,80,80,.8);font-size:.78rem">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" class="form-input"
                           placeholder="Ulangi password" required autocomplete="new-password">
                </div>
                @else
                <div style="padding:.75rem 1rem;background:rgba(212,197,169,.06);border:1px solid rgba(212,197,169,.15);border-radius:2px;font-size:.82rem;color:rgba(240,237,232,.5)">
                    💡 Untuk mereset password user ini, gunakan tombol <strong style="color:var(--accent)">Reset PW</strong> di halaman daftar user.
                </div>
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-dark" style="padding:15px 48px">
            <span>{{ isset($user) ? 'Simpan Perubahan' : 'Tambah User' }}</span>
        </button>
    </form>
</div>

@endsection
