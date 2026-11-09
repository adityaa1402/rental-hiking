<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('transaksi', 'status_pengiriman')) {
            Schema::table('transaksi', function (Blueprint $table): void {
                $table->string('status_pengiriman', 30)->nullable();
                $table->string('nama_kurir', 100)->nullable();
                $table->string('telepon_kurir', 25)->nullable();
                $table->string('nomor_resi', 100)->nullable();
                $table->timestamp('dikirim_pada')->nullable();
                $table->timestamp('diterima_pada')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('transaksi', 'status_pengiriman')) {
            Schema::table('transaksi', function (Blueprint $table): void {
                $table->dropColumn([
                    'status_pengiriman',
                    'nama_kurir',
                    'telepon_kurir',
                    'nomor_resi',
                    'dikirim_pada',
                    'diterima_pada',
                ]);
            });
        }
    }
};
