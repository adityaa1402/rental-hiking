<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing tables if they exist (re-run safe)
        Schema::dropIfExists('dokumen_jaminan');
        Schema::dropIfExists('pengembalian');
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('detail_paket');
        Schema::dropIfExists('paket_sewa');
        Schema::dropIfExists('alat_hiking');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('pengaturan');

        Schema::create('kategori', function (Blueprint $table) {
            $table->unsignedInteger('id_kategori')->autoIncrement();
            $table->string('nama_kategori', 100);
            $table->string('slug', 100)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('icon', 50)->default('bi-compass');
        });

        Schema::create('alat_hiking', function (Blueprint $table) {
            $table->unsignedInteger('id_alat')->autoIncrement();
            $table->unsignedInteger('id_kategori');
            $table->string('nama_alat', 150);
            $table->string('slug', 150)->unique();
            $table->string('merk', 100);
            $table->text('deskripsi');
            $table->text('spesifikasi');
            $table->decimal('harga_per_hari', 12, 2)->default(0);
            $table->unsignedInteger('stok_total')->default(0);
            $table->enum('kondisi', ['Baik', 'Perlu Perawatan', 'Rusak', 'Sedang Dibersihkan'])->default('Baik');
            $table->string('gambar', 255);
            $table->timestamps();
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onUpdate('cascade');
        });

        Schema::create('paket_sewa', function (Blueprint $table) {
            $table->unsignedInteger('id_paket')->autoIncrement();
            $table->string('nama_paket', 150);
            $table->string('slug', 150)->unique();
            $table->text('deskripsi');
            $table->unsignedInteger('kapasitas_orang')->default(2);
            $table->decimal('harga_paket_per_hari', 12, 2)->default(0);
            $table->string('gambar', 255);
            $table->tinyInteger('status_aktif')->default(1);
            $table->timestamps();
        });

        Schema::create('detail_paket', function (Blueprint $table) {
            $table->unsignedInteger('id_detail_paket')->autoIncrement();
            $table->unsignedInteger('id_paket');
            $table->unsignedInteger('id_alat');
            $table->unsignedInteger('jumlah_unit')->default(1);
            $table->foreign('id_paket')->references('id_paket')->on('paket_sewa')->onDelete('cascade');
            $table->foreign('id_alat')->references('id_alat')->on('alat_hiking');
        });

        Schema::create('transaksi', function (Blueprint $table) {
            $table->unsignedInteger('id_transaksi')->autoIncrement();
            $table->string('kode_transaksi', 35)->unique();
            $table->unsignedBigInteger('id_user');
            $table->date('tgl_sewa');
            $table->date('tgl_kembali');
            $table->unsignedInteger('total_hari')->default(1);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('biaya_pengiriman', 12, 2)->default(0);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('denda', 12, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('metode_pengambilan', ['Ambil di Toko', 'Kirim via Kurir'])->default('Ambil di Toko');
            $table->text('alamat_pengiriman')->nullable();
            $table->enum('status_transaksi', ['Pending', 'Disetujui', 'Berjalan', 'Selesai', 'Ditolak', 'Dibatalkan'])->default('Pending');
            $table->text('catatan_admin')->nullable();
            $table->text('catatan_customer')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('id_user')->references('id')->on('users');
        });

        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->unsignedInteger('id_detail')->autoIncrement();
            $table->unsignedInteger('id_transaksi');
            $table->unsignedInteger('id_alat');
            $table->unsignedInteger('jumlah_unit')->default(1);
            $table->decimal('harga_per_hari_snapshot', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('cascade');
            $table->foreign('id_alat')->references('id_alat')->on('alat_hiking');
        });

        Schema::create('pengembalian', function (Blueprint $table) {
            $table->unsignedInteger('id_pengembalian')->autoIncrement();
            $table->unsignedInteger('id_transaksi');
            $table->date('tanggal_pengembalian_aktual');
            $table->string('kondisi_pengembalian', 100)->nullable();
            $table->unsignedInteger('jumlah_baik')->default(0);
            $table->unsignedInteger('jumlah_rusak')->default(0);
            $table->unsignedInteger('jumlah_hilang')->default(0);
            $table->decimal('denda', 12, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('cascade');
        });

        Schema::create('dokumen_jaminan', function (Blueprint $table) {
            $table->unsignedInteger('id_dokumen')->autoIncrement();
            $table->unsignedInteger('id_transaksi');
            $table->string('nama_file_tersimpan', 255);
            $table->string('tipe_dokumen', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('cascade');
        });

        Schema::create('pengaturan', function (Blueprint $table) {
            $table->unsignedInteger('id_pengaturan')->autoIncrement();
            $table->string('nama_toko', 150)->default('HIKETRIP RENTAL');
            $table->string('tagline', 255)->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon', 25)->nullable();
            $table->string('whatsapp', 25)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('jam_operasional', 100)->nullable();
            $table->decimal('biaya_pengiriman_flat', 12, 2)->default(25000);
            $table->decimal('denda_keterlambatan_per_hari', 12, 2)->default(50000);
            $table->text('syarat_ketentuan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_jaminan');
        Schema::dropIfExists('pengembalian');
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('detail_paket');
        Schema::dropIfExists('paket_sewa');
        Schema::dropIfExists('alat_hiking');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('pengaturan');
    }
};

