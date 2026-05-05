# Onelito Web Lelang — Koi Auction & E-Commerce Platform

> Platform lelang dan penjualan ikan Koi berbasis web yang menggabungkan sistem lelang *real-time* dengan toko online, dilengkapi manajemen anggota, pembayaran terintegrasi, dan dasbor admin komprehensif.

---

## Daftar Isi

1. [Latar Belakang](#latar-belakang)
2. [Fitur Utama](#fitur-utama)
3. [Teknologi](#teknologi)
4. [Arsitektur Sistem](#arsitektur-sistem)
5. [Struktur Folder](#struktur-folder)
6. [Instalasi & Setup](#instalasi--setup)
7. [Menjalankan Aplikasi](#menjalankan-aplikasi)
8. [Konfigurasi Environment](#konfigurasi-environment)
9. [Routes & API Endpoint](#routes--api-endpoint)
10. [Panduan Penggunaan](#panduan-penggunaan)
11. [Bot & Otomasi](#bot--otomasi)
12. [Best Practices](#best-practices)
13. [Roadmap](#roadmap)
14. [Kontributor](#kontributor)
15. [Lisensi](#lisensi)

---

## Latar Belakang

Pasar ikan Koi di Indonesia berkembang pesat namun masih banyak transaksi yang dilakukan secara konvensional atau melalui platform umum yang tidak dirancang khusus untuk lelang ikan hidup. Onelito hadir untuk menjawab kebutuhan tersebut dengan menyediakan:

- **Sistem lelang terstruktur** dengan jadwal event, penentuan harga awal, dan mekanisme perpanjangan waktu otomatis saat terdapat tawaran di menit-menit terakhir.
- **Toko online** untuk produk dan stok Koi yang dapat dibeli langsung (non-lelang).
- **Transparansi transaksi** melalui log bidding, notifikasi real-time, dan invoice otomatis.
- **Manajemen terpusat** bagi admin untuk mengelola peserta, produk, event lelang, pengiriman, dan laporan keuangan.

---

## Fitur Utama

### Untuk Peserta (Member)
| Fitur | Deskripsi |
|---|---|
| Registrasi & Login | Form registrasi, login email/password, dan Google OAuth |
| Sistem Bidding | Penawaran manual dan auto-bid pada event lelang aktif |
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
| CSS Framework | Bootstrap | 4.2.1 |
| JavaScript Library | jQuery | 3.3.1 |
| Charting | Chart.js | 2.7.3 |
| Data Tables | DataTables | 1.10.18 |
| Rich Text Editor | Summernote | 0.8.11 |
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

## Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                             │
│         Browser (Blade + Bootstrap + jQuery + Vite)             │
└────────────────────────────┬────────────────────────────────────┘
                             │ HTTP/HTTPS
┌────────────────────────────▼────────────────────────────────────┐
│                      ROUTING LAYER                              │
│  routes/web.php (Guest, Member, Admin)  │  routes/api.php       │
└──────────────────┬──────────────────────┬───────────────────────┘
                   │                      │
       ┌───────────▼──────┐   ┌───────────▼──────────┐
       │    Middleware     │   │   API Controllers    │
       │  auth:member      │   │   (Sanctum Token)    │
       │  auth:admin       │   └──────────────────────┘
       └───────────┬───────┘
                   │
┌──────────────────▼──────────────────────────────────────────────┐
│                   CONTROLLER LAYER (41 Controllers)             │
│   AuctionController  │  OrderController  │  AdminControllers    │
│   CartController     │  ProfileController│  ProductController   │
└──────────────────┬──────────────────────────────────────────────┘
                   │
     ┌─────────────┼───────────────┐
     │             │               │
┌────▼────┐  ┌─────▼─────┐  ┌─────▼──────┐
│  Models │  │ Services  │  │    Jobs    │
│(Eloquent│  │ Auction   │  │ WhatsApp   │
│  ORM)   │  │ TimeService│  │ Notif      │
└────┬────┘  └───────────┘  └────────────┘
     │
┌────▼──────────────────────────────────────────────────────────┐
│                      DATABASE LAYER                            │
│                       MySQL 8+                                 │
│  m_peserta │ m_ikan_lelang │ t_log_bidding │ t_order │ m_produk│
└───────────────────────────────────────────────────────────────┘
```

### Alur Lelang

```
Admin buat Event Lelang
        │
        ▼
Admin tambahkan Ikan ke Event (harga awal, kelipatan bid)
        │
        ▼
Member membuka halaman /auction/{idIkan}
        │
        ├──► Member place bid (manual)
        │         │
        │         ├── Validasi harga ≥ harga minimum
        │         ├── Catat ke t_log_bidding
        │         ├── Notifikasi peserta lain (outbid)
        │         └── Perpanjang waktu jika < X menit tersisa
        │
        ├──► Auto-bid system aktif
        │         └── Bid otomatis sesuai batas yang ditetapkan member
        │
        ▼
Lelang berakhir → Admin konfirmasi pemenang
        │
        ▼
Invoice digenerate → Pesanan dibuat → Pembayaran via Xendit
        │
        ▼
Admin konfirmasi pembayaran → Proses pengiriman → Selesai
```

---

## Struktur Folder

```
onelito/
│
├── app/
│   ├── Console/            # Artisan commands
│   ├── Exceptions/         # Error handler kustom
│   ├── Http/
│   │   ├── Controllers/    # 41 controllers (web & admin)
│   │   ├── Middleware/     # Auth, CORS, dll
│   │   └── Requests/       # Form request validation
│   ├── Jobs/               # Queue jobs (WhatsApp, notifikasi)
│   ├── Mail/               # Email classes (verifikasi, password reset)
│   ├── Models/             # 42 Eloquent models
│   │   ├── Bot/            # Model khusus sistem bot
│   │   └── *.php           # Member, Event, Order, Product, dll
│   └── Services/
│       └── AuctionTimeService.php  # Logika kalkulasi waktu lelang
│
├── config/                 # Konfigurasi Laravel (app, auth, db, mail, dll)
│
├── database/
│   ├── migrations/         # 16+ file migrasi tabel
│   ├── seeders/            # Data awal (roles, settings, geography)
│   └── factories/          # Factory untuk testing
│
├── public/
│   ├── index.php           # Entry point aplikasi
│   ├── css/                # Compiled CSS (output Vite)
│   ├── js/                 # Compiled JS + third-party libraries
│   ├── img/                # Gambar statis dan upload
│   └── fonts/              # Web fonts
│
├── resources/
│   ├── css/app.css         # Entry CSS (Bootstrap + kustom)
│   ├── js/app.js           # Entry JS (jQuery, plugins)
│   └── views/
│       ├── admin/          # Halaman panel admin
│       ├── layout/         # Master layout (header, sidebar, footer)
│       ├── emails/         # Template email
│       └── components/     # Blade components reusable
│
├── routes/
│   ├── web.php             # Semua route web (guest, member, admin)
│   └── api.php             # Route API (Sanctum)
│
├── storage/
│   ├── app/public/         # File upload user (symlink ke public)
│   └── logs/               # Log aplikasi Laravel
│
├── tests/
│   ├── Feature/            # Integration tests
│   └── Unit/               # Unit tests
│
├── .env.example            # Template environment variables
├── composer.json           # PHP dependencies
├── package.json            # Node.js dependencies
├── vite.config.js          # Konfigurasi Vite build
└── Procfile                # Konfigurasi deployment Heroku
```

---

## Instalasi & Setup

### Prasyarat

Pastikan environment lokal memiliki:
- PHP ≥ 8.0.2 dengan ekstensi: `mbstring`, `xml`, `curl`, `gd`, `pdo_mysql`, `zip`
- Composer ≥ 2.x
- Node.js ≥ 16.x dan NPM ≥ 8.x
- MySQL 8+
- Git

### Step-by-Step

**1. Clone repository**
```bash
git clone <repository-url>
cd onelito
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Install Node.js dependencies**
```bash
npm install
```

**4. Salin dan konfigurasi environment**
```bash
cp .env.example .env
```
Edit file `.env` sesuai kebutuhan (lihat bagian [Konfigurasi Environment](#konfigurasi-environment)).

**5. Generate application key**
```bash
php artisan key:generate
```

**6. Buat database MySQL**
```sql
CREATE DATABASE onelito_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**7. Jalankan migrasi database**
```bash
php artisan migrate
```

**8. Jalankan seeder (data awal)**
```bash
php artisan db:seed
```

**9. Buat storage symlink**
```bash
php artisan storage:link
```

**10. Build assets frontend**
```bash
# Development
npm run dev

# Production
npm run build
```

---

## Menjalankan Aplikasi

### Development (Local)

```bash
# Terminal 1 — Laravel dev server
php artisan serve

# Terminal 2 — Vite hot-reload
npm run dev

# Terminal 3 — Queue worker (untuk notifikasi & WhatsApp)
php artisan queue:work
```

Akses aplikasi di: `http://localhost:8000`

### Production (Heroku)

Heroku menggunakan `Procfile` dengan Apache:

```
web: vendor/bin/heroku-php-apache2 public/
```

Deploy dengan:
```bash
git push heroku main
heroku run php artisan migrate --force
heroku run php artisan storage:link
```

### Cache & Optimasi (Production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

---

## Konfigurasi Environment

Salin `.env.example` ke `.env` dan isi variabel berikut:

### Aplikasi
```env
APP_NAME="Onelito Web Lelang"
APP_ENV=local                   # local | production
APP_KEY=                        # Di-generate oleh artisan key:generate
APP_DEBUG=true                  # false di production
APP_URL=http://localhost:8000
```

### Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=onelito_db
DB_USERNAME=root
DB_PASSWORD=
```

### Mail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io      # Ganti dengan provider SMTP aktual
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@onelito.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Autentikasi Google OAuth
```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/google/callback
```

### Payment Gateway (Xendit)
```env
XENDIT_SECRET_KEY=
XENDIT_PUBLIC_KEY=
XENDIT_CALLBACK_TOKEN=
```

### Queue
```env
QUEUE_CONNECTION=database       # database | redis
```

### WhatsApp Notification (opsional)
```env
WHATSAPP_API_URL=
WHATSAPP_API_KEY=
```

---

## Routes & API Endpoint

### Web Routes Publik

| Method | URL | Deskripsi |
|---|---|---|
| `GET` | `/` | Halaman beranda |
| `GET` | `/auction` | Daftar event lelang aktif |
| `GET` | `/auction/{idIkan}` | Detail ikan & form bidding |
| `GET` | `/koi_stok` | Katalog stok Koi |
| `GET` | `/onelito_store` | Toko online |
| `GET` | `/news` | Berita & artikel |
| `GET` | `/terms` | Syarat & ketentuan |
| `GET` | `/privacy` | Kebijakan privasi |

### Autentikasi

| Method | URL | Deskripsi |
|---|---|---|
| `GET/POST` | `/login` | Login member |
| `GET/POST` | `/registrasi` | Registrasi member baru |
| `GET` | `/logout` | Logout |
| `GET` | `/google/redirect` | Redirect ke Google OAuth |
| `GET` | `/google/callback` | Callback setelah Google login |
| `POST` | `/change-password` | Ubah password |

### Member Routes (Butuh Login)

| Method | URL | Deskripsi |
|---|---|---|
| `POST` | `/auction/{idIkan}` | Submit penawaran (bid) |
| `GET` | `/winning-auction` | Daftar lelang yang dimenangkan |
| `GET` | `/cart` | Keranjang belanja |
| `GET` | `/wishlist` | Daftar wishlist |
| `GET` | `/profil` | Halaman profil |
| `PUT` | `/profile/edit` | Update data profil |
| `GET` | `/order/invoice/{no_order}` | Unduh invoice PDF |
| `POST` | `/order` | Buat pesanan baru |
| `GET` | `/shipment` | Manajemen pengiriman |
| Resource | `/carts` | CRUD keranjang |
| Resource | `/wishlists` | CRUD wishlist |
| Resource | `/alamat` | CRUD alamat pengiriman |

### Admin Routes (Prefix: `/admin`)

| Method | URL | Deskripsi |
|---|---|---|
| `GET` | `/admin/dashboard` | Dasbor dengan grafik analitik |
| Resource | `/admin/members` | CRUD data member |
| Resource | `/admin/fishes` | CRUD ikan lelang |
| Resource | `/admin/products` | CRUD produk toko |
| Resource | `/admin/orders` | Manajemen pesanan |
| Resource | `/admin/auctions` | CRUD event lelang |
| `GET` | `/admin/auction-winners-info` | Info pemenang lelang |
| `PATCH` | `/admin/auction-winners-update` | Update status pemenang |
| `POST` | `/webhook/order/status` | Webhook status pembayaran Xendit |
| Resource | `/admin/bot/member` | Manajemen bot member |
| Resource | `/admin/bot/winner` | Manajemen bot winner |
| `GET` | `/admin/bot/data-lelang/{start_time}` | Data lelang untuk bot |
| `GET` | `/admin/bot/invoice/export/{id}` | Ekspor invoice bot |

### API Routes

| Method | URL | Auth | Deskripsi |
|---|---|---|---|
| `GET` | `/api/user` | Sanctum Token | Info user terautentikasi |

---

## Panduan Penggunaan

### Alur Peserta (Member)

```
1. Registrasi / Login
   └── Isi form registrasi atau login via Google

2. Jelajahi Event Lelang
   └── Buka /auction → pilih event aktif → pilih ikan

3. Lakukan Penawaran
   ├── Manual bid: masukkan nominal ≥ harga minimum
   └── Auto bid: tentukan batas maksimal, sistem bid otomatis

4. Pantau Status Bid
   └── Notifikasi masuk jika kalah bid (outbid)

5. Jika Menang
   ├── Cek /winning-auction
   ├── Konfirmasi pesanan dan pilih alamat pengiriman
   ├── Lakukan pembayaran via Xendit
   └── Unduh invoice dari /order/invoice/{no_order}

6. Tracking Pengiriman
   └── Pantau status di /shipment
```

### Alur Admin

```
1. Login ke /admin/login

2. Setup Event Lelang
   ├── Buat event baru di /admin/auctions
   └── Tambahkan ikan beserta harga awal dan kelipatan bid

3. Pantau Lelang Aktif
   └── Monitor log bidding dan peserta dari dasbor

4. Tutup Lelang & Tetapkan Pemenang
   ├── Tutup event setelah waktu habis
   └── Konfirmasi pemenang di /admin/auction-winners-info

5. Proses Pesanan
   ├── Konfirmasi pembayaran yang masuk
   ├── Update status pengiriman (input no. resi)
   └── Konfirmasi penerimaan oleh pembeli

6. Laporan & Ekspor
   └── Ekspor data member dan pemenang lelang ke Excel
```

---

## Bot & Otomasi

Sistem menyertakan modul **Bot** yang memungkinkan simulasi peserta lelang untuk keperluan pengujian atau event tertentu.

### Fitur Bot
- **Bot Member** — akun peserta simulasi dengan role terpisah
- **Bot Bidding** — mekanisme bid otomatis berdasarkan data lelang
- **Bot Winner** — pencatatan pemenang dari sesi bot

### Model Bot (namespace `App\Models\Bot\*`)
```
Bot/User.php       — User bot (koneksi DB: bot)
Bot/Member.php     — Member bot
Bot/Lelang.php     — Data lelang bot
Bot/Bid.php        — Record bid bot
Bot/PemenangLelang.php — Pemenang lelang bot
Bot/Role.php       — Role bot
```

> **Catatan:** Bot menggunakan koneksi database terpisah. Pastikan konfigurasi `DB_BOT_*` tersedia di `.env` jika fitur ini aktif.

---

## Best Practices

### Pengembangan

- **Guard Authentication:** Selalu gunakan middleware `auth:member` untuk route member dan `auth:admin` untuk route admin. Jangan mencampur guard.
- **Queue Jobs:** Semua notifikasi WhatsApp dan email berat harus melalui job queue. Jangan proses langsung di controller.
- **File Upload:** Gunakan `Storage::disk('public')` dan pastikan `php artisan storage:link` sudah dijalankan.
- **Validasi Input:** Gunakan `FormRequest` untuk validasi di controller; jangan validasi manual inline di method controller.
- **Polymorphic Relations:** Cart dan Wishlist menggunakan relasi polymorphic — pastikan `cartable_type` dan `cartable_id` di-set dengan benar saat membuat record.

### Database

- Tambahkan index pada kolom yang sering digunakan di `WHERE` clause, terutama `status`, `event_id`, `member_id`.
- Migrasi baru jangan memodifikasi migrasi lama — buat file migrasi baru untuk perubahan skema.
- Gunakan `softDeletes` untuk data yang perlu audit trail (member, produk, pesanan).

### Frontend

- Asset yang dikompilasi dengan Vite masuk ke `public/build/` — jangan edit file di sana secara manual.
- Gunakan helper `@vite(['resources/css/app.css', 'resources/js/app.js'])` di Blade layout.
- DataTables dengan server-side processing wajib menggunakan `yajra/datatables` untuk performa pada data besar.

### Keamanan

- Semua input form harus menggunakan `{{ csrf_field() }}` atau `@csrf`.
- Webhook Xendit diverifikasi menggunakan `XENDIT_CALLBACK_TOKEN` — validasi header `x-callback-token` sebelum memproses.
- Password reset dan verifikasi email menggunakan signed URL Laravel — jangan bypass dengan generate manual.
- Rate limiting perlu diterapkan pada endpoint bidding untuk mencegah spam bid.

---

## Roadmap

### v1.x (Saat Ini)
- [x] Sistem lelang dengan manual bid
- [x] Auto-bid system
- [x] Toko online (e-commerce)
- [x] Payment gateway Xendit
- [x] Notifikasi WhatsApp & email
- [x] Admin dashboard dengan analitik
- [x] Ekspor laporan Excel & PDF
- [x] Google OAuth login
- [x] Bot management

### v2.0 (Direncanakan)
- [ ] **Real-time bidding** menggunakan WebSocket (Laravel Reverb / Pusher) — menggantikan polling
- [ ] **Aplikasi Mobile** (Flutter/React Native) dengan API endpoint yang diperluas
- [ ] **Notifikasi Push** berbasis FCM untuk mobile
- [ ] **Multi-bahasa** (Indonesia & Inggris) menggunakan Laravel Localization
- [ ] **Sistem Rating & Review** untuk penjual dan produk
- [ ] **Dashboard Analytics** lebih lanjut dengan filter periode dan ekspor grafik

### v2.1 (Jangka Panjang)
- [ ] **Livestream Auction** — integrasi streaming video langsung pada halaman lelang
- [ ] **Escrow System** — dana pembeli ditahan sampai konfirmasi penerimaan
- [ ] **API Publik** dengan dokumentasi Swagger/OpenAPI untuk integrasi pihak ketiga
- [ ] **Multi-vendor** — memungkinkan beberapa penjual mengelola stok dan event sendiri

---

## Kontributor

| Nama | Peran |
|---|---|
| Angga Ferdani | Full-stack Developer |

---

## Lisensi

Project ini bersifat proprietary dan digunakan untuk keperluan internal bisnis Onelito. Seluruh kode sumber, desain, dan aset merupakan milik Onelito dan tidak untuk didistribusikan tanpa izin tertulis.

---

*Dokumentasi ini di-maintain bersama codebase. Jika ada perubahan arsitektur atau fitur baru, perbarui bagian yang relevan sebelum merge ke branch utama.*
