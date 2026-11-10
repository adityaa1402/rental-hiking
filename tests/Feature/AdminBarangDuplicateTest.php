<?php

namespace Tests\Feature;

use App\Models\AlatHiking;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBarangDuplicateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_barang_list_shows_a_matching_item_only_once(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = $this->createCategory();
        $this->createItem($category, 'Tenda Dome', 'HikeCo');
        $this->createItem($category, 'Tenda Dome', 'HikeCo');

        $this->actingAs($admin)
            ->get(route('admin.barang.index'))
            ->assertOk()
            ->assertSee('Total: 1 barang')
            ->assertSee('Tenda Dome');
    }

    public function test_admin_product_images_load_from_the_legacy_upload_directory(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = $this->createCategory();
        $item = $this->createItem($category, 'Tenda Dome', 'HikeCo', 'tenda-dome-decathlon.jpg');
        $imagePath = public_path('uploads/barang/tenda-dome-decathlon.jpg');

        $this->assertFileExists($imagePath);

        $this->actingAs($admin)
            ->get(route('admin.barang.index'))
            ->assertOk()
            ->assertSee(asset('uploads/barang/tenda-dome-decathlon.jpg'), false);

        $this->get(route('admin.barang.show', $item->id_alat))
            ->assertOk()
            ->assertSee(asset('uploads/barang/tenda-dome-decathlon.jpg'), false);

        $this->get(route('admin.barang.edit', $item->id_alat))
            ->assertOk()
            ->assertSee(asset('uploads/barang/tenda-dome-decathlon.jpg'), false);
    }

    public function test_admin_cannot_add_an_item_that_is_already_registered(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $category = $this->createCategory();
        $this->createItem($category, 'Tenda Dome', 'HikeCo');

        $this->actingAs($admin)
            ->withSession(['_token' => 'test-token'])
            ->from(route('admin.barang.create'))
            ->post(route('admin.barang.store'), $this->validItemData($category, [
                '_token' => 'test-token',
                'nama_alat' => 'Tenda Dome',
                'merk' => 'HikeCo',
                'gambar' => UploadedFile::fake()->create('tenda.jpg', 10, 'image/jpeg'),
            ]))
            ->assertRedirect(route('admin.barang.create'))
            ->assertSessionHasErrors('nama_alat');

        $this->assertSame(1, AlatHiking::count());
    }

    public function test_admin_cannot_edit_an_item_to_match_another_registered_item(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = $this->createCategory();
        $this->createItem($category, 'Tenda Dome', 'HikeCo');
        $itemToUpdate = $this->createItem($category, 'Tenda Tunnel', 'CampCo');

        $this->actingAs($admin)
            ->withSession(['_token' => 'test-token'])
            ->from(route('admin.barang.edit', $itemToUpdate->id_alat))
            ->put(route('admin.barang.update', $itemToUpdate->id_alat), $this->validItemData($category, [
                '_token' => 'test-token',
                'nama_alat' => 'Tenda Dome',
                'merk' => 'HikeCo',
                'gambar' => null,
            ]))
            ->assertRedirect(route('admin.barang.edit', $itemToUpdate->id_alat))
            ->assertSessionHasErrors('nama_alat');

        $this->assertSame('Tenda Tunnel', $itemToUpdate->fresh()->nama_alat);
    }

    private function createCategory(): Kategori
    {
        return Kategori::create([
            'nama_kategori' => 'Tenda',
            'slug' => 'tenda',
        ]);
    }

    private function createItem(Kategori $category, string $name, string $brand, string $image = 'tenda.jpg'): AlatHiking
    {
        static $slugNumber = 0;

        return AlatHiking::create([
            'id_kategori' => $category->id_kategori,
            'nama_alat' => $name,
            'slug' => 'barang-' . ++$slugNumber,
            'merk' => $brand,
            'deskripsi' => 'Perlengkapan hiking.',
            'spesifikasi' => 'Spesifikasi barang.',
            'harga_per_hari' => 10000,
            'stok_total' => 2,
            'stok_rusak' => 0,
            'kondisi' => 'Baik',
            'gambar' => $image,
        ]);
    }

    private function validItemData(Kategori $category, array $overrides = []): array
    {
        return array_merge([
            'id_kategori' => $category->id_kategori,
            'nama_alat' => 'Sleeping Bag',
            'merk' => 'CampCo',
            'deskripsi' => 'Perlengkapan hiking.',
            'spesifikasi' => 'Spesifikasi barang.',
            'harga_per_hari' => 10000,
            'stok_total' => 2,
            'stok_rusak' => 0,
            'kondisi' => 'Baik',
        ], $overrides);
    }
}
