# HIKETRIP Rental

Aplikasi penyewaan perlengkapan hiking berbasis Laravel 12. Pengunjung dapat melihat katalog dan paket; pelanggan dapat mengajukan sewa, membayar, melacak pengiriman, dan melihat hasil pengembalian. Admin mengelola produk, transaksi, pembayaran, pengiriman, inventaris, dan pemeriksaan barang kembali.

## Menjalankan secara lokal

1. Siapkan PHP 8.2+, Composer, Node.js/npm, dan MySQL (Laragon dapat digunakan).
2. Jalankan `composer install`, salin `.env.example` menjadi `.env`, lalu isi koneksi database MySQL.
3. Jalankan `php artisan key:generate`, `php artisan migrate`, `php artisan storage:link`, `npm install`, dan `npm run build`.
4. Jalankan `php artisan serve`, lalu buka `http://127.0.0.1:8000`.

Untuk basis data kosong, `php artisan db:seed` mengisi data contoh, termasuk akun demo. Jangan gunakan akun demo di produksi. Rincian setup, alur pelanggan/admin, dan pemeriksaan pengembalian ada di [Panduan Penggunaan dan Alur Rental](./PANDUAN_PENGGUNA.md).

## Pemeriksaan

```bash
php artisan test
php artisan route:list
```

## Dokumentasi

- [Product Requirements Document (PRD)](./docs/PRD_HIKETRIP_RENTAL.md) — kebutuhan, ruang lingkup, alur, aturan bisnis, data, dan pengujian berdasarkan implementasi repository.
- [Panduan Penggunaan dan Alur Rental](./PANDUAN_PENGGUNA.md) — setup, alur transaksi, dan troubleshooting.
- [Kredit Foto Produk](./PHOTO_CREDITS.md) — sumber foto katalog.
