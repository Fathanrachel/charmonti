# Black Box Testing Report

## CharmOnTi — E-Commerce Gelang Custom & Aksesoris
Dokumen ini berisi laporan pengujian *black box* terhadap Sistem E-Commerce CharmOnTi. Pengujian dilakukan tanpa melihat struktur kode internal: setiap kasus uji dirancang dari spesifikasi fungsional, dieksekusi, lalu hasil aktual dibandingkan dengan hasil yang diharapkan.

---

## 1. Ruang Lingkup & Metode

### 1.1 Level Pengujian

Pengujian dijalankan pada dua level untuk memastikan logika bisnis di sisi tampilan (Frontend) dan pemrosesan database (Backend) berjalan selaras:

| Level | Cara uji | Alasan |
|-------|----------|--------|
| **UI** | Tester berperan sebagai pelanggan, admin, atau owner melalui peramban web (browser). | Memverifikasi alur bisnis, navigasi antarmuka, notifikasi, dan respon tombol secara visual. |
| **Tinker (CLI)** | Menjalankan perintah PHP/Eloquent langsung pada command line Laravel. | Memverifikasi konsistensi relasi basis data PostgreSQL, integritas nilai kolom, dan hasil sinkronisasi laporan keuangan pasca pembatalan transaksi yang tidak terlihat langsung di UI. |

### 1.2 Teknik Pengujian

| Kode | Teknik | Penerapan |
|------|--------|-----------|
| **EP** | *Equivalence Partitioning* | Membagi domain input menjadi partisi valid dan invalid, lalu menguji satu perwakilan tiap partisi. |
| **BVA** | *Boundary Value Analysis* | Menguji batas nilai minimum dan maksimum (misalnya: kuantitas manik-manik gelang custom 1 - 15). |
| **ST** | *State Transition* | Menguji perpindahan status pesanan dan status pembayaran dari pending $\rightarrow$ diproses/paid $\rightarrow$ selesai atau batal. |
| **NEG** | *Negative Testing* | Menguji input atau aksi yang seharusnya ditolak/diamankan oleh sistem. |
| **FUNC** | *Functional Testing* | Verifikasi alur normal (*happy path*) dari setiap fitur. |

### 1.3 Lingkungan Pengujian

| Komponen | Keterangan |
|----------|------------|
| Backend / Framework | Laravel 10/11 + PostgreSQL |
| Frontend | TailwindCSS + Blade Template + Filament Admin Panel |
| Zona Waktu Aplikasi | `Asia/Jakarta` (WIB / GMT+7) |
| Payment Gateway | Midtrans (Sandbox) |

### 1.4 Data Uji Akun Akses

| Peran (Role) | Kredensial Uji | Tanggung Jawab Pengujian |
|-------|-----------|---------------|
| **Customer** | Akun terdaftar di database | Login, rakit gelang custom, checkout keranjang belanja, cek status bayar, ulasan produk, ajukan komplain. |
| **Admin** | Panel admin Filament | Konfirmasi pengerjaan, input nomor resi kurir, ubah status pengiriman. |
| **Owner** | Panel owner Filament | Kelola master bahan baku, monitoring grafik penjualan, unduh laporan PDF resmi. |

---

## 2. Pengujian Autentikasi & Kelola Profil (Customer)

**Tujuan:** Memverifikasi pengamanan profil pelanggan yang wajib diisi sebelum melakukan pemesanan barang.

| ID | Teknik | Level | Skenario | Input | Hasil Yang Diharapkan | Hasil Pengujian | Status |
|----|--------|-------|----------|-------|-----------------------|-----------------|--------|
| TC-CP-01 | FUNC | UI | Login dengan kredensial valid | Kredensial terdaftar | Berhasil masuk ke website utama, nama profile muncul di navigasi. | Sesuai Ekspektasi | **PASSED** |
| TC-CP-02 | NEG | UI | Mencoba checkout barang saat profil belum lengkap | Klik tombol "Checkout" dengan data kota/telepon kosong | Ditolak, dialihkan ke halaman profil dengan pesan peringatan merah: *“Silakan lengkapi data profil Anda terlebih dahulu...”* | Sesuai Ekspektasi | **PASSED** |
| TC-CP-03 | EP | UI | Mengisi profil dengan data lengkap & valid | Mengisi Nama, No Telepon, Provinsi, Kota, Alamat Lengkap, Kode Pos | Data profil berhasil diperbarui, muncul notifikasi sukses hijau, dan pelanggan diizinkan checkout. | Sesuai Ekspektasi | **PASSED** |

---

## 3. Sistem Keranjang & Pembuatan Gelang Custom (Customer)

**Tujuan:** Memverifikasi fungsionalitas perakitan gelang custom, keranjang belanja gabungan, dan perlindungan pengiriman form ganda.

| ID | Teknik | Level | Skenario | Input | Hasil Yang Diharapkan | Hasil Pengujian | Status |
|----|--------|-------|----------|-------|-----------------------|-----------------|--------|
| TC-CB-01 | BVA | UI | Memilih manik-manik gelang custom tepat pada batas maksimum | Mengisi jumlah total manik = 15 | Sistem menerima input, tombol "Masukkan ke Keranjang" aktif. | Sesuai Ekspektasi | **PASSED** |
| TC-CB-02 | BVA | UI | Memilih manik-manik gelang custom melebihi batas maksimum | Mengisi jumlah total manik = 16 | Ditolak, muncul pesan error: *“Total manik/charm yang Anda pilih melebihi batas maksimal (15 manik)”*. | Sesuai Ekspektasi | **PASSED** |
| TC-CB-03 | FUNC | UI | Melakukan checkout gabungan (gelang jadi + gelang custom) | Klik "Buat Pesanan & Bayar" dari halaman keranjang | Sistem sukses membuat satu data Order baru di database yang memuat semua item belanjaan. | Sesuai Ekspektasi | **PASSED** |
| TC-CB-04 | NEG | UI | Menekan tombol "Buat Pesanan" berkali-kali secara cepat (*Double Click*) | Klik tombol submit secara beruntun | Tombol submit langsung terkunci (*disabled*) menjadi abu-abu bertuliskan *“Memproses Pesanan... ⌛”*. Hanya 1 transaksi yang dibuat di database. | Sesuai Ekspektasi | **PASSED** |
| TC-CB-05 | FUNC | Tinker | Memverifikasi pengosongan keranjang belanja pasca checkout | Meninjau session `'cart'` | Session `'cart'` langsung terhapus (kosong) sesaat setelah tombol checkout ditekan untuk mencegah duplikasi order. | Sesuai Ekspektasi | **PASSED** |

