@extends('layouts.admin')

@section('admin-content')

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
    <div>
        <h1 class="admin-page-title">Manage Users</h1>
        <p class="admin-page-sub">Kelola akun admin yang bisa mengakses panel ini.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-dark"><span>+ Add User</span></a>
</div>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <span class="admin-table-title">All Users ({{ $users->count() }})</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Joined</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:.75rem">
                        <div style="width:34px;height:34px;border-radius:50%;background:rgba(212,197,169,.15);border:1px solid rgba(212,197,169,.2);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:.95rem;color:var(--accent);flex-shrink:0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:500;color:var(--white)">{{ $user->name }}</div>
                            @if($user->id === Auth::id())
                                <div style="font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:var(--accent)">You</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="color:rgba(240,237,232,.55)">{{ $user->email }}</td>
                <td style="color:var(--mid-gray)">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <span class="badge badge-active">Active</span>
                </td>
                <td>
                    <div style="display:flex;gap:.5rem;flex-wrap:wrap">
                        <a href="{{ route('admin.users.edit', $user) }}" class="action-btn">Edit</a>

                        {{-- Reset password via inline mini form --}}
                        <button onclick="toggleReset({{ $user->id }})" class="action-btn">Reset PW</button>

                        @if($user->id !== Auth::id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn danger">Hapus</button>
                        </form>
                        @endif
                    </div>

                    {{-- Inline reset password form --}}
                    <div id="reset-{{ $user->id }}" style="display:none;margin-top:.75rem;border:1px solid var(--border);padding:1rem;border-radius:2px;background:var(--bg-3)">
                        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                            @csrf
                            <div style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:flex-end">
                                <div style="flex:1;min-width:160px">
                                    <label class="form-label" style="display:block;margin-bottom:4px">Password Baru</label>
                                    <input type="password" name="password" class="form-input" placeholder="Min. 8 karakter" required style="margin:0">
                                </div>
                                <div style="flex:1;min-width:160px">
                                    <label class="form-label" style="display:block;margin-bottom:4px">Konfirmasi</label>
                                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required style="margin:0">
                                </div>
                                <button type="submit" class="action-btn" style="height:46px;padding:0 16px">Simpan</button>
                                <button type="button" onclick="toggleReset({{ $user->id }})" class="action-btn" style="height:46px">Batal</button>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
function toggleReset(id) {
    var el = document.getElementById('reset-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>

@endsection
