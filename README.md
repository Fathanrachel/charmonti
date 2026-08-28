# 🍪 BROWSTIME E-Commerce — Dokumentasi Instalasi

> **Panduan lengkap untuk menjalankan aplikasi BROWSTIME dari awal hingga berjalan sempurna.**

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

BROWSTIME adalah sistem e-commerce berbasis **Make-to-Order** untuk UMKM yang memproduksi makanan sesuai pesanan. Fitur utama:

### Customer Frontend
- 🏪 Katalog Produk dengan filter kategori dan pencarian
- 🛒 Keranjang Belanja (session-based)
- 📦 Checkout (Guest & Member)
- 💳 Multi Payment: Transfer Bank, QRIS Static, Midtrans
- 🚚 Kalkulasi Ongkir via RajaOngkir API
- 📍 Tracking Pesanan via kode booking
- ⭐ Review & Rating

### Admin Panel (Filament)
- 📊 Dashboard Analytics
- 📦 Manajemen Produk & Kategori
- 📝 Bill of Material (BOM) / Resep
- 📉 Mutasi Stok (pemakaian, masuk, rusak, penyesuaian)
- 🧾 Manajemen Pesanan
- 📈 Laporan dengan Export Excel

### Multi-Role & Panel Access
| Role | URL Panel | Akses |
|------|-----------|-------|
| **Admin** | `/admin` | Full access: Produk, Kategori, Bahan Baku, BOM, Satuan, Mutasi Stok, Pesanan, Akun Bank, Wilayah Pengiriman, Pengaturan Midtrans & QRIS |
| **Produksi** | `/produksi` | Pesanan siap produksi (status: paid/produksi/dikirim/selesai), Mutasi Stok, Dashboard statistik |
| **Keuangan** | `/keuangan` | Laporan Keuangan, Laporan Penjualan, Laporan Stok (dengan Export Excel), Dashboard statistik |
| **Pelanggan** | `/` | Customer frontend (katalog, keranjang, checkout, tracking) |

---

## 🛠 Tech Stack

| Kategori | Teknologi |
|----------|-----------|
| **Framework** | Laravel 12 |
| **PHP Version** | 8.2+ |
| **Admin Panel** | Filament 4.2 |
| **Frontend** | Livewire 3.7, Alpine.js 3.4 |
| **Styling** | TailwindCSS 4.0 |
| **Build Tool** | Vite 7.0 |
| **Database** | MySQL |
| **Authentication** | Laravel Breeze |
| **Authorization** | Spatie Laravel Permission 6.23 |
| **Media Library** | Spatie Media Library (Filament Plugin) |
| **Payment Gateway** | Midtrans PHP SDK 2.6 |
| **Shipping API** | RajaOngkir API |
| **Export** | Maatwebsite Excel 3.1 |

---

## 💻 Persyaratan Sistem

- **PHP** >= 8.2 dengan extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML, GD/Imagick
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **MySQL** >= 8.0 atau **MariaDB** >= 10.6
- **Git**

### Rekomendasi:
- **Laragon** (Windows)
- **Laravel Valet** (macOS)
- **Docker** dengan Laravel Sail

---

## 🚀 Instalasi

### 1️⃣ Clone Repository

```bash
git clone https://github.com/Faathir81/E-Commerce-Browstime.git
cd E-Commerce-Browstime
```

### 2️⃣ Install Dependency PHP

```bash
composer install
```

### 3️⃣ Install Dependency JavaScript

```bash
npm install
```

### 4️⃣ Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 5️⃣ Setup Database

Buat database MySQL:

```sql
CREATE DATABASE browstime_ecommerce;
```

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=browstime_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

### 6️⃣ Jalankan Migration & Seeder

```bash
php artisan migrate
php artisan db:seed
```

Atau sekaligus:

```bash
php artisan migrate:fresh --seed
```

### 7️⃣ Setup Storage Link

```bash
php artisan storage:link
```

### 8️⃣ Build Asset Frontend

```bash
npm run build
```

---

## ⚙️ Konfigurasi Environment

