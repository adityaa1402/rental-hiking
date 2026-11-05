# TEST CHECKLIST - HIKETRIP RENTAL

## ✅ Persiapan Testing
1. ✅ Jalankan: `php artisan serve`
2. ✅ Buka browser: http://localhost:8000
3. ✅ Database sudah diimport
4. ✅ Cache sudah dibersihkan

## 🧪 Testing Halaman Public (Tanpa Login)

### Home Page
- [ ] http://localhost:8000
- [ ] Hero section dengan gambar gunung muncul
- [ ] About section muncul
- [ ] Testimonial muncul
- [ ] Contact section muncul

### Katalog
- [ ] http://localhost:8000/katalog
- [ ] Daftar barang muncul dengan gambar
- [ ] Filter kategori berfungsi
- [ ] Sorting (terbaru, termurah, termahal) berfungsi
- [ ] Klik barang → masuk ke detail

### Detail Barang
- [ ] Gambar barang muncul
- [ ] Informasi harga, stok, kategori muncul
- [ ] Calculator durasi sewa berfungsi
- [ ] Tombol "Sewa Sekarang" ada

### Paket
- [ ] http://localhost:8000/paket
- [ ] Daftar paket muncul
- [ ] List barang dalam paket muncul
- [ ] Total harga paket benar

### Auth Pages
- [ ] http://localhost:8000/login - form login muncul
- [ ] http://localhost:8000/register - form register muncul

## 🔐 Testing Login & Register

### Register Customer Baru
- [ ] Isi form register
- [ ] Submit → redirect ke login
- [ ] Cek database: user baru masuk dengan role='customer'

### Login as Customer
- [ ] Login dengan user customer
- [ ] Redirect ke /customer/dashboard
- [ ] Navbar menampilkan nama user dan tombol logout

### Login as Admin
- [ ] Login dengan user admin (cek di database)
- [ ] Redirect ke /admin/dashboard
- [ ] Navbar admin muncul

## 👤 Testing Customer Dashboard

### Dashboard Customer
- [ ] http://localhost:8000/customer/dashboard
- [ ] Card statistik muncul (total transaksi, aktif, selesai)
- [ ] Transaksi terbaru muncul (max 5)
- [ ] Semua data sesuai dengan transaksi user

### Transaksi Customer
- [ ] http://localhost:8000/customer/transaksi
- [ ] Daftar semua transaksi user muncul
- [ ] Filter status (Semua, Pending, Disetujui, dst) berfungsi
- [ ] Detail transaksi bisa dilihat
- [ ] Upload bukti transfer berfungsi (untuk status Pending)

### Profil Customer
- [ ] http://localhost:8000/customer/profil
- [ ] Form profil muncul dengan data user
- [ ] Update profil berfungsi
- [ ] Ganti password berfungsi

## 🔧 Testing Admin Dashboard

### Dashboard Admin
- [ ] http://localhost:8000/admin/dashboard
- [ ] ✅ Card statistik:
  - [ ] Total Barang
  - [ ] Transaksi Pending (warna kuning)
  - [ ] Sedang Berjalan (warna biru)
  - [ ] Total Customer
- [ ] ✅ Card pendapatan:
  - [ ] Pendapatan Bulan Ini
  - [ ] Total Pendapatan
  - [ ] Transaksi Selesai
- [ ] Grafik transaksi 6 bulan muncul
- [ ] Transaksi terbaru muncul

### Manajemen Barang
- [ ] http://localhost:8000/admin/barang
- [ ] Daftar barang muncul
- [ ] Tambah barang baru berfungsi (dengan upload gambar)
- [ ] Edit barang berfungsi
- [ ] Hapus barang berfungsi
- [ ] Stok tersedia/disewa dihitung benar

### Manajemen Kategori
- [ ] http://localhost:8000/admin/kategori
- [ ] Daftar kategori muncul
- [ ] Tambah/Edit/Hapus kategori berfungsi

