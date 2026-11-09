<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Tenda',
                'slug' => 'tenda',
                'deskripsi' => 'Tenda camping dan hiking untuk berbagai kapasitas dan musim',
                'icon' => 'bi-triangle-half',
            ],
            [
                'nama_kategori' => 'Sleeping Bag',
                'slug' => 'sleeping-bag',
                'deskripsi' => 'Kantong tidur berkualitas untuk kenyamanan maksimal di gunung',
                'icon' => 'bi-backpack',
            ],
            [
                'nama_kategori' => 'Backpack',
                'slug' => 'backpack',
                'deskripsi' => 'Tas carrier untuk membawa kebutuhan hiking Anda',
                'icon' => 'bi-bag-heart',
            ],
            [
                'nama_kategori' => 'Sepatu Hiking',
                'slug' => 'sepatu-hiking',
                'deskripsi' => 'Sepatu trekking dengan grip dan support terbaik',
                'icon' => 'bi-shoe',
            ],
            [
                'nama_kategori' => 'Jacket & Pakaian',
                'slug' => 'jacket-pakaian',
                'deskripsi' => 'Jaket waterproof dan pakaian khusus hiking',
                'icon' => 'bi-vest',
            ],
            [
                'nama_kategori' => 'Aksesori',
                'slug' => 'aksesori',
                'deskripsi' => 'Aksesori pendukung seperti GPS, carabiners, ropes',
                'icon' => 'bi-tools',
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::firstOrCreate(
                ['slug' => $kategori['slug']],
                $kategori
            );
        }
    }
}
