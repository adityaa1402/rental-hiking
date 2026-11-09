<?php

namespace Database\Seeders;

use App\Models\AlatHiking;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AlatHikingSeeder extends Seeder
{
    public function run(): void
    {
        // Get categories
        $tenda = Kategori::where('slug', 'tenda')->first();
        $sleepingBag = Kategori::where('slug', 'sleeping-bag')->first();
        $backpack = Kategori::where('slug', 'backpack')->first();
        $sepatu = Kategori::where('slug', 'sepatu-hiking')->first();
        $jacket = Kategori::where('slug', 'jacket-pakaian')->first();
        $aksesori = Kategori::where('slug', 'aksesori')->first();

        $data = [
            // TENDA
            [
                'id_kategori' => $tenda->id_kategori,
                'nama_alat' => 'Tenda Dome 2 Orang',
                'slug' => Str::slug('Tenda Dome 2 Orang') . '-' . time(),
                'merk' => 'Decathlon',
                'deskripsi' => 'Tenda camping dome berkualitas tinggi untuk 2 orang. Desain simple namun kokoh dengan tiang aluminium. Tahan angin dan cocok untuk pemula.',
                'spesifikasi' => 'Kapasitas: 2 orang
Bahan: Poliester + PE
Tinggi: 120 cm
Berat: 1.8 kg
Waterproof: 2000mm
Luas: 220 x 120 cm',
                'harga_per_hari' => 75000,
                'stok_total' => 5,
                'kondisi' => 'Baik',
                'gambar' => 'tenda-dome-decathlon.jpg',
            ],
            [
                'id_kategori' => $tenda->id_kategori,
                'nama_alat' => 'Tenda Tunnel 4 Orang',
                'slug' => Str::slug('Tenda Tunnel 4 Orang') . '-' . time(),
                'merk' => 'Coleman',
                'deskripsi' => 'Tenda tunnel dengan kapasitas 4 orang. Desain memanjang membuat ruang dalam lebih luas. Ideal untuk keluarga atau grup kecil.',
                'spesifikasi' => 'Kapasitas: 4 orang
Bahan: Polyester 185T
Tinggi: 140 cm
Berat: 3.2 kg
Waterproof: 3000mm
Luas: 320 x 220 cm',
                'harga_per_hari' => 120000,
                'stok_total' => 3,
                'kondisi' => 'Baik',
                'gambar' => 'tenda-tunnel-coleman.jpg',
            ],
            [
                'id_kategori' => $tenda->id_kategori,
                'nama_alat' => 'Tenda Ultralight 1 Orang',
                'slug' => Str::slug('Tenda Ultralight 1 Orang') . '-' . time(),
                'merk' => 'Nemo',
                'deskripsi' => 'Tenda ultra-ringan untuk solo traveler. Sempurna untuk pendaki profesional yang mengejar berat minimal. Mudah dibawa untuk ekspedisi jarak jauh.',
                'spesifikasi' => 'Kapasitas: 1 orang
Bahan: Dyneema
Berat: 0.8 kg
Waterproof: 3000mm
Luas: 230 x 100 cm
Tahan angin: Excellent',
                'harga_per_hari' => 100000,
                'stok_total' => 4,
                'kondisi' => 'Baik',
                'gambar' => 'tenda-ultralight-nemo.jpg',
            ],

            // SLEEPING BAG
            [
                'id_kategori' => $sleepingBag->id_kategori,
                'nama_alat' => 'Sleeping Bag Summer 10°C',
                'slug' => Str::slug('Sleeping Bag Summer 10C') . '-' . time(),
                'merk' => 'Mountain Hardwear',
                'deskripsi' => 'Sleeping bag musim panas dengan temperature rating 10°C. Nyaman dan ringkas untuk hiking musim panas di dataran tinggi.',
                'spesifikasi' => 'Temperature Rating: 10°C
Bahan: Hollow Fiber
Lebar: 90 cm
Panjang: 220 cm
Berat: 1.2 kg
Kompresibel: Ya',
                'harga_per_hari' => 40000,
                'stok_total' => 8,
                'kondisi' => 'Baik',
                'gambar' => 'sleeping-bag-summer.jpg',
            ],
            [
                'id_kategori' => $sleepingBag->id_kategori,
                'nama_alat' => 'Sleeping Bag Winter -5°C',
                'slug' => Str::slug('Sleeping Bag Winter -5C') . '-' . time(),
                'merk' => 'The North Face',
                'deskripsi' => 'Sleeping bag winter dengan fill down untuk kehangatan maksimal. Cocok untuk pendakian gunung tinggi dan musim dingin.',
                'spesifikasi' => 'Temperature Rating: -5°C
Bahan: 800 Fill Down
Lebar: 95 cm
Panjang: 230 cm
Berat: 1.8 kg
Kompresibel: Ya',
                'harga_per_hari' => 60000,
                'stok_total' => 5,
                'kondisi' => 'Baik',
                'gambar' => 'sleeping-bag.jpg',
            ],

            // BACKPACK
            [
                'id_kategori' => $backpack->id_kategori,
                'nama_alat' => 'Backpack 40L Hiking',
                'slug' => Str::slug('Backpack 40L Hiking') . '-' . time(),
                'merk' => 'Osprey',
                'deskripsi' => 'Tas carrier 40 liter ideal untuk hiking 2-3 hari. Ergonomis dengan hip belt untuk distribusi beban. Support sempurna untuk punggung.',
                'spesifikasi' => 'Kapasitas: 40 liter
Bahan: Ballistic Nylon
Tinggi: 60 cm
Lebar: 32 cm
Kedalaman: 25 cm
Berat: 1.8 kg',
                'harga_per_hari' => 55000,
                'stok_total' => 6,
                'kondisi' => 'Baik',
                'gambar' => 'backpack-40l-osprey.jpg',
            ],
            [
                'id_kategori' => $backpack->id_kategori,
                'nama_alat' => 'Backpack 60L Trekking',
                'slug' => Str::slug('Backpack 60L Trekking') . '-' . time(),
                'merk' => 'Deuter',
                'deskripsi' => 'Tas carrier besar 60 liter untuk pendakian multi-hari. Dilengkapi rain cover dan kompartemen terpisah untuk equipments.',
                'spesifikasi' => 'Kapasitas: 60 liter
Bahan: Polyamide
Tinggi: 70 cm
Lebar: 35 cm
Kedalaman: 28 cm
Berat: 2.3 kg',
                'harga_per_hari' => 75000,
                'stok_total' => 4,
                'kondisi' => 'Baik',
                'gambar' => 'backpack-60l-deuter.jpg',
            ],
            [
                'id_kategori' => $backpack->id_kategori,
                'nama_alat' => 'Backpack 20L Daypack',
                'slug' => Str::slug('Backpack 20L Daypack') . '-' . time(),
                'merk' => 'Patagonia',
                'deskripsi' => 'Tas kecil 20 liter untuk day hiking. Ringan dan praktis untuk membawa kebutuhan sehari-hari.',
                'spesifikasi' => 'Kapasitas: 20 liter
Bahan: Recycled Nylon
Tinggi: 45 cm
Lebar: 27 cm
Kedalaman: 20 cm
Berat: 0.8 kg',
                'harga_per_hari' => 30000,
                'stok_total' => 10,
                'kondisi' => 'Baik',
                'gambar' => 'backpack-20l-patagonia.jpg',
            ],

            // SEPATU HIKING
            [
                'id_kategori' => $sepatu->id_kategori,
                'nama_alat' => 'Sepatu Hiking Waterproof',
                'slug' => Str::slug('Sepatu Hiking Waterproof') . '-' . time(),
                'merk' => 'Salomon',
                'deskripsi' => 'Sepatu hiking waterproof dengan grip terbaik. Ankle support untuk stabilitas di medan berat. Sol anti-slip dan tahan lama.',
                'spesifikasi' => 'Bahan: Mesh + Kulit
Waterproof: Ya (Gore-Tex)
Berat: 380g/pasang
Support: Ankle
Ukuran: 38-45 (mixed)
Sol: Vibram',
                'harga_per_hari' => 45000,
                'stok_total' => 12,
                'kondisi' => 'Baik',
                'gambar' => 'sepatu-waterproof-salomon.jpg',
            ],
            [
                'id_kategori' => $sepatu->id_kategori,
                'nama_alat' => 'Sepatu Trekking Lightweight',
                'slug' => Str::slug('Sepatu Trekking Lightweight') . '-' . time(),
                'merk' => 'Merrell',
                'deskripsi' => 'Sepatu trekking ringan untuk hiking medium. Breathable dan fleksibel untuk kenyamanan maksimal seharian.',
                'spesifikasi' => 'Bahan: Breathable Mesh
Waterproof: Tidak
Berat: 320g/pasang
Support: Ankle Medium
Ukuran: 38-45 (mixed)
Sol: Kontakt',
                'harga_per_hari' => 35000,
                'stok_total' => 8,
                'kondisi' => 'Baik',
                'gambar' => 'sepatu-trekking-merrell.jpg',
            ],

            // JACKET
            [
                'id_kategori' => $jacket->id_kategori,
                'nama_alat' => 'Jacket Waterproof Outdoor',
                'slug' => Str::slug('Jacket Waterproof Outdoor') . '-' . time(),
                'merk' => 'Arc\'teryx',
                'deskripsi' => 'Jaket outdoor waterproof dan breathable. Tahan cuaca ekstrem dengan desain minimalis dan ringan.',
                'spesifikasi' => 'Bahan: Gore-Tex
Waterproof: 10,000mm
Breathable: 10,000g/m2/24h
Berat: 420g
Ukuran: S-XL (mixed)',
                'harga_per_hari' => 60000,
                'stok_total' => 6,
                'kondisi' => 'Baik',
                'gambar' => 'jacket-waterproof-arcteryx.jpg',
            ],
            [
                'id_kategori' => $jacket->id_kategori,
                'nama_alat' => 'Thermal Base Layer',
                'slug' => Str::slug('Thermal Base Layer') . '-' . time(),
                'merk' => 'Decathlon',
                'deskripsi' => 'Pakaian thermal base layer untuk kehangatan di ketinggian. Moisture-wicking dan anti-bau.',
                'spesifikasi' => 'Bahan: Merino Wool Blend
Moisture-Wicking: Ya
Berat: 200g
Ukuran: XS-XXL (mixed)
Warna: Black, Navy',
                'harga_per_hari' => 25000,
                'stok_total' => 15,
                'kondisi' => 'Baik',
                'gambar' => 'thermal-base-layer-decathlon.jpg',
            ],

            // AKSESORI
            [
                'id_kategori' => $aksesori->id_kategori,
                'nama_alat' => 'Kompas & Map',
                'slug' => Str::slug('Kompas & Map') . '-' . time(),
                'merk' => 'Suunto',
                'deskripsi' => 'Kompas profesional dengan map untuk navigasi outdoor. Akurat dan mudah digunakan.',
                'spesifikasi' => 'Tipe: Liquid Damped
Akurasi: ±2°
Map Scale: 1:50000
Berat: 45g
Material: Plastic + Metal',
                'harga_per_hari' => 15000,
                'stok_total' => 10,
                'kondisi' => 'Baik',
                'gambar' => 'kompas-map-suunto.jpg',
            ],
            [
                'id_kategori' => $aksesori->id_kategori,
                'nama_alat' => 'Headlamp LED 500 Lumen',
                'slug' => Str::slug('Headlamp LED 500 Lumen') . '-' . time(),
                'merk' => 'Petzl',
                'deskripsi' => 'Lampu kepala LED dengan 500 lumen. Waterproof dan baterai tahan lama untuk aktivitas malam.',
                'spesifikasi' => 'Lumen: 500
Tipe Lampu: LED
Waterproof: IPX4
Baterai: 3 AAA (tahan 50 jam)
Berat: 88g',
                'harga_per_hari' => 20000,
                'stok_total' => 8,
                'kondisi' => 'Baik',
                'gambar' => 'headlamp-petzl.jpg',
            ],
            [
                'id_kategori' => $aksesori->id_kategori,
                'nama_alat' => 'Carabiner & Rope Set',
                'slug' => Str::slug('Carabiner & Rope Set') . '-' . time(),
                'merk' => 'Black Diamond',
                'deskripsi' => 'Carabiner dan tali untuk keselamatan dan setup camp. Set lengkap untuk pendakian dasar.',
                'spesifikasi' => 'Carabiner: 3 unit (8mm)
Tali: 20 meter 6mm
Material: Aluminum + Nylon
Beban: 22kN
Berat: 500g',
                'harga_per_hari' => 30000,
                'stok_total' => 5,
                'kondisi' => 'Baik',
                'gambar' => 'carabiner-rope-black-diamond.jpg',
            ],
            [
                'id_kategori' => $aksesori->id_kategori,
                'nama_alat' => 'Matras Tidur (Sleeping Pad)',
                'slug' => Str::slug('Matras Tidur Sleeping Pad') . '-' . time(),
                'merk' => 'Therm-a-Rest',
                'deskripsi' => 'Matras tidur self-inflating untuk kenyamanan dan insulasi. Ringan dan kompak.',
                'spesifikasi' => 'Tipe: Self-Inflating
Lebar: 51 cm
Panjang: 183 cm
Tebal: 3.8 cm
Berat: 570g
R-Value: 2.6',
                'harga_per_hari' => 25000,
                'stok_total' => 7,
                'kondisi' => 'Baik',
                'gambar' => 'matras-sleeping-pad.jpg',
            ],
        ];

        foreach ($data as $item) {
            $item['slug'] = Str::slug($item['nama_alat']);
            AlatHiking::updateOrCreate(['slug' => $item['slug']], $item);
        }

        $this->command->info('AlatHikingSeeder: ' . count($data) . ' barang berhasil ditambahkan');
    }
}
