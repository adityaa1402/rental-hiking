<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesan_kontak', function (Blueprint $table) {
            $table->foreignId('id_user')
                ->nullable()
                ->after('id_pesan')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pesan_kontak', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_user');
        });
    }
};
