<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'nama_lengkap')) {
            if (Schema::hasColumn('users', 'name')) {
                Schema::table('users', function (Blueprint $table): void {
                    $table->renameColumn('name', 'nama_lengkap');
                });
            } else {
                Schema::table('users', function (Blueprint $table): void {
                    $table->string('nama_lengkap', 100);
                });
            }
        }

        if (!Schema::hasColumn('users', 'no_hp')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('no_hp', 25)->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'alamat')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->text('alamat')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'alamat')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('alamat');
            });
        }

        if (Schema::hasColumn('users', 'no_hp')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('no_hp');
            });
        }

        if (Schema::hasColumn('users', 'nama_lengkap') && !Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->renameColumn('nama_lengkap', 'name');
            });
        }
    }
};