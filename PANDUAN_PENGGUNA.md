# Panduan Penggunaan dan Alur Rental

Panduan ini menjelaskan persiapan aplikasi dan alur rental HIKETRIP dari melihat katalog sampai barang selesai diperiksa saat dikembalikan.

## 1. Menyiapkan aplikasi

### Prasyarat

- PHP 8.2 atau lebih baru dan Composer
- MySQL/MariaDB (Laragon dapat menjalankan layanan MySQL)
- Node.js dan npm

### Instalasi lokal

Jalankan perintah dari folder proyek:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
npm run build
php artisan storage:link
```

Buat basis data kosong di MySQL, lalu sesuaikan pengaturan `.env`:

```dotenv
APP_NAME="HIKETRIP Rental"
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_hiking
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi dan server:

```powershell
php artisan migrate
php artisan serve
```

Buka `http://127.0.0.1:8000`. Untuk instalasi baru yang membutuhkan produk dan akun demo, jalankan `php artisan db:seed` satu kali pada basis data kosong. Seeder akun demo menggunakan `admin@hiketrip.local` dan `customer@hiketrip.local`, keduanya dengan kata sandi `password`. Ganti/hapus akun tersebut sebelum aplikasi dipakai sungguhan. Jangan menjalankan seeder akun demo pada basis data yang sudah memiliki akun dengan email yang sama.

Unggahan bukti pembayaran dan dokumen jaminan disimpan pada storage publik aplikasi. Jika gambar tidak tampil, pastikan `php artisan storage:link` sudah dijalankan dan folder `storage` dapat ditulis oleh PHP.

## 2. Alur pelanggan

1. **Lihat katalog atau paket.** Harga barang ditampilkan per hari. Ketersediaan mengikuti stok layak sewa dan tanggal yang sudah disetujui/berjalan.
2. **Daftar atau masuk sebagai pelanggan.** Booking hanya dilakukan setelah masuk.
3. **Ajukan booking.** Pilih satu barang atau satu paket, tanggal mulai, tanggal kembali (harus setelah tanggal mulai), jumlah unit, metode ambil/kirim, dan unggah dokumen jaminan. Alamat wajib diisi untuk pengiriman kurir. Periksa total dan setujui syarat sebelum mengirim.
4. **Tunggu pemeriksaan admin.** Status `Pending` berarti belum disetujui. Stok diperiksa lagi oleh admin saat persetujuan; pengajuan yang masih pending belum memblokir stok.
5. **Bayar setelah disetujui.** Untuk transfer bank atau e-wallet, unggah bukti pembayaran. Pilihan tunai hanya tersedia untuk pengambilan di toko dan dibayar ketika mengambil barang. Bukti online menunggu verifikasi admin; jika ditolak, baca alasannya lalu unggah bukti yang benar. Setelah pembayaran dikonfirmasi, form pembayaran tidak dapat dikirim ulang.
6. **Ambil atau lacak pengiriman.**
   - Pengambilan di toko: admin menandai sewa berjalan ketika barang benar-benar diserahkan.
   - Kurir: admin mengisi data kurir dan memperbarui status `Disiapkan` → `Dalam Perjalanan` → `Terkirim`. Masa sewa baru berjalan setelah status terkirim.
7. **Kembalikan barang.** Serahkan seluruh barang kepada petugas. Admin mencatat tanggal penerimaan sebenarnya, kondisi, dan jumlah baik/rusak/hilang untuk setiap jenis barang. Jumlah tersebut harus sama dengan jumlah yang disewa.
8. **Selesai.** Setelah pemeriksaan disimpan, transaksi menjadi `Selesai`; pelanggan dapat melihat tanggal, kondisi, rincian barang, dan denda keterlambatan bila ada.

