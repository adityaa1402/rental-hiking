<?php

namespace Database\Seeders;

use App\Models\AlatHiking;
use Illuminate\Database\Seeder;

class ProductPhotoSeeder extends Seeder
{
    private const PRODUCT_IMAGES = [
        'Tenda Dome 2 Orang' => ['tenda-dome-default.jpg', 'tenda-dome-decathlon.jpg'],
        'Tenda Tunnel 4 Orang' => ['tenda-tunnel-default.jpg', 'tenda-tunnel-coleman.jpg'],
        'Tenda Ultralight 1 Orang' => ['tenda-ultralight-default.jpg', 'tenda-ultralight-nemo.jpg'],
        'Sleeping Bag Summer 10°C' => ['sleeping-bag-summer-default.jpg', 'sleeping-bag-summer.jpg'],
        'Sleeping Bag Winter -5°C' => ['sleeping-bag-winter-default.jpg', 'sleeping-bag.jpg'],
        'Backpack 40L Hiking' => ['backpack-40l-default.jpg', 'backpack-40l-osprey.jpg'],
        'Backpack 60L Trekking' => ['backpack-60l-default.jpg', 'backpack-60l-deuter.jpg'],
        'Backpack 20L Daypack' => ['backpack-20l-default.jpg', 'backpack-20l-patagonia.jpg'],
        'Sepatu Hiking Waterproof' => ['sepatu-hiking-waterproof-default.jpg', 'sepatu-waterproof-salomon.jpg'],
        'Sepatu Trekking Lightweight' => ['sepatu-trekking-lightweight-default.jpg', 'sepatu-trekking-merrell.jpg'],
        'Jacket Waterproof Outdoor' => ['jacket-waterproof-default.jpg', 'jacket-waterproof-arcteryx.jpg'],
        'Thermal Base Layer' => ['thermal-base-layer-default.jpg', 'thermal-base-layer-decathlon.jpg'],
        'Kompas & Map' => ['kompas-map-default.jpg', 'kompas-map-suunto.jpg'],
        'Headlamp LED 500 Lumen' => ['headlamp-led-default.jpg', 'headlamp-petzl.jpg'],
        'Carabiner & Rope Set' => ['carabiner-rope-default.jpg', 'carabiner-rope-black-diamond.jpg'],
        'Matras Tidur (Sleeping Pad)' => ['matras-tidur-default.jpg', 'matras-sleeping-pad.jpg'],
    ];

    private const LEGACY_IMAGES = [
        'Sleeping Bag Summer 10°C' => ['matras-sleeping-pad.jpg', 'sleeping-bag-summer.jpg'],
        'Sleeping Bag Winter -5°C' => ['matras-sleeping-pad.jpg', 'sleeping-bag.jpg'],
    ];

    public function run(): void
    {
        $updated = 0;

        foreach (self::PRODUCT_IMAGES as $productName => [$placeholder, $photo]) {
            $updated += AlatHiking::where('nama_alat', $productName)
                ->where('gambar', $placeholder)
                ->update(['gambar' => $photo]);
        }

        foreach (self::LEGACY_IMAGES as $productName => [$previousPhoto, $photo]) {
            $updated += AlatHiking::where('nama_alat', $productName)
                ->where('gambar', $previousPhoto)
                ->update(['gambar' => $photo]);
        }

        $this->command?->info("ProductPhotoSeeder: {$updated} foto placeholder diperbarui");
    }
}