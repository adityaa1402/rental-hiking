<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('alat_hiking', 'stok_rusak')) {
            Schema::table('alat_hiking', function (Blueprint $table): void {
                $table->unsignedInteger('stok_rusak')->default(0)->after('stok_total');
            });
        }

        if (!Schema::hasTable('detail_pengembalian')) {
            Schema::create('detail_pengembalian', function (Blueprint $table): void {
                $table->unsignedInteger('id_detail_pengembalian')->autoIncrement();
                $table->unsignedInteger('id_pengembalian');
                $table->unsignedInteger('id_alat');
                $table->unsignedInteger('jumlah_baik')->default(0);
                $table->unsignedInteger('jumlah_rusak')->default(0);
                $table->unsignedInteger('jumlah_hilang')->default(0);
                $table->unique(['id_pengembalian', 'id_alat']);
                $table->foreign('id_pengembalian')
                    ->references('id_pengembalian')
                    ->on('pengembalian')
                    ->onDelete('cascade');
                $table->foreign('id_alat')
                    ->references('id_alat')
                    ->on('alat_hiking');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');

        if (Schema::hasColumn('alat_hiking', 'stok_rusak')) {
            Schema::table('alat_hiking', function (Blueprint $table): void {
                $table->dropColumn('stok_rusak');
            });
        }
    }
};
