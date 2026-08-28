# 🐳 Menjalankan CharmOnTi dengan Docker

Panduan ini menjelaskan cara menjalankan project **CharmOnTi** menggunakan Docker — tanpa perlu install PHP, Composer, Node.js, atau PostgreSQL secara manual di laptop kamu.

---

## ✅ Prasyarat

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) sudah terinstall dan **running** (ikon Docker di taskbar berwarna biru)

---

## 🚀 Cara Menjalankan (Pertama Kali)

### 1. Clone / Download Project
```bash
git clone <url-repo>
cd charmonti
```

### 2. Setup File Environment
```bash
# Copy contoh env
cp .env.example .env
```

Buka `.env` dan pastikan konfigurasi berikut **sudah benar**:
```env
APP_KEY=          # ← akan di-generate otomatis
APP_URL=http://localhost:8080

DB_HOST=db        # ← WAJIB: nama service Docker, bukan localhost!
DB_PORT=5432
DB_DATABASE=charmonti_db
DB_USERNAME=postgres
DB_PASSWORD=postgres

MIDTRANS_SERVER_KEY=Mid-server-xxxxx   # ← Isi dengan key kamu
MIDTRANS_CLIENT_KEY=Mid-client-xxxxx   # ← Isi dengan key kamu
```

### 3. Generate APP_KEY
```bash
# Jalankan sekali untuk generate key
docker compose run --rm app php artisan key:generate
```

### 4. Build & Jalankan Semua Service
```bash
docker compose up -d --build
```

> Proses pertama kali bisa memakan waktu **3-10 menit** karena Docker mengunduh image dan membangun aplikasi.

### 5. Akses Aplikasi

| Panel | URL |
|-------|-----|
| 🛍️ Customer (Homepage) | http://localhost:8080 |
| 👨‍💼 Admin Panel | http://localhost:8080/admin |
| 👑 Owner Panel | http://localhost:8080/owner |

---

## 📋 Perintah Sehari-hari

```bash
# Jalankan (tanpa build ulang)
docker compose up -d

# Hentikan semua container
docker compose down

# Lihat status container
docker compose ps

# Lihat log real-time
docker compose logs -f

# Lihat log salah satu service
docker compose logs -f app
docker compose logs -f db
docker compose logs -f queue

# Masuk ke dalam container app
docker compose exec app sh

# Jalankan artisan command
docker compose exec app php artisan <command>

# Contoh: buat seeder
docker compose exec app php artisan db:seed

# Contoh: buat migration
docker compose exec app php artisan make:migration create_xxx_table
```

---

## 🔄 Update Setelah Ada Perubahan Kode

```bash
# Rebuild image dan restart
docker compose up -d --build
```

---

## ⚠️ Peringatan Penting

> **Jangan hapus volume** kecuali kamu sengaja ingin reset database!

```bash
# HATI-HATI: Ini akan menghapus semua data database!
docker compose down -v
```

---

## 🏗️ Arsitektur Container

```
docker compose up
      │
      ├─► charmonti_app    → FrankenPHP + Laravel  (port 8080)
      ├─► charmonti_db     → PostgreSQL 16          (port 5432)
      └─► charmonti_queue  → Laravel Queue Worker
```

---

## ❓ Troubleshooting

**Container tidak mau start?**
```bash
docker compose logs app
```

**Database error?**
```bash
# Cek apakah DB service healthy
docker compose ps
# Pastikan DB_HOST=db di .env (bukan localhost)
```

**Port 8080 sudah dipakai?**
Ubah di `docker-compose.yml`:
```yaml
ports:
  - "9000:80"   # Ganti 8080 dengan port lain
```

**Reset total (mulai dari awal)?**
```bash
docker compose down -v
docker compose up -d --build
```