---

## 4. Alur Pembayaran & Sinkronisasi Midtrans (Customer & Admin)

**Tujuan:** Menguji keserasian alur pembayaran dengan simulator Midtrans Sandbox dan pengamanan penanganan error.

| ID | Teknik | Level | Skenario | Input | Hasil Yang Diharapkan | Hasil Pengujian | Status |
|----|--------|-------|----------|-------|-----------------------|-----------------|--------|
| TC-PM-01 | FUNC | UI | Menampilkan tombol status bayar secara dinamis | Membuka halaman pembayaran pertama kali | Tombol *"Saya Sudah Bayar (Cek Status)"* tersembunyi. Tombol baru muncul setelah tombol *"Bayar Sekarang"* diklik pelanggan. | Sesuai Ekspektasi | **PASSED** |
| TC-PM-02 | FUNC | UI | Melakukan sinkronisasi status pembayaran lunas | Membayar di simulator, lalu klik *"Saya Sudah Bayar"* | Sistem memanggil status Midtrans, memperbarui status bayar menjadi **Lunas (Paid)**, dan status order berubah menjadi **Diproses**. | Sesuai Ekspektasi | **PASSED** |
| TC-PM-03 | NEG | UI | Klik "Cek Status" di panel Admin pada order yang belum diinisialisasi | Klik tombol *"Cek Status"* pada order yang belum diklik tombol bayar | Sistem menangkap API error 404 secara aman, menampilkan warning: *“Transaksi ini belum pernah dibuka atau diinisialisasi...”* tanpa crash. | Sesuai Ekspektasi | **PASSED** |

---

## 5. Logika Inventaris & Mutasi Stok (Admin & Owner)

**Tujuan:** Memverifikasi akurasi pemotongan stok bahan baku secara berkelompok (tidak terpecah-pecah) dan pengembalian stok saat pembatalan.

| ID | Teknik | Level | Skenario | Input | Hasil Yang Diharapkan | Hasil Pengujian | Status |
|----|--------|-------|----------|-------|-----------------------|-----------------|--------|
| TC-ST-01 | FUNC | UI | Memotong stok manik-manik secara akumulatif setelah lunas | Pesanan dengan 4 unit `charm2` diubah statusnya menjadi diproses | Di tabel **Bahan Keluar**, hanya bertambah **1 baris saja** dengan kolom *Jumlah Keluar* tertulis **4** (bukan 4 baris terpisah bernilai 1). | Sesuai Ekspektasi | **PASSED** |
| TC-ST-02 | ST | UI | Mengembalikan stok bahan baku saat pesanan dibatalkan oleh Admin | Mengubah status pesanan dari "diproses" menjadi **"Batal"** | Sistem secara reaktif membatalkan pengiriman, membatalkan transaksi keuangan, dan membuat **1 baris mutasi pengembalian** di tabel **Bahan Masuk** secara otomatis. | Sesuai Ekspektasi | **PASSED** |

---

## 6. Laporan Keuangan, PDF & Panel Kontrol (Owner)

**Tujuan:** Memverifikasi hak akses laporan Owner, perbaikan visual ulasan, dan sinkronisasi reaktif laporan pasca pembatalan.

| ID | Teknik | Level | Skenario | Input | Hasil Yang Diharapkan | Hasil Pengujian | Status |
|----|--------|-------|----------|-------|-----------------------|-----------------|--------|
| TC-OW-01 | NEG | UI | Mengakses rute download PDF Laporan tanpa autentikasi Owner | Membuka langsung URL PDF Laporan | Rute dilindungi middleware `auth:owner`, akses ditolak dan dialihkan ke form login Owner secara aman. | Sesuai Ekspektasi | **PASSED** |
| TC-OW-02 | ST | Tinker | Sinkronisasi reaktif laporan keuangan ketika transaksi dibatalkan | Mengubah status pesanan berbayar menjadi **Batal** | Laporan Keuangan Owner otomatis berkurang total pendapatannya (re-kalkulasi instan pendapatan & profit hari tersebut). | Sesuai Ekspektasi | **PASSED** |
| TC-OW-03 | FUNC | UI | Menggunakan tombol hapus bahan baku individual | Klik tombol merah **"Delete"** di pojok kanan atas edit bahan | Tombol Delete tampil dengan benar dan dapat digunakan Owner untuk menghapus bahan baku. | Sesuai Ekspektasi | **PASSED** |
| TC-OW-04 | FUNC | UI | Menampilkan ulasan gabungan produk di halaman utama | Membuka homepage `/` | Ulasan menampilkan seluruh produk yang dibeli pembeli dalam pesanan terkait dengan format koma (misal: *gelang Taylor, cincin BTS*). | Sesuai Ekspektasi | **PASSED** |

---
*Laporan ini diperbarui secara berkala seiring berjalannya siklus integrasi fitur.*
