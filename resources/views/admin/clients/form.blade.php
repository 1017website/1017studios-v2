@extends('layouts.admin')

@section('admin-content')
<div class="client-admin-heading">
    <div>
        <h1 class="admin-page-title">{{ isset($client) ? 'Edit Klien' : 'Tambah Klien' }}</h1>
        <p class="admin-page-sub">Unggah logo asli klien atau mitra yang ingin ditampilkan di homepage.</p>
    </div>
    <a href="{{ route('admin.clients.index') }}" class="action-btn">← Our Client</a>
</div>

@if ($errors->any())
<div class="client-admin-errors" role="alert"><ul>
    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
</ul></div>
@endif

<form method="POST" action="{{ isset($client) ? route('admin.clients.update', $client) : route('admin.clients.store') }}" enctype="multipart/form-data" class="client-admin-form">
    @csrf
    @if (isset($client)) @method('PUT') @endif
    <div class="admin-section-card">
        <h2 class="admin-section-card-title">Detail Klien & Mitra</h2>
        <div class="admin-form">
            <div class="form-group">
                <label for="client-name" class="form-label">Nama Klien / Mitra *</label>
                <input id="client-name" type="text" name="name" class="form-input" value="{{ old('name', $client->name ?? '') }}" maxlength="120" required>
            </div>
            <div class="form-group">
                <label for="client-order" class="form-label">Urutan Tampil *</label>
                <input id="client-order" type="number" name="order" class="form-input" value="{{ old('order', $client->order ?? 0) }}" min="0" max="65535" required aria-describedby="client-order-help">
                <p id="client-order-help" class="client-admin-help">Angka lebih kecil tampil lebih dahulu. Baris kedua menampilkan urutan terbalik.</p>
            </div>
            <label class="client-admin-checkbox">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $client->is_active ?? true))>
                <span>Aktif — tampilkan logo di website</span>
            </label>
        </div>
    </div>

    <div class="admin-section-card">
        <h2 class="admin-section-card-title">Logo Klien</h2>
        <div class="client-admin-preview">
            @if (isset($client))
            <img id="client-logo-preview" src="{{ asset('storage/'.$client->logo) }}" alt="Preview logo {{ $client->name }}">
            @else
            <img id="client-logo-preview" alt="Preview logo klien" style="display:none">
            <span id="preview-placeholder">Preview logo</span>
            @endif
        </div>
        <label for="client-logo" class="form-label">{{ isset($client) ? 'Ganti Logo (opsional)' : 'Upload Logo *' }}</label>
        <input id="client-logo" type="file" name="logo" class="form-input" accept="image/png,image/jpeg,image/webp" data-preview="client-logo-preview" aria-describedby="client-logo-help" @required(!isset($client))>
        <p id="client-logo-help" class="client-admin-help">PNG, JPG, atau WebP. Maksimal 2 MB dan 4000 × 4000 px. Gunakan gambar transparan atau berlatar putih. {{ isset($client) ? 'Kosongkan untuk mempertahankan logo saat ini.' : '' }}</p>
    </div>

    <div class="client-admin-actions">
        <button type="submit" class="btn"><span>{{ isset($client) ? 'Simpan Perubahan' : 'Tambah Klien' }}</span></button>
        <a href="{{ route('admin.clients.index') }}" class="action-btn">Batal</a>
    </div>
</form>
@endsection
