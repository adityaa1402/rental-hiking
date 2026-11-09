<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_gallery', function (Blueprint $table) {
            $table->unsignedInteger('id_gallery')->autoIncrement();
            $table->unsignedInteger('id_alat');
            $table->string('gambar', 255);
            $table->string('keterangan', 255)->nullable();
            $table->tinyInteger('urutan')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('id_alat')->references('id_alat')->on('alat_hiking')->onDelete('cascade');
            $table->index('id_alat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_gallery');
    }
};
