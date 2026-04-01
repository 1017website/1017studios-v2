# 1017Studios — Company Profile Website

**Stack:** Laravel 10 · PHP 8.2 · MySQL · Blade · Vanilla JS/CSS

---

## 📁 Struktur Proyek

```
1017studios/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php           ← Halaman publik
│   │   └── Admin/
│   │       ├── AuthController.php
│   │       ├── DashboardController.php
│   │       ├── PortfolioController.php
│   │       ├── ServiceController.php
│   │       ├── TestimonialController.php
│   │       ├── MessageController.php
│   │       └── SettingsController.php
│   └── Models/
│       ├── Portfolio.php
│       ├── Service.php
│       ├── Testimonial.php
│       ├── Message.php
│       └── Setting.php
├── database/
│   ├── migrations/                      ← Skema tabel
│   └── seeders/DatabaseSeeder.php       ← Data awal
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php                ← Layout publik
│   │   └── admin.blade.php              ← Layout admin
│   ├── home/                            ← Halaman publik
│   │   ├── index.blade.php
│   │   ├── services.blade.php
│   │   ├── portfolio.blade.php
│   │   ├── about.blade.php
│   │   └── contact.blade.php
│   └── admin/                           ← Panel admin
│       ├── auth/login.blade.php
│       ├── dashboard.blade.php
│       ├── portfolio/
│       ├── services/
│       ├── testimonials/
│       ├── messages/
│       └── settings.blade.php
├── public/
│   ├── css/app.css                      ← Semua styling
│   ├── js/app.js                        ← Interaktivitas
│   └── images/logo.png                  ← Logo 1017Studios
└── routes/web.php                       ← Semua route
```

---

## 🚀 Setup & Instalasi

### 1. Buat project Laravel baru

```bash
composer create-project laravel/laravel:^10.0 1017studios-app
cd 1017studios-app
```

### 2. Copy semua file template ini

Salin semua file dari folder ini ke dalam project Laravel yang baru dibuat. Override file yang sudah ada.

### 3. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai konfigurasi database kamu:

```env
DB_DATABASE=1017studios
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Buat database

```sql
CREATE DATABASE 1017studios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Jalankan migrasi & seeder

```bash
php artisan migrate --seed
```

### 6. Storage symlink (untuk upload file)

```bash
php artisan storage:link
```

### 7. Jalankan server

```bash
php artisan serve
```

Website bisa diakses di: **http://localhost:8000**

---

## 🔐 Admin Panel

- **URL:** http://localhost:8000/admin
- **Email:** admin@1017studios.com
- **Password:** password123

> ⚠️ **Penting:** Ganti password admin setelah pertama kali login!
> Masuk ke admin → menu user di pojok kanan atas.

### Fitur Admin:
| Menu | Fungsi |
|---|---|
| Dashboard | Statistik & pesan terbaru |
| Portfolio | Tambah/edit/hapus proyek |
| Services | Kelola layanan yang tampil |
| Testimonials | Kelola review klien |
| Messages | Lihat pesan dari form kontak |
| Settings | Info perusahaan, WhatsApp, statistik |

---

## 📱 Halaman Publik

| Route | Halaman |
|---|---|
| `/` | Homepage |
| `/services` | Halaman layanan |
| `/portfolio` | Portofolio (dengan filter kategori) |
| `/about` | Tentang kami |
| `/contact` | Kontak & form |

---

## 🎨 Kustomisasi

### Ganti nomor WhatsApp
1. Masuk ke **Admin → Settings**
2. Isi field **WhatsApp Number** format: `628xxxx` (tanpa +)

### Ganti logo
Ganti file `public/images/logo.png` dengan logo kamu.
Logo ditampilkan putih (`filter: brightness(0) invert(1)`) — pastikan logo punya background transparan.

### Tambah portfolio
1. Masuk ke **Admin → Portfolio → Add New**
2. Upload thumbnail, isi detail, centang "Featured" agar tampil di homepage

### Ubah statistik homepage
Masuk ke **Admin → Settings** → bagian Statistics

---

## 🗄️ Database Tables

| Table | Keterangan |
|---|---|
| `users` | Admin user (Laravel default) |
| `portfolios` | Item portofolio |
| `services` | Layanan perusahaan |
| `testimonials` | Review klien |
| `messages` | Pesan dari form kontak |
| `settings` | Konfigurasi website (key-value) |

---

## 🔧 Requirements

- PHP >= 8.2
- MySQL >= 5.7 / MariaDB >= 10.3
- Composer >= 2.x
- Extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

---

## 📦 Deployment ke Production

```bash
# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Environment
APP_ENV=production
APP_DEBUG=false
```

Pastikan folder `storage/` dan `bootstrap/cache/` writable:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

**Built with ❤️ by 1017Studios**
