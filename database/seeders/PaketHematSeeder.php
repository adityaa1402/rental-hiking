<?php

namespace Database\Seeders;

use App\Models\AlatHiking;
use App\Models\DetailPaket;
use App\Models\PaketSewa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketHematSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'slug' => 'paket-hemat-pendaki-solo',
                'nama_paket' => 'Paket Hemat Pendaki Solo',
                'deskripsi' => 'Perlengkapan ringkas untuk pendakian sendiri, termasuk tenda ultralight, carrier, dan perlengkapan tidur.',
                'kapasitas_orang' => 1,
                'items' => [
                    ['nama_alat' => 'Tenda Ultralight 1 Orang', 'jumlah_unit' => 1],
                    ['nama_alat' => 'Backpack 40L Hiking', 'jumlah_unit' => 1],
                    ['nama_alat' => 'Sleeping Bag Summer 10°C', 'jumlah_unit' => 1],
                    ['nama_alat' => 'Matras Tidur (Sleeping Pad)', 'jumlah_unit' => 1],
                    ['nama_alat' => 'Headlamp LED 500 Lumen', 'jumlah_unit' => 1],
                    ['nama_alat' => 'Kompas & Map', 'jumlah_unit' => 1],
                ],
            ],
            [
                'slug' => 'paket-hemat-berdua',
                'nama_paket' => 'Paket Hemat Berdua',
                'deskripsi' => 'Paket berbagi tenda untuk dua pendaki, lengkap dengan carrier dan perlengkapan tidur masing-masing.',
                'kapasitas_orang' => 2,
                'items' => [
                    ['nama_alat' => 'Tenda Dome 2 Orang', 'jumlah_unit' => 1],
                    ['nama_alat' => 'Backpack 40L Hiking', 'jumlah_unit' => 2],
                    ['nama_alat' => 'Sleeping Bag Summer 10°C', 'jumlah_unit' => 2],
                    ['nama_alat' => 'Matras Tidur (Sleeping Pad)', 'jumlah_unit' => 2],
                    ['nama_alat' => 'Headlamp LED 500 Lumen', 'jumlah_unit' => 2],
                    ['nama_alat' => 'Kompas & Map', 'jumlah_unit' => 1],
                ],
            ],
            [
                'slug' => 'paket-hemat-rombongan-4-orang',
                'nama_paket' => 'Paket Hemat Rombongan 4 Orang',
                'deskripsi' => 'Perlengkapan untuk rombongan empat orang dengan tenda luas dan perlengkapan tidur untuk setiap anggota.',
                'kapasitas_orang' => 4,
                'items' => [
                    ['nama_alat' => 'Tenda Tunnel 4 Orang', 'jumlah_unit' => 1],
                    ['nama_alat' => 'Backpack 60L Trekking', 'jumlah_unit' => 4],
                    ['nama_alat' => 'Sleeping Bag Winter -5°C', 'jumlah_unit' => 4],
                    ['nama_alat' => 'Matras Tidur (Sleeping Pad)', 'jumlah_unit' => 4],
                    ['nama_alat' => 'Headlamp LED 500 Lumen', 'jumlah_unit' => 4],
                    ['nama_alat' => 'Kompas & Map', 'jumlah_unit' => 1],
                ],
            ],
            [
                'slug' => 'paket-hemat-keluarga',
                'nama_paket' => 'Paket Hemat Keluarga',
                'deskripsi' => 'Pilihan praktis untuk keluarga atau grup kecil dengan tenda empat orang dan perlengkapan dasar bersama.',
                'kapasitas_orang' => 4,
                'items' => [
                    ['nama_alat' => 'Tenda Tunnel 4 Orang', 'jumlah_unit' => 1],
                    ['nama_alat' => 'Backpack 40L Hiking', 'jumlah_unit' => 4],
                    ['nama_alat' => 'Sleeping Bag Summer 10°C', 'jumlah_unit' => 4],
                    ['nama_alat' => 'Matras Tidur (Sleeping Pad)', 'jumlah_unit' => 4],
                    ['nama_alat' => 'Headlamp LED 500 Lumen', 'jumlah_unit' => 4],
                    ['nama_alat' => 'Carabiner & Rope Set', 'jumlah_unit' => 1],
                ],
            ],
        ];

        $itemNames = collect($packages)
            ->flatMap(fn (array $package) => $package['items'])
            ->pluck('nama_alat')
            ->unique()
            ->values();

        $alatByName = AlatHiking::whereIn('nama_alat', $itemNames)
            ->where('kondisi', 'Baik')
            ->orderBy('id_alat')
            ->get()
            ->unique('nama_alat')
            ->keyBy('nama_alat');

        $missingItems = $itemNames->diff($alatByName->keys());

        if ($missingItems->isNotEmpty()) {
            $this->command?->warn(
                'PaketHematSeeder dilewati: barang paket belum tersedia ('.$missingItems->implode(', ').').'
            );

            return;
        }

        DB::transaction(function () use ($packages, $alatByName): void {
            foreach ($packages as $package) {
                $hargaNormal = collect($package['items'])->sum(
                    fn (array $item) => $alatByName[$item['nama_alat']]->harga_per_hari * $item['jumlah_unit']
                );
                $hargaPaket = (int) (floor(($hargaNormal * 0.8) / 1000) * 1000);

                $paket = PaketSewa::updateOrCreate(
                    ['slug' => $package['slug']],
                    [
                        'nama_paket' => $package['nama_paket'],
                        'deskripsi' => $package['deskripsi'],
                        'kapasitas_orang' => $package['kapasitas_orang'],
                        'harga_paket_per_hari' => $hargaPaket,
                        'gambar' => 'paket_pemula.jpg',
                        'status_aktif' => 1,
                    ]
                );

                $paket->detailPaket()->delete();

                foreach ($package['items'] as $item) {
                    DetailPaket::create([
                        'id_paket' => $paket->id_paket,
                        'id_alat' => $alatByName[$item['nama_alat']]->id_alat,
                        'jumlah_unit' => $item['jumlah_unit'],
                    ]);
                }
            }
        });

        $this->command?->info(count($packages).' Paket Hemat aktif.');
    }
}
