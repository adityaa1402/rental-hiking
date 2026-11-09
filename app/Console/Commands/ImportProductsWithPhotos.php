<?php

namespace App\Console\Commands;

use App\Models\AlatHiking;
use App\Models\Kategori;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportProductsWithPhotos extends Command
{
    protected $signature = 'import:products {--fresh} {--test}';
    protected $description = 'Import produk hiking dengan foto secara otomatis';

    public function handle()
    {
        $this->info('🚀 Mulai import produk hiking dengan foto...');

        // Clear existing jika --fresh flag
        if ($this->option('fresh')) {
            if ($this->confirm('Hapus semua produk lama?')) {
                AlatHiking::truncate();
                $this->info('✅ Produk lama dihapus');
            }
        }

        // Pastikan folder barang ada
        $this->ensureFolderExists();

        // Seed kategori
        $this->seedKategori();

        // Seed produk dengan foto
        $this->seedProduk();

        $this->info('✅ Import selesai!');
        $this->info('📊 Database sudah siap dengan foto produk');
    }

    private function ensureFolderExists()
    {
        $path = storage_path('app/public/barang');
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
            $this->info('✅ Folder storage/app/public/barang dibuat');
        }
    }

    private function seedKategori()
    {
        $kategoris = [
            ['nama_kategori' => 'Tenda', 'slug' => 'tenda', 'icon' => 'bi-triangle-half'],
            ['nama_kategori' => 'Sleeping Bag', 'slug' => 'sleeping-bag', 'icon' => 'bi-backpack'],
            ['nama_kategori' => 'Backpack', 'slug' => 'backpack', 'icon' => 'bi-bag-heart'],
            ['nama_kategori' => 'Sepatu Hiking', 'slug' => 'sepatu-hiking', 'icon' => 'bi-shoe'],
            ['nama_kategori' => 'Jacket & Pakaian', 'slug' => 'jacket-pakaian', 'icon' => 'bi-vest'],
            ['nama_kategori' => 'Aksesori', 'slug' => 'aksesori', 'icon' => 'bi-tools'],
        ];

        foreach ($kategoris as $data) {
            Kategori::firstOrCreate(['slug' => $data['slug']], $data);
        }

        $this->info('✅ ' . count($kategoris) . ' kategori siap');
    }

    private function seedProduk()
    {
        $produkList = $this->getProdukData();
        $imported = 0;
        $skipped = 0;

        foreach ($produkList as $data) {
            $kategori = Kategori::where('nama_kategori', $data['kategori'])->first();
            if (!$kategori) continue;

            $exists = AlatHiking::where('merk', $data['merk'])
                ->where('nama_alat', $data['nama_alat'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            $fotoFilename = $this->generatePhotoFilename($data['merk'], $data['nama_alat']);

            AlatHiking::create([
                'id_kategori' => $kategori->id_kategori,
                'nama_alat' => $data['nama_alat'],
                'slug' => Str::slug($data['nama_alat']) . '-' . uniqid(),
                'merk' => $data['merk'],
                'deskripsi' => $data['deskripsi'],
                'spesifikasi' => $data['spesifikasi'],
                'harga_per_hari' => $data['harga_per_hari'],
                'stok_total' => $data['stok_total'],
                'kondisi' => 'Baik',
                'gambar' => $fotoFilename,
            ]);

            $imported++;
            $this->line("✓ {$data['merk']} - {$data['nama_alat']}");
        }

        $this->info("📦 Import selesai: {$imported} produk ditambahkan, {$skipped} sudah ada");
    }

    private function generatePhotoFilename($merk, $namaAlat)
    {
        $filename = Str::slug($merk . '-' . $namaAlat) . '-' . time() . '.jpg';
        
        $filePath = storage_path('app/public/barang/' . $filename);
        
        // Generate dummy image dengan placeholder service
        try {
            $colors = ['FF6B6B', '4ECDC4', '45B7D1', 'FFA07A', '98D8C8', 'F7DC6F'];
            $color = $colors[array_rand($colors)];
            
            $url = "https://via.placeholder.com/600x400/$color?text=" . 
                   urlencode(substr($namaAlat, 0, 25));
            
            $content = @file_get_contents($url, false, stream_context_create([
                'http' => ['timeout' => 5]
            ]));
            
            if ($content && strlen($content) > 100) {
                file_put_contents($filePath, $content);
                return $filename;
            }
        } catch (\Exception $e) {
            // Silent fail, use fallback
        }
        
        // Fallback: create simple colored image
        $this->createSimpleImage($filePath);
        
        return $filename;
    }

    private function createSimpleImage($filePath)
    {
        try {
            if (!function_exists('imagecreatetruecolor')) {
                return;
            }

            $image = imagecreatetruecolor(600, 400);
            $bgColor = imagecolorallocate($image, 200, 200, 200);
            imagefill($image, 0, 0, $bgColor);
            
            $textColor = imagecolorallocate($image, 100, 100, 100);
            imagestring($image, 5, 250, 190, 'Product Image', $textColor);
            
            imagejpeg($image, $filePath, 75);
            imagedestroy($image);
        } catch (\Exception $e) {
            // Ignore errors
        }
    }

    private function getProdukData()
    {
        return [
            // TENDA
            [
                'kategori' => 'Tenda',
                'nama_alat' => 'Tenda Dome Coleman 2 Orang',
                'merk' => 'Coleman',
                'deskripsi' => 'Tenda dome berkualitas tinggi dari Coleman, cocok untuk 2 orang dengan setup cepat.',
                'spesifikasi' => 'Berat: 2.5kg | Material: Polyester 190T | Waterproof: 3000mm | Mudah dipasang',
                'harga_per_hari' => 150000,
                'stok_total' => 5,
            ],
            [
                'kategori' => 'Tenda',
                'nama_alat' => 'Tenda Decathlon 3 Orang',
                'merk' => 'Decathlon',
                'deskripsi' => 'Tenda berkualitas dari Decathlon, kapasitas 3 orang dengan ventilasi baik.',
                'spesifikasi' => 'Berat: 3.2kg | Material: Nylon 210D | Waterproof: 2500mm | Spacious',
                'harga_per_hari' => 180000,
                'stok_total' => 4,
            ],
            [
                'kategori' => 'Tenda',
                'nama_alat' => 'Tenda Mountain Hardwear 4 Musim',
                'merk' => 'Mountain Hardwear',
                'deskripsi' => 'Tenda premium 4 musim untuk kondisi ekstrem dan durabilitas maksimal.',
                'spesifikasi' => 'Berat: 4.5kg | Material: Dyneema | Waterproof: 5000mm | Double Wall',
                'harga_per_hari' => 300000,
                'stok_total' => 2,
            ],

            // SLEEPING BAG
            [
                'kategori' => 'Sleeping Bag',
                'nama_alat' => 'Sleeping Bag Coleman Musim Dingin',
                'merk' => 'Coleman',
                'deskripsi' => 'Kantong tidur hangat dari Coleman, ideal untuk musim dingin dan ketinggian tinggi.',
                'spesifikasi' => 'Temp Rating: -10°C | Berat: 1.8kg | Material: Polyester | Fill: Synthetic',
                'harga_per_hari' => 120000,
                'stok_total' => 6,
            ],
            [
                'kategori' => 'Sleeping Bag',
                'nama_alat' => 'Sleeping Bag Decathlon 3 Musim',
                'merk' => 'Decathlon',
                'deskripsi' => 'Kantong tidur versatile untuk 3 musim dengan harga terjangkau.',
                'spesifikasi' => 'Temp Rating: -5°C | Berat: 1.5kg | Material: Nylon | Fill: Polyester',
                'harga_per_hari' => 90000,
                'stok_total' => 8,
            ],
            [
                'kategori' => 'Sleeping Bag',
                'nama_alat' => 'Sleeping Bag Mountain Hardwear Premium',
                'merk' => 'Mountain Hardwear',
                'deskripsi' => 'Kantong tidur premium dengan teknologi terkini untuk performa maksimal.',
                'spesifikasi' => 'Temp Rating: -15°C | Berat: 2.2kg | Material: Pertex | Fill: 800FP Down',
                'harga_per_hari' => 250000,
                'stok_total' => 3,
            ],

            // BACKPACK
            [
                'kategori' => 'Backpack',
                'nama_alat' => 'Backpack Decathlon 50L',
                'merk' => 'Decathlon',
                'deskripsi' => 'Tas carrier 50 liter dari Decathlon, nyaman untuk trekking multi-hari.',
                'spesifikasi' => 'Kapasitas: 50L | Berat: 1.6kg | Material: Nylon 420D | Waterproof',
                'harga_per_hari' => 100000,
                'stok_total' => 7,
            ],
            [
                'kategori' => 'Backpack',
                'nama_alat' => 'Backpack Osprey Atmos 65L',
                'merk' => 'Osprey',
                'deskripsi' => 'Backpack profesional 65 liter dengan suspension system terbaik di kelasnya.',
                'spesifikasi' => 'Kapasitas: 65L | Berat: 2.1kg | Material: Nylon 500D | Anti-Slip Back Panel',
                'harga_per_hari' => 200000,
                'stok_total' => 3,
            ],
            [
                'kategori' => 'Backpack',
                'nama_alat' => 'Backpack Coleman Daypack 30L',
                'merk' => 'Coleman',
                'deskripsi' => 'Tas harian 30 liter dari Coleman, cocok untuk day trip atau sebagai carry-on.',
                'spesifikasi' => 'Kapasitas: 30L | Berat: 0.9kg | Material: Polyester | Lightweight',
                'harga_per_hari' => 60000,
                'stok_total' => 10,
            ],

            // SEPATU HIKING
            [
                'kategori' => 'Sepatu Hiking',
                'nama_alat' => 'Sepatu Salomon Quest 4D',
                'merk' => 'Salomon',
                'deskripsi' => 'Sepatu trekking premium dari Salomon dengan grip dan support maksimal.',
                'spesifikasi' => 'Ukuran: 40-46 | Material: Leather/Gore-Tex | Weight: 450g | Ankle Support',
                'harga_per_hari' => 80000,
                'stok_total' => 5,
            ],
            [
                'kategori' => 'Sepatu Hiking',
                'nama_alat' => 'Sepatu Decathlon Quechua NH100',
                'merk' => 'Decathlon',
                'deskripsi' => 'Sepatu hiking terjangkau dari Decathlon dengan kualitas handal.',
                'spesifikasi' => 'Ukuran: 36-47 | Material: Suede/Rubber | Weight: 380g | Comfortable Fit',
                'harga_per_hari' => 50000,
                'stok_total' => 8,
            ],
            [
                'kategori' => 'Sepatu Hiking',
                'nama_alat' => 'Sepatu Merrell Moab 2',
                'merk' => 'Merrell',
                'deskripsi' => 'Sepatu trekking versatile dari Merrell untuk berbagai medan.',
                'spesifikasi' => 'Ukuran: 39-48 | Material: Nubuck | Weight: 420g | Vibram Sole',
                'harga_per_hari' => 100000,
                'stok_total' => 4,
            ],

            // JACKET & PAKAIAN
            [
                'kategori' => 'Jacket & Pakaian',
                'nama_alat' => 'Jaket Waterproof The North Face',
                'merk' => 'The North Face',
                'deskripsi' => 'Jaket waterproof premium dari The North Face untuk cuaca ekstrem.',
                'spesifikasi' => 'Material: Gore-Tex | Berat: 380g | Warna: Biru | Breathable & Windproof',
                'harga_per_hari' => 150000,
                'stok_total' => 4,
            ],
            [
                'kategori' => 'Jacket & Pakaian',
                'nama_alat' => 'Jaket Arc\'teryx Insulated',
                'merk' => 'Arc\'teryx',
                'deskripsi' => 'Jaket insulated berkualitas tinggi dari Arc\'teryx untuk performa optimal.',
                'spesifikasi' => 'Material: Proton LT | Berat: 420g | 800FP Down Insulation | Lightweight',
                'harga_per_hari' => 200000,
                'stok_total' => 2,
            ],
            [
                'kategori' => 'Jacket & Pakaian',
                'nama_alat' => 'Base Layer Decathlon Merino',
                'merk' => 'Decathlon',
                'deskripsi' => 'Base layer wool merino dari Decathlon, nyaman dan breathable.',
                'spesifikasi' => 'Material: 100% Merino | Berat: 180g | Temperature: -5 hingga 15°C',
                'harga_per_hari' => 60000,
                'stok_total' => 12,
            ],

            // AKSESORI
            [
                'kategori' => 'Aksesori',
                'nama_alat' => 'GPS Garmin eTrex',
                'merk' => 'Garmin',
                'deskripsi' => 'GPS handheld dari Garmin untuk navigasi akurat di gunung.',
                'spesifikasi' => 'Berat: 185g | Battery: AA x4 | Water Resistant | High Accuracy',
                'harga_per_hari' => 80000,
                'stok_total' => 3,
            ],
            [
                'kategori' => 'Aksesori',
                'nama_alat' => 'Carabiner & Rope Set Mammut',
                'merk' => 'Mammut',
                'deskripsi' => 'Set safety gear profesional dari Mammut untuk climbing dan rescue.',
                'spesifikasi' => 'Material: Steel | Carabiners: 2 pcs | Rope: 30m | Safety Rated',
                'harga_per_hari' => 120000,
                'stok_total' => 2,
            ],
            [
                'kategori' => 'Aksesori',
                'nama_alat' => 'Headlamp Decathlon LED',
                'merk' => 'Decathlon',
                'deskripsi' => 'Lampu kepala LED dari Decathlon, ringan dan terang untuk trekking malam.',
                'spesifikasi' => 'Berat: 60g | Brightness: 250 lumen | Battery: 3xAAA | Waterproof',
                'harga_per_hari' => 40000,
                'stok_total' => 15,
            ],
        ];
    }
}
