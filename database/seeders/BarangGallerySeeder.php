<?php

namespace Database\Seeders;

use App\Models\AlatHiking;
use App\Models\BarangGallery;
use Illuminate\Database\Seeder;

class BarangGallerySeeder extends Seeder
{
    public function run(): void
    {
        // Product names and their image names
        $products = [
            'Tenda Dome 2 Orang' => 'product-1.png',
            'Tenda Tunnel 4 Orang' => 'product-2.png',
            'Tenda Ultralight 1 Orang' => 'product-3.png',
            'Sleeping Bag Summer 10°C' => 'product-4.png',
            'Sleeping Bag Winter -5°C' => 'product-5.png',
            'Backpack 20L Daypack' => 'product-6.png',
            'Backpack 40L Hiking' => 'product-7.png',
            'Backpack 60L Trekking' => 'product-8.png',
            'Sepatu Hiking Waterproof' => 'product-9.png',
            'Sepatu Trekking Lightweight' => 'product-10.png',
            'Jacket Waterproof Outdoor' => 'product-11.png',
            'Thermal Base Layer' => 'product-12.png',
            'Kompas & Map' => 'product-13.png',
            'Headlamp LED 500 Lumen' => 'product-14.png',
            'Carabiner & Rope Set' => 'product-15.png',
            'Matras Tidur (Sleeping Pad)' => 'product-16.png',
        ];

        $gallery_data = [];
        $urutan = 1;

        foreach ($products as $productName => $imageName) {
            // Find the product by name
            $alat = AlatHiking::where('nama_alat', $productName)->first();

            if ($alat) {
                $gallery_data[] = [
                    'id_alat' => $alat->id_alat,
                    'gambar' => 'assets/images/' . $imageName,  // Updated path
                    'keterangan' => 'Foto produk - ' . $productName,
                    'urutan' => 1, // First image
                    'created_at' => now(),
                ];

                // Add some additional gallery images (variations)
                if ($urutan <= 8) { // Add extra images for first 8 products
                    $gallery_data[] = [
                        'id_alat' => $alat->id_alat,
                        'gambar' => 'assets/images/' . $imageName,  // Updated path
                        'keterangan' => 'Foto produk detail - ' . $productName,
                        'urutan' => 2, // Second image
                        'created_at' => now(),
                    ];
                }
            }

            $urutan++;
        }

        // Insert all gallery data
        if (!empty($gallery_data)) {
            foreach ($gallery_data as $item) {
                BarangGallery::create($item);
            }
            $this->command->info('BarangGallerySeeder: ' . count($gallery_data) . ' gallery entries berhasil ditambahkan');
        } else {
            $this->command->warn('BarangGallerySeeder: Tidak ada produk ditemukan untuk ditambahkan gallery');
        }
    }
}
