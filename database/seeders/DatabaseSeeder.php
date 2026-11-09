<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed kategori barang
        $this->call(KategoriSeeder::class);

        // Seed barang hiking
        $this->call(AlatHikingSeeder::class);

        // Assign photos to existing products that still use placeholders
        $this->call(ProductPhotoSeeder::class);

        // Seed barang gallery images
        $this->call(BarangGallerySeeder::class);

        // Seed an active discounted sample hiking package
        $this->call(PaketHematSeeder::class);

        // Seed test users
        User::factory()->create([
            'nama_lengkap' => 'Admin User',
            'email' => 'admin@hiketrip.local',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'nama_lengkap' => 'Test Customer',
            'email' => 'customer@hiketrip.local',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);
    }
}
