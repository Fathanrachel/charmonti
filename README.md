# 🌸 CharmOnTi E-Commerce — Dokumentasi Instalasi

> **Panduan lengkap untuk menjalankan aplikasi CharmOnTi E-Commerce Gelang Custom dari awal hingga berjalan sempurna.**

---

## 📋 Daftar Isi

- [Spesifikasi Aplikasi](#-spesifikasi-aplikasi)
- [Tech Stack](#-tech-stack)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Konfigurasi Environment](#-konfigurasi-environment)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Data Seeder](#-data-seeder)
- [Akun Demo](#-akun-demo)
- [Troubleshooting](#-troubleshooting)

---

## 📖 Spesifikasi Aplikasi

CharmOnTi adalah sistem e-commerce inovatif yang memungkinkan pelanggan membeli produk perhiasan jadi atau merakit **Gelang Custom** mereka sendiri secara interaktif (maksimal 15 charm/manik per gelang).

### Customer Frontend
- 🏪 Katalog Produk Jadi & Bahan Gelang Custom
- 🛠️ Fitur Rakit Gelang Kustom Interaktif
- 🛒 Keranjang Belanja & Checkout Terintegrasi
- 💳 Pembayaran Otomatis via Midtrans (Transfer Bank, QRIS, E-Wallet)
- 🚚 Manajemen Pengiriman dengan Ekspedisi
- 📍 Tracking Status Pesanan

### Admin Panel (Filament)
- 📊 Dashboard Penjualan & Stok
- 📦 Manajemen Produk Jadi & Bahan Kustom
- 📉 Mutasi Stok FIFO Terpusat (Bahan Masuk/Keluar, Produk Masuk/Keluar)
- 🧾 Manajemen Pesanan & Verifikasi Status
- 🚚 Manajemen Kota & Ekspedisi (Ongkir)

### Owner Panel (Filament)
- 📈 Dashboard Statistik Bisnis
- 📊 Laporan Penjualan (Otomatis Tersinkron)
- 💰 Laporan Keuangan (Revenue, Expense, Profit)
- 📄 Export Laporan ke format PDF (via DOMPDF)

### Multi-Role & Panel Access
| Role | URL Panel | Akses |
|------|-----------|-------|
| **Admin** | `/admin` | Mengelola operasional harian: Produk, Bahan, Mutasi Stok, Pesanan, Ekspedisi. |
| **Owner** | `/owner` | Memantau performa bisnis dan mengunduh Laporan Keuangan/Penjualan (PDF). |
| **Pelanggan** | `/` | Mengakses katalog, merakit gelang, keranjang, dan checkout. |

---

## 🛠 Tech Stack

| Kategori | Teknologi |
|----------|-----------|
| **Framework** | Laravel 13.x |
| **PHP Version** | 8.3 / 8.4 |
| **Admin Panel** | Filament 5.0 |
| **Frontend** | TailwindCSS 4.0, Blade Templates |
| **Build Tool** | Vite 8.0 |
| **Database** | PostgreSQL 16 (via Docker) / SQLite (Lokal) |
| **Web Server** | FrankenPHP (via Docker) |
| **Payment Gateway** | Midtrans PHP SDK 2.6 |
| **PDF Generator** | DOMPDF (barryvdh/laravel-dompdf) |

---

## 💻 Persyaratan Sistem

### Opsi 1: Menggunakan Docker (Sangat Direkomendasikan ⭐)
- **Docker Desktop** (untuk Windows/Mac) atau Docker Engine (Linux)
- WSL2 (khusus pengguna Windows)

### Opsi 2: Manual Tanpa Docker
- **PHP** >= 8.3 dengan extensions: PDO, Mbstring, PCNTL, GD, Zip, Intl, PostgreSQL/SQLite
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **PostgreSQL** atau **SQLite**
- **Git**

---

## 🚀 Instalasi (Menggunakan Docker)

Cara ini paling mudah karena **tidak perlu install PHP/Node/Database** di laptop kamu.

### 1️⃣ Clone Repository

```bash
git clone <url-repo-charmonti>
cd charmonti
```

### 2️⃣ Konfigurasi Environment

```bash
cp .env.example .env
```
*(Lihat bagian [Konfigurasi Environment](#-konfigurasi-environment) di bawah untuk penyesuaian `.env`)*

### 3️⃣ Jalankan Docker Services

```bash
docker compose up -d --build
```
*Proses ini akan mengunduh dependencies, melakukan build frontend, dan menyalakan Database.*

### 4️⃣ Generate Key & Setup Database

Jalankan perintah ini di dalam container:

```bash
# Generate APP_KEY
docker compose exec app php artisan key:generate

# Storage Link (Opsional, sudah dihandle entrypoint)
docker compose exec app php artisan storage:link

# Jalankan seeder (jika butuh data dummy awal)
docker compose exec app php artisan db:seed
```

---

## ⚙️ Konfigurasi Environment

Pastikan file `.env` kamu diatur seperti ini jika menggunakan Docker:

```env
APP_NAME=CharmOnTi
APP_ENV=local
APP_URL=http://localhost:8080

# Konfigurasi Database (Arahkan ke service docker 'db')
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=charmonti_db
DB_USERNAME=postgres
DB_PASSWORD=postgres

# Integrasi Midtrans
MIDTRANS_SERVER_KEY=Mid-server-YOUR_SERVER_KEY
MIDTRANS_CLIENT_KEY=Mid-client-YOUR_CLIENT_KEY
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

---

## ▶️ Menjalankan Aplikasi

Aplikasi berjalan di port `8080` jika menggunakan Docker.

| URL | Deskripsi |
|-----|-----------|
| http://localhost:8080 | Landing Page / Customer Frontend |
| http://localhost:8080/login | Halaman Login Customer |
| http://localhost:8080/admin | Panel Staff Admin |
| http://localhost:8080/owner | Panel Pemilik Bisnis (Owner) |

---

## 🌱 Data Seeder

Untuk mengisi database dengan data wilayah awal (Kota & Ekspedisi) atau akun contoh:

```bash
# Via Docker
docker compose exec app php artisan db:seed

# Manual (jika tidak pakai Docker)
php artisan db:seed
```

Jika kamu memiliki seeder lokasi spesifik untuk ongkos kirim:
```bash
docker compose exec app php artisan db:seed --class=LocationSeeder
```

---

## 🔑 Login & Akun Demo

Akses panel didasarkan pada guard dan tabel `profiles`. Setiap pengguna yang terdaftar di Panel harus memiliki profil dengan role yang sesuai.

*Jika kamu sudah menjalankan seeder (dan seeder memuat akun dummy), kamu bisa login dengan akun yang disediakan seeder.*

Jika belum, kamu bisa membuat user pertama melalui command line atau register di halaman web, lalu secara manual mengubah nilai `role` di tabel `profiles` menjadi `admin` atau `owner`.

---

## 🐛 Troubleshooting

### Database Error saat start Docker
Pastikan `DB_HOST=db` di file `.env`. Jangan gunakan `127.0.0.1`.

### KeyGenerateCommand Read-Only file system
Jika `php artisan key:generate` gagal karena file read-only, jalankan:
```bash
docker compose exec app php artisan key:generate --show
```
Lalu copy hasilnya (dimulai dengan `base64:...`) dan paste secara manual ke file `.env` lokal kamu.

### Gambar/Foto Tidak Muncul
Pastikan storage link sudah dibuat:
```bash
docker compose exec app php artisan storage:link
```

---

**Developed for CharmOnTi E-Commerce**
