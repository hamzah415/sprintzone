# SprintZone

Aplikasi **e-commerce berbasis Laravel** untuk penjualan produk sepatu dan aksesoris performance, dengan fitur katalog, keranjang, checkout, histori pembelian, autentikasi, serta panel admin.

## ✨ Fitur Utama

### Untuk pelanggan
- Halaman utama dengan produk populer dan hero banner
- Etalase produk dengan filter brand dan sorting harga
- Detail produk dengan variansi (warna/ukuran) dan stok
- Pencarian produk
- Keranjang belanja dan checkout
- Riwayat pembelian
- Login/registrasi pengguna
- Login Google
- 2FA (Google Authenticator) untuk keamanan akun

### Untuk admin
- Dashboard admin
- Manajemen perusahaan, brand, kategori
- Manajemen produk dan varian
- Manajemen pengguna
- Riwayat pembelian dan status transaksi

### Integrasi pembayaran
- Midtrans untuk proses pembayaran

## 🛠️ Teknologi

- PHP `^8.3`
- Laravel `^13.0`
- Laravel Socialite
- Midtrans PHP SDK
- Google2FA
- Vite + Tailwind CSS

## 📁 Struktur Proyek

- `app/Http/Controllers` : logika bisnis dan controller
- `app/Models` : model Eloquent
- `database/migrations` : skema database
- `resources/views` : tampilan Blade
- `routes/web.php` : definisi route web
- `config/` : konfigurasi aplikasi, layanan, Midtrans, dan lainnya

## 🚀 Cara Menjalankan Secara Lokal

### 1. Install dependensi

```bash
composer install
npm install
```

### 2. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

Setelah itu, edit file `.env` dan sesuaikan:

- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `GOOGLE_CLIENT_ID`
- `GOOGLE_CLIENT_SECRET`
- `GOOGLE_REDIRECT_URI`
- `MIDTRANS_SERVER_KEY`
- `MIDTRANS_CLIENT_KEY`

### 3. Migrasi database

```bash
php artisan migrate
```

### 4. Jalankan aplikasi

```bash
php artisan serve
npm run dev
```

Aplikasi akan berjalan di `http://127.0.0.1:8000`.

## 🧪 Testing

Untuk menjalankan test aplikasi:

```bash
php artisan test
```

## 📦 Script Composer

Project ini juga memiliki script `composer` untuk setup awal:

```bash
composer run setup
```

> Script ini menginstall dependensi, membuat `.env`, generate APP_KEY, menjalankan migrasi, menginstall dependency frontend, dan membuild aset.

## 🔐 Catatan Keamanan

- Route admin dilindungi dengan middleware `auth`, `2fa`, dan `isAdmin`
- Login Google menggunakan Laravel Socialite
- 2FA menggunakan Google Authenticator
- Midtrans dikonfigurasi lewat `config/midtrans.php`

## 📌 Halaman Utama

- `/` : landing page / katalog produk
- `/etalase` : halaman etalase produk
- `/search` : halaman pencarian
- `/login` dan `/uregister` : autentikasi pengguna
- `/cart` dan `/checkout` : proses pembelian
- `/dashboard` atau `/admin/dashboard` : panel admin

## 👤 Kontributor

Jika kamu ingin mengembangkan project ini lebih lanjut, pastikan menjalankan migrasi terbaru dan meninjau file `routes/web.php` serta controller terkait sebelum menambahkan fitur baru.

## 📄 Lisensi

Proyek ini menggunakan lisensi MIT.
