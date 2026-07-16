# Onelito Web Lelang — Koi Auction & E-Commerce Platform

> Platform lelang dan penjualan ikan Koi berbasis web yang menggabungkan sistem lelang *real-time*, undian berhadiah (Lucky Draw), dan toko online, dilengkapi manajemen anggota, pembayaran terintegrasi, dan dasbor admin komprehensif.

---

## Daftar Isi

1. [Latar Belakang](#latar-belakang)
2. [Fitur Utama](#fitur-utama)
3. [Teknologi](#teknologi)
4. [Struktur Folder](#struktur-folder)
5. [Instalasi & Setup](#instalasi--setup)
6. [Menjalankan Aplikasi](#menjalankan-aplikasi)
7. [Konfigurasi Environment](#konfigurasi-environment)
8. [Routes Utama](#routes-utama)
9. [Kontributor](#kontributor)
10. [Lisensi](#lisensi)

---

## Latar Belakang

Pasar ikan Koi di Indonesia berkembang pesat namun masih banyak transaksi yang dilakukan secara konvensional atau melalui platform umum yang tidak dirancang khusus untuk lelang ikan hidup. Onelito hadir untuk menjawab kebutuhan tersebut dengan menyediakan:

- **Sistem lelang terstruktur** dengan jadwal event, penentuan harga awal, dan mekanisme perpanjangan waktu otomatis saat terdapat tawaran di menit-menit terakhir.
- **Lucky Draw** — event undian berhadiah per ikan, peserta mendaftar dan mendapat nomor urut unik, lalu memilih ikan mana saja yang ingin diikutkan undian.
- **Toko online** untuk produk dan stok Koi yang dapat dibeli langsung (non-lelang).
- **Transparansi transaksi** melalui log bidding, notifikasi real-time, dan invoice otomatis.
- **Manajemen terpusat** bagi admin untuk mengelola peserta, produk, event lelang, event Lucky Draw, pengiriman, dan laporan keuangan.

---

## Fitur Utama

### Untuk Peserta (Member)

| Fitur | Deskripsi |
|---|---|
| Registrasi & Login | Form registrasi dengan verifikasi nomor telepon via OTP WhatsApp, login email/password, dan Google OAuth |
| Sistem Bidding | Penawaran manual pada event lelang aktif |
| Lucky Draw | Daftar ke event Lucky Draw yang sedang aktif (dapat nomor urut unik per event), pilih ikan mana saja yang ingin diikutkan undian, lihat siapa saja peserta lain yang ikut undian ikan yang sama |
| Notifikasi Real-time | Pemberitahuan saat kalah bid, menang lelang, dan status pesanan |
| Keranjang & Wishlist | Polymorphic cart & wishlist untuk produk dan ikan |
| Manajemen Alamat | Multiple delivery address dengan hierarki wilayah (Provinsi → Kelurahan) |
| Riwayat Pesanan | Tracking status pesanan dari pembayaran hingga pengiriman |
| Invoice Digital | Unduh invoice PDF untuk setiap transaksi |

### Untuk Admin

| Fitur | Deskripsi |
|---|---|
| Dashboard Analitik | Grafik penjualan, nominal transaksi, dan peserta lelang (Chart.js) |
| Manajemen Event Lelang | Buat, edit, tutup event; kelola ikan per event |
| Manajemen Lucky Draw | Buat, edit, tutup event Lucky Draw; kelola ikan (termasuk harga) yang diikutkan per event |
| Manajemen Produk & Stok | CRUD produk, kategori, label, foto, dan stok Koi |
| Manajemen Pesanan | Konfirmasi pembayaran, proses pengiriman, dan konfirmasi penerimaan |
| Manajemen Member | Daftar member, histori login, ekspor Excel |
| Pemenang Lelang | Penetapan pemenang, konfirmasi, dan generate invoice |
| Manajemen Bot | Simulasi peserta bot untuk keperluan pengujian lelang |
| Notifikasi Sistem | Kirim pengumuman ke semua peserta atau grup tertentu |
| Pengaturan Aplikasi | Konfigurasi banner, mata uang, setting umum |

---

## Teknologi

### Backend

| Komponen | Teknologi | Versi |
|---|---|---|
| Framework | Laravel | 9.48.* |
| Runtime | PHP | ≥ 8.0.2 |
| ORM | Eloquent (built-in) | — |
| Autentikasi API | Laravel Sanctum | ^2.14.1 |
| OAuth | Laravel Socialite | ^5.20 |
| Role & Permission | Spatie Laravel Permission | ^5.4 |
| Payment Gateway | Xendit PHP SDK | ^6.0 |
| PDF | barryvdh/laravel-dompdf | ^2.2 |
| Excel | Maatwebsite Excel | ^3.1 |
| Image Processing | Intervention Image | 3.0 |
| QR Code | simplesoftwareio/simple-qrcode | ~4 |
| Barcode | milon/barcode | ^12.0 |
| DataTables SSP | yajra/laravel-datatables-oracle | ~9.0 |

### Frontend

| Komponen | Teknologi | Versi |
|---|---|---|
| Build Tool | Vite | — |
| Template Engine | Laravel Blade | — |
| CSS Framework | Bootstrap | 4.2.1 / 5 (halaman baru) |
| JavaScript Library | jQuery | 3.3.1 |
| Charting | Chart.js | 2.7.3 |
| Data Tables | DataTables | 1.10.18 |
| Rich Text Editor | Summernote / TinyMCE | — |
| Select Dropdown | Select2 | 4.0.6 |
| Alert Dialog | SweetAlert | 2.1.2 |
| Calendar | Fullcalendar | 3.10.0 |

### Database & Infrastruktur

| Komponen | Teknologi |
|---|---|
| Database | MySQL 8+ |
| Queue Driver | Database |
| File Storage | Local (Laravel Storage) |
| Deployment | Heroku (Apache) |
| Mail | SMTP (konfigurabel) |

---

## Struktur Folder

```
onelito/
│
├── app/
│   ├── Console/            # Artisan commands
│   ├── Http/
│   │   ├── Controllers/    # Controller publik + Admin/ (CRUD panel admin)
│   │   ├── Middleware/     # Auth, CORS, dll
│   │   └── Requests/       # Form request validation
│   ├── Jobs/               # Queue jobs (WhatsApp, notifikasi)
│   ├── Mail/               # Email classes (verifikasi, password reset)
│   ├── Models/              # Eloquent models (Member, Event, LuckyDrawEvent, dll)
│   │   └── Bot/            # Model khusus sistem bot
│   └── Services/
│       └── AuctionTimeService.php  # Logika kalkulasi waktu lelang
│
├── config/                 # Konfigurasi Laravel (app, auth, db, mail, dll)
│
├── database/
│   ├── migrations/         # File migrasi tabel
│   ├── seeders/            # Data awal (roles, settings, geography)
│   └── factories/          # Factory untuk testing
│
├── public/                 # Entry point aplikasi, aset statis, hasil build Vite
│
├── resources/
│   ├── css/, js/           # Entry CSS & JS
│   └── views/
│       ├── admin/          # Panel admin (auction, lucky-draw, produk, dll)
│       ├── layout/, part/  # Master layout & navbar situs publik
│       ├── new/            # Template halaman publik yang lebih baru
│       └── emails/         # Template email
│
├── routes/
│   ├── web.php             # Semua route web (guest, member, admin)
│   └── api.php             # Route API (Sanctum)
│
├── storage/
│   ├── app/public/         # File upload user (symlink ke public)
│   └── logs/               # Log aplikasi Laravel
│
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── Procfile                # Konfigurasi deployment Heroku
```

---

## Instalasi & Setup

### Prasyarat

- PHP ≥ 8.0.2 dengan ekstensi: `mbstring`, `xml`, `curl`, `gd`, `pdo_mysql`, `zip`
- Composer ≥ 2.x
- Node.js ≥ 16.x dan NPM ≥ 8.x
- MySQL 8+
- Git

### Step-by-Step

```bash
# 1. Clone repository
git clone <repository-url>
cd onelito

# 2. Install dependencies
composer install
npm install

# 3. Konfigurasi environment
cp .env.example .env
php artisan key:generate

# 4. Buat database MySQL
# CREATE DATABASE onelito_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 5. Migrasi & seed
php artisan migrate
php artisan db:seed

# 6. Storage symlink
php artisan storage:link

# 7. Build assets
npm run dev    # development
npm run build  # production
```

---

## Menjalankan Aplikasi

### Development

```bash
php artisan serve       # Terminal 1 — Laravel dev server
npm run dev              # Terminal 2 — Vite hot-reload
php artisan queue:work   # Terminal 3 — Queue worker (notifikasi & WhatsApp)
```

Akses aplikasi di `http://localhost:8000`.

### Production (Heroku)

```bash
git push heroku main
heroku run php artisan migrate --force
heroku run php artisan storage:link
```

Cache & optimasi:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

---

## Konfigurasi Environment

Salin `.env.example` ke `.env` dan isi variabel utama berikut:

```env
APP_NAME="Onelito Web Lelang"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=onelito_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_FROM_ADDRESS=noreply@onelito.com

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/google/callback

XENDIT_SECRET_KEY=
XENDIT_PUBLIC_KEY=
XENDIT_CALLBACK_TOKEN=

QONTAK_API_KEY=
QONTAK_CHANNEL_INTEGRATION_ID=

QUEUE_CONNECTION=database
```

---

## Routes Utama

### Publik

| Method | URL | Deskripsi |
|---|---|---|
| `GET` | `/` | Halaman beranda |
| `GET` | `/auction` | Daftar event lelang aktif |
| `GET` | `/auction/{idIkan}` | Detail ikan & form bidding |
| `GET` | `/lucky-draw` | Event Lucky Draw yang sedang aktif |
| `GET` | `/lucky-draw/{idIkan}/detail` | Detail ikan Lucky Draw & daftar peserta yang ikut undian |
| `GET` | `/koi_stok` | Katalog stok Koi |
| `GET` | `/onelito_store` | Toko online |

### Autentikasi

| Method | URL | Deskripsi |
|---|---|---|
| `GET/POST` | `/login` | Login member |
| `GET/POST` | `/registrasi` | Registrasi member + verifikasi OTP WhatsApp |
| `GET` | `/google/redirect`, `/google/callback` | Google OAuth |
| `GET/POST` | `/reqreset` | Lupa password |
| `GET/POST` | `/ls/reset` | Reset password via link email |

### Member (butuh login)

| Method | URL | Deskripsi |
|---|---|---|
| `POST` | `/auction/{idIkan}` | Submit penawaran (bid) |
| `POST` | `/lucky-draw/register` | Daftar ke event Lucky Draw aktif (dapat nomor urut unik) |
| `POST` | `/lucky-draw/{idIkan}/toggle-entry` | Ikut/batal ikut undian ikan tertentu |
| `GET` | `/cart`, `/wishlist`, `/profil` | Keranjang, wishlist, profil |

### Admin (prefix `/admin`)

| Method | URL | Deskripsi |
|---|---|---|
| `GET` | `/admin/dashboard` | Dasbor analitik |
| Resource | `/admin/auctions`, `/admin/auction-products` | CRUD event & ikan lelang |
| Resource | `/admin/lucky-draws`, `/admin/lucky-draw-fishes` | CRUD event & ikan Lucky Draw |
| Resource | `/admin/products`, `/admin/orders`, `/admin/members` | Manajemen produk, pesanan, member |
| Resource | `/admin/bot/member`, `/admin/bot/winner` | Manajemen bot untuk simulasi lelang |

---

## Kontributor

| Nama | Peran |
|---|---|
| Angga Ferdani | Full-stack Developer |

---

## Lisensi

Project ini bersifat proprietary dan digunakan untuk keperluan internal bisnis Onelito. Seluruh kode sumber, desain, dan aset merupakan milik Onelito dan tidak untuk didistribusikan tanpa izin tertulis.
