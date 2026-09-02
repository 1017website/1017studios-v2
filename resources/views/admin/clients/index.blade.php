@extends('layouts.admin')

@section('admin-content')
<div class="client-admin-heading">
    <div>
        <h1 class="admin-page-title">Our Client</h1>
        <p class="admin-page-sub">Kelola logo klien dan mitra pada dua baris logo berjalan di atas Our Services.</p>
    </div>
    @if ($clientsReady)
    <a href="{{ route('admin.clients.create') }}" class="btn"><span>+ Tambah Klien</span></a>
    @endif
</div>

@if (!$clientsReady)
<div class="admin-section-card">
    <h2 class="admin-section-card-title">Aktivasi Our Client</h2>
    <p class="client-admin-note">Tabel klien belum tersedia. Siapkan backup database, lalu jalankan Migrate melalui Settings → System Maintenance. Setelah selesai, kembali ke menu ini untuk mengunggah logo.</p>
    <a href="{{ route('admin.settings') }}#maintenance-title" class="btn"><span>Buka System Maintenance</span></a>
</div>
@else
<div class="admin-table-wrap client-admin-table">
    <div class="admin-table-header">
        <span class="admin-table-title">Semua Klien ({{ $clients->total() }})</span>
    </div>
    <table>
        <thead><tr><th scope="col">Logo</th><th scope="col">Nama</th><th scope="col">Urutan</th><th scope="col">Status</th><th scope="col">Tindakan</th></tr></thead>
        <tbody>
            @forelse ($clients as $client)
            <tr>
                <td><img src="{{ asset('storage/'.$client->logo) }}" alt="Logo {{ $client->name }}" class="client-admin-thumbnail" width="120" height="68"></td>
                <td>{{ $client->name }}</td>
                <td>{{ $client->order }}</td>
                <td><span class="badge {{ $client->is_active ? 'badge-active' : 'badge-draft' }}">{{ $client->is_active ? 'Aktif' : 'Draft' }}</span></td>
                <td>
                    <div class="client-admin-actions">
                        <a href="{{ route('admin.clients.edit', $client) }}" class="action-btn" aria-label="Edit {{ $client->name }}">Edit</a>
                        <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('Hapus klien beserta file logonya? Tindakan ini tidak dapat dibatalkan. Gunakan status Draft jika hanya ingin menyembunyikan logo.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn danger" aria-label="Hapus {{ $client->name }}">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="client-admin-empty">Belum ada klien. Klik Tambah Klien untuk mengunggah logo pertama.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="client-admin-pagination">{{ $clients->links() }}</div>
<p class="client-admin-note">Urutan terkecil tampil lebih dahulu. Hanya klien berstatus Aktif yang ditampilkan. Logo tidak diambil otomatis dari Portfolio atau Testimonials.</p>
@endif
@endsection