Biaya sewa dihitung berdasarkan harga per hari, jumlah unit, periode sewa, diskon paket bila berlaku, dan biaya pengiriman. Tanggal kembali adalah batas sewa; denda dihitung dari hari setelah tanggal tersebut sampai tanggal barang benar-benar diterima kembali, sesuai tarif pada pengaturan toko.

## 3. Alur admin

1. Masuk ke `/login` dengan akun ber-role admin.
2. Buka **Transaksi**, lalu periksa periode, rincian barang, dokumen jaminan, metode pengambilan, dan alamat pelanggan.
3. Setujui atau tolak pengajuan pending. Persetujuan memeriksa stok layak sewa secara transaksional agar dua admin tidak menyetujui stok yang sama.
4. Buka **Pembayaran** untuk memeriksa bukti transfer/e-wallet atau mencatat pembayaran tunai yang diterima di toko. Pembayaran hanya dapat dikonfirmasi untuk transaksi yang sudah disetujui.
5. Lakukan serah-terima toko atau jalankan tahapan kurir. Untuk pengambilan di toko, transaksi hanya dapat dimulai setelah pembayaran dikonfirmasi dan tanggal mulai sewa tiba.
6. Saat barang kembali, buka detail transaksi → **Proses Pengembalian Barang**. Catat tanggal aktual (bukan tanggal rencana bila berbeda), kondisi, lalu rincian baik/rusak/hilang per barang. Sistem menolak jumlah yang tidak cocok, tanggal masa depan, tanggal sebelum mulai sewa, dan pengembalian sebelum pembayaran/serah-terima valid.
7. Simpan pemeriksaan untuk menutup transaksi. Barang rusak masuk ke stok tidak layak sewa; barang hilang mengurangi stok fisik. Setelah barang rusak diperbaiki, buka **Barang → Edit** dan kurangi jumlah “Unit Rusak / Tidak Siap Sewa” agar dapat disewakan lagi.

Daftar pengembalian terpisah tidak digunakan; proses dan riwayat pengembalian berada pada detail transaksi.

## 4. Arti status

| Status | Arti |
|---|---|
| `Pending` | Pengajuan menunggu pemeriksaan admin. |
| `Disetujui` | Pengajuan disetujui; pelanggan perlu menyelesaikan pembayaran. |
| `Berjalan` | Barang telah diserahterimakan atau pengiriman sudah ditandai terkirim. |
| `Selesai` | Barang sudah diperiksa saat kembali dan transaksi ditutup. |
| `Ditolak` | Pengajuan atau pembayaran ditolak; lihat catatan admin. |
| `Dibatalkan` | Pelanggan membatalkan pengajuan yang masih pending. |

Status pembayaran terpisah dari status transaksi: `Pending`, `Confirmed`, atau `Rejected`. Pembayaran `Pending` belum berarti uang sudah diterima/terverifikasi.

## 5. Perbaikan masalah umum

- **Perubahan tabel belum terlihat:** jalankan `php artisan migrate`.
- **Foto/berkas tidak dapat dibuka:** jalankan `php artisan storage:link`, periksa file di `storage/app/public`, lalu pastikan server web dapat mengakses folder `storage`.
- **Halaman lama atau error setelah perubahan konfigurasi:** jalankan `php artisan config:clear` dan `php artisan view:clear`.
- **Stok tidak cukup:** cek jadwal transaksi berstatus disetujui/berjalan, kondisi barang, dan jumlah unit rusak yang belum diperbaiki. Persetujuan admin tetap menjadi pemeriksaan akhir stok.
- **Form pengembalian ditolak:** tanggal aktual harus hari ini atau sebelumnya dan tidak sebelum tanggal mulai sewa; untuk setiap barang, jumlah baik + rusak + hilang wajib sama dengan jumlah yang disewa.
- **Pengiriman belum dapat diproses:** pastikan transaksi disetujui dan pembayaran berstatus `Confirmed`; tahap kurir harus diurutkan.

## 6. Perintah pengembangan

```powershell
php artisan test
php artisan route:list
npm run build
```
