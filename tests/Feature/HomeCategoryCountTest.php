<?php

namespace Tests\Feature;

use App\Models\AlatHiking;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeCategoryCountTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_category_count_matches_unique_catalog_products(): void
    {
        $category = Kategori::create([
            'nama_kategori' => 'Tenda',
            'slug' => 'tenda',
            'deskripsi' => 'Kategori tenda',
            'icon' => 'bi-triangle',
        ]);

        $product = [
            'id_kategori' => $category->id_kategori,
            'nama_alat' => 'Tenda Dome',
            'merk' => 'Merek Contoh',
            'deskripsi' => 'Tenda untuk pengujian.',
            'spesifikasi' => 'Kapasitas dua orang.',
            'harga_per_hari' => 50000,
            'stok_total' => 2,
            'kondisi' => 'Baik',
            'gambar' => 'tenda.jpg',
        ];

        AlatHiking::create($product + ['slug' => 'tenda-dome']);
        AlatHiking::create($product + ['slug' => 'tenda-dome-duplikat']);
        AlatHiking::create($product + [
            'slug' => 'tenda-rusak',
            'nama_alat' => 'Tenda Rusak',
            'kondisi' => 'Rusak',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $this->assertSame(1, $response->viewData('kategoriList')->first()->alat_hiking_count);

        $catalogResponse = $this->get('/katalog');

        $catalogResponse->assertOk();
        $this->assertSame(1, $catalogResponse->viewData('items')->total());
    }
}