Edit `.env` sesuai kebutuhan:

```env
# Aplikasi
APP_NAME=BROWSTIME
APP_URL=http://127.0.0.1:8000

# Database
DB_DATABASE=browstime_ecommerce
DB_USERNAME=root
DB_PASSWORD=

# RajaOngkir API
RAJAONGKIR_API_KEY=your_api_key_here
RAJAONGKIR_BASE_URL=https://rajaongkir.komerce.id/api/v1
RAJAONGKIR_ORIGIN_SUBDISTRICT_ID=763
RAJAONGKIR_COURIER=jne
```

---

## ▶️ Menjalankan Aplikasi

### Quick Start (Rekomendasi)

```bash
composer dev
```

Ini menjalankan secara paralel:
- PHP Server (`php artisan serve`)
- Queue Worker (`php artisan queue:listen`)
- Log Viewer (`php artisan pail`)
- Vite Dev Server (`npm run dev`)

### Manual

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev

# Terminal 3 (opsional)
php artisan queue:listen
```

### Akses URL

| URL | Deskripsi |
|-----|-----------|
| http://127.0.0.1:8000 | Landing Page / Customer Frontend |
| http://127.0.0.1:8000/login | **Login (semua role)** |
| http://127.0.0.1:8000/register | Register Customer baru |
| http://127.0.0.1:8000/admin | Panel Admin |
| http://127.0.0.1:8000/produksi | Panel Staf Produksi |
| http://127.0.0.1:8000/keuangan | Panel Bagian Keuangan |

---

## 🌱 Data Seeder

### Menjalankan Semua Seeder

```bash
php artisan db:seed
```

### Seeder Spesifik

```bash
# Main seeder (roles, users, produk, bahan baku, BOM)
php artisan db:seed --class=BrowstimeSeeder

# Pesanan dummy untuk testing
php artisan db:seed --class=DummyOrderSeeder
```

### Reset Database + Seeder

```bash
php artisan migrate:fresh --seed
```

### Data yang Di-seed

| Data | Jumlah | Keterangan |
|------|--------|------------|
| Roles | 4 | admin, produksi, keuangan, pelanggan |
| Users | 4 | 1 per role |
| Kategori | 2 | Cookies, Brownies |
| Satuan | 3 | Gram, Pcs, Milligram |
| Bahan Baku | 14 | Matcha, Butter, Telur, dll |
| Produk | 10 | 8 Cookies + 2 Brownies |
| BOM/Resep | 10 | 1 resep per produk |
| Metode Pembayaran | 3 | Transfer, QRIS, Midtrans |
| Akun Bank | 1 | BCA - Browstime |

---

## 🔑 Login & Akun Demo

### Sistem Login

> **Semua user login di satu tempat yang sama:** `/login`

Setelah login berhasil, sistem akan **otomatis redirect** ke panel sesuai role:

```
Login (/login) → Cek Role → Redirect ke Dashboard sesuai role
```

| Role | Redirect setelah Login |
|------|------------------------|
| Admin | `/admin` |
| Produksi | `/produksi` |
| Keuangan | `/keuangan` |
| Pelanggan | `/` (Landing Page) |

### Akun Demo

Setelah menjalankan seeder, gunakan akun berikut untuk testing:

| Role | Email | Password | Redirect ke |
|------|-------|----------|-------------|
| **Admin** | admin@demo.com | password | `/admin` |
| **Produksi** | produksi@demo.com | password | `/produksi` |
| **Keuangan** | keuangan@demo.com | password | `/keuangan` |
| **Pelanggan** | pelanggan1@demo.com | password | `/` (landing) |

---

## 🐛 Troubleshooting

### Database tidak ditemukan
```bash
mysql -u root -e "CREATE DATABASE browstime_ecommerce"
```

### Class not found
```bash
composer dump-autoload
php artisan optimize:clear
```

### Vite manifest not found
```bash
npm run build
```

### Storage images tidak tampil
```bash
php artisan storage:link
```

---

**Developed by Silver Capybara**
