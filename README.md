# Onelito Web Lelang — Koi Auction & E-Commerce Platform

> Platform lelang dan penjualan ikan Koi berbasis web yang menggabungkan sistem lelang *real-time*, undian berhadiah (Lucky Draw), dan toko online, dilengkapi manajemen anggota, pembayaran terintegrasi, dan dasbor admin komprehensif.

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