### Manajemen Transaksi
- [ ] http://localhost:8000/admin/transaksi
- [ ] Daftar semua transaksi muncul
- [ ] Filter status berfungsi
- [ ] Detail transaksi lengkap
- [ ] Approve transaksi (Pending → Disetujui)
- [ ] Mulai sewa (Disetujui → Berjalan)
- [ ] Selesaikan sewa (Berjalan → Selesai)
- [ ] Tolak transaksi berfungsi

### Manajemen Customer
- [ ] http://localhost:8000/admin/customer
- [ ] Daftar customer muncul
- [ ] Detail customer dengan riwayat transaksi
- [ ] Block/Unblock customer berfungsi

### Manajemen Paket
- [ ] http://localhost:8000/admin/paket
- [ ] Daftar paket muncul
- [ ] Tambah paket baru dengan pilih barang
- [ ] Edit paket berfungsi
- [ ] Hapus paket berfungsi

### Laporan
- [ ] http://localhost:8000/admin/laporan
- [ ] Laporan transaksi berdasarkan tanggal
- [ ] Export PDF berfungsi
- [ ] Filter periode berfungsi

### Pengaturan
- [ ] http://localhost:8000/admin/pengaturan
- [ ] Update info toko (nama, alamat, kontak)
- [ ] Update kebijakan sewa
- [ ] Perubahan tersimpan

## 🛒 Testing Booking Process (End-to-End)

### Booking Barang Satuan
- [ ] Login as customer
- [ ] Pilih barang dari katalog
- [ ] Klik "Sewa Sekarang"
- [ ] Isi form booking (tanggal, durasi, jumlah unit)
- [ ] Upload KTP
- [ ] Submit → redirect ke transaksi customer
- [ ] Transaksi masuk dengan status "Pending"
- [ ] Cek admin → transaksi pending bertambah

### Booking Paket
- [ ] Login as customer
- [ ] Pilih paket dari halaman paket
- [ ] Isi form booking paket
- [ ] Upload KTP
- [ ] Submit → transaksi masuk
- [ ] Detail transaksi menampilkan semua barang dalam paket

### Upload Bukti Transfer
- [ ] Customer upload bukti transfer
- [ ] File tersimpan di public/uploads/bukti
- [ ] Status tetap Pending (menunggu admin approve)

### Approve & Process (Admin)
- [ ] Admin lihat transaksi pending
- [ ] Verifikasi bukti transfer & dokumen
- [ ] Approve → status jadi "Disetujui"
- [ ] Mulai sewa → status jadi "Berjalan"
- [ ] Stok barang berkurang
- [ ] Selesaikan → status jadi "Selesai"
- [ ] Stok barang kembali

## ❌ Testing Error Handling

- [ ] Akses halaman admin tanpa login → redirect ke login
- [ ] Customer coba akses halaman admin → forbidden/redirect
- [ ] Admin coba akses halaman customer → redirect
- [ ] Upload file bukan gambar → error validation
- [ ] Booking dengan stok tidak cukup → error
- [ ] Form kosong submit → error validation

## 📊 Summary

Total Tests: **~80 test cases**

### Status Fixes:
✅ Variable naming di semua controllers sudah konsisten
✅ Primary keys sudah sesuai (id_user, id_alat, dll)
✅ Relationship aliases sudah ditambahkan
✅ Session driver = file (tidak perlu tabel sessions)
✅ Cache driver = file (tidak perlu tabel cache)
✅ Admin Dashboard stats lengkap termasuk pendapatan_bulan

### Quick Commands:
```bash
# Start server
php artisan serve

# Clear cache jika ada error
php artisan view:clear
php artisan config:clear
php artisan route:clear

# Check routes
php artisan route:list

# Check database connection
php artisan tinker
>>> \App\Models\User::count()
```

---

## 🎯 PRIORITY TESTING (Test ini dulu!)

1. **Admin Dashboard** - Sudah diperbaiki hari ini
2. **Customer Dashboard** - Sudah diperbaiki kemarin  
3. **Katalog & Detail Barang** - Core feature
4. **Booking Process** - End-to-end flow
5. **Admin Transaksi Management** - Approve/Reject flow

Kalau 5 ini OK, sisanya pasti OK! 🚀
