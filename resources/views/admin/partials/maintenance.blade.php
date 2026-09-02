<section class="admin-section-card maintenance-section" aria-labelledby="maintenance-title">
    <h2 class="admin-section-card-title" id="maintenance-title">System Maintenance</h2>
    <p class="maintenance-description">Jalankan pemeliharaan setelah pembaruan website. Simpan perubahan Settings terlebih dahulu dan jalankan satu tindakan pada satu waktu.</p>

    @if ($errors->maintenance->any())
    <div class="maintenance-errors" role="alert">
        <ul>
            @foreach ($errors->maintenance->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="maintenance-grid">
        <form method="POST" action="{{ route('admin.maintenance.migrate') }}" class="maintenance-form" data-maintenance-form>
            @csrf
            <h3>Migrate</h3>
            <p>Menjalankan migrasi database yang belum diterapkan. Tidak menjalankan fresh, refresh, rollback, atau seeder.</p>
            <p class="maintenance-warning">Migrasi dapat mengubah struktur dan data. Pastikan backup database tersedia sebelum melanjutkan.</p>
            <div class="form-group">
                <label for="migrate-password" class="form-label">Password akun CMS</label>
                <input type="password" id="migrate-password" name="password" class="form-input" autocomplete="current-password" required>
            </div>
            <label class="maintenance-confirm">
                <input type="checkbox" name="confirmed" value="1" required>
                <span>Saya sudah menyiapkan backup dan menyetujui perubahan database.</span>
            </label>
            <button type="submit" class="btn"><span>Jalankan Migrate</span></button>
        </form>

        <form method="POST" action="{{ route('admin.maintenance.optimize-clear') }}" class="maintenance-form" data-maintenance-form>
            @csrf
            <h3>Optimize Clear</h3>
            <p>Membersihkan cache optimasi Laravel, termasuk konfigurasi, route, view, dan cache aplikasi, agar perubahan terbaru dapat dimuat.</p>
            <p class="maintenance-warning">Cache akan dibangun kembali saat diperlukan. Akses pertama setelah pembersihan mungkin lebih lambat.</p>
            <div class="form-group">
                <label for="optimize-clear-password" class="form-label">Password akun CMS</label>
                <input type="password" id="optimize-clear-password" name="password" class="form-input" autocomplete="current-password" required>
            </div>
            <label class="maintenance-confirm">
                <input type="checkbox" name="confirmed" value="1" required>
                <span>Saya menyetujui pembersihan cache website.</span>
            </label>
            <button type="submit" class="btn"><span>Jalankan Optimize Clear</span></button>
        </form>
    </div>
    <p class="maintenance-status" role="status" data-maintenance-status hidden>Sedang menjalankan pemeliharaan. Tunggu hingga halaman selesai dimuat; jangan kirim ulang.</p>
</section>
