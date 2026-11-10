<?php

namespace Tests\Feature;

use App\Models\AlatHiking;
use App\Models\DetailPaket;
use App\Models\Kategori;
use App\Models\PaketSewa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaketManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_form_shows_real_item_names_and_prices(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->createItem();

        $this->actingAs($admin)
            ->get(route('admin.paket.create'))
            ->assertOk()
            ->assertSee('Tenda Dome')
            ->assertSee('Rp 50.000')
            ->assertDontSee('nama_barang');
    }

    public function test_package_images_render_in_admin_detail_and_edit_views(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $package = $this->createPackage();

        $this->assertFileExists(public_path('uploads/paket/paket_pemula.jpg'));

        $this->actingAs($admin)
            ->get(route('admin.paket.index'))
            ->assertOk()
            ->assertSee('/uploads/paket/paket_pemula.jpg', false);

        $this->get(route('admin.paket.show', $package->id_paket))
            ->assertOk()
            ->assertSee('/uploads/paket/paket_pemula.jpg', false);

        $this->get(route('admin.paket.edit', $package->id_paket))
            ->assertOk()
            ->assertSee('/uploads/paket/paket_pemula.jpg', false);
    }

    public function test_promotional_package_images_render_from_valid_artwork(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $package = $this->createPackage();
        $package->update(['slug' => 'paket-hemat-pendaki-solo']);

        $this->actingAs($admin)
            ->get(route('admin.paket.index'))
            ->assertOk()
            ->assertSee('/assets/images/paket-hemat-solo.png', false);
    }

    public function test_update_replaces_package_items_and_persists_inactive_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $firstItem = $this->createItem('Tenda Dome');
        $secondItem = $this->createItem('Sleeping Bag');
        $package = $this->createPackage();
        $package->detailPaket()->create([
            'id_alat' => $firstItem->id_alat,
            'jumlah_unit' => 1,
        ]);

        $this->actingAs($admin)
            ->withSession(['_token' => 'test-token'])
            ->put(route('admin.paket.update', $package->id_paket), [
                '_token' => 'test-token',
                'nama_paket' => $package->nama_paket,
                'deskripsi' => $package->deskripsi,
                'kapasitas_orang' => 2,
                'harga_paket_per_hari' => 75000,
                'items' => [
                    ['id_alat' => $secondItem->id_alat, 'jumlah' => 2],
                ],
            ])
            ->assertRedirect(route('admin.paket.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('detail_paket', [
            'id_paket' => $package->id_paket,
            'id_alat' => $secondItem->id_alat,
            'jumlah_unit' => 2,
        ]);
        $this->assertDatabaseMissing('detail_paket', [
            'id_paket' => $package->id_paket,
            'id_alat' => $firstItem->id_alat,
        ]);
        $this->assertDatabaseHas('paket_sewa', [
            'id_paket' => $package->id_paket,
            'status_aktif' => 0,
            'harga_paket_per_hari' => 75000,
        ]);
    }

    public function test_update_rejects_duplicate_or_empty_items_without_removing_existing_items(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = $this->createItem();
        $package = $this->createPackage();
        $package->detailPaket()->create([
            'id_alat' => $item->id_alat,
            'jumlah_unit' => 1,
        ]);
        $validData = [
            '_token' => 'test-token',
            'nama_paket' => $package->nama_paket,
            'deskripsi' => $package->deskripsi,
            'kapasitas_orang' => 2,
            'harga_paket_per_hari' => 75000,
        ];

        $this->actingAs($admin)
            ->withSession(['_token' => 'test-token'])
            ->from(route('admin.paket.edit', $package->id_paket))
            ->put(route('admin.paket.update', $package->id_paket), $validData + [
                'items' => [
                    ['id_alat' => $item->id_alat, 'jumlah' => 1],
                    ['id_alat' => $item->id_alat, 'jumlah' => 2],
                ],
            ])
            ->assertRedirect(route('admin.paket.edit', $package->id_paket))
            ->assertSessionHasErrors('items.1.id_alat');

        $this->put(route('admin.paket.update', $package->id_paket), $validData + [
            'items' => [],
        ])->assertSessionHasErrors('items');

        $this->assertDatabaseHas('detail_paket', [
            'id_paket' => $package->id_paket,
            'id_alat' => $item->id_alat,
            'jumlah_unit' => 1,
        ]);
        $this->assertSame(1, DetailPaket::where('id_paket', $package->id_paket)->count());
    }

    private function createItem(string $name = 'Tenda Dome'): AlatHiking
    {
        static $itemIndex = 0;
        $category = Kategori::firstOrCreate(
            ['slug' => 'perlengkapan'],
            ['nama_kategori' => 'Perlengkapan', 'icon' => 'bi-box']
        );

        return AlatHiking::create([
            'id_kategori' => $category->id_kategori,
            'nama_alat' => $name,
            'slug' => 'item-' . ++$itemIndex,
            'merk' => 'HikeTrip',
            'deskripsi' => 'Perlengkapan pendakian.',
            'spesifikasi' => 'Kondisi baik.',
            'harga_per_hari' => 50000,
            'stok_total' => 5,
            'stok_rusak' => 0,
            'kondisi' => 'Baik',
            'gambar' => 'item.jpg',
        ]);
    }

    private function createPackage(): PaketSewa
    {
        static $packageIndex = 0;

        return PaketSewa::create([
            'nama_paket' => 'Paket Tes',
            'slug' => 'paket-tes-' . ++$packageIndex,
            'deskripsi' => 'Paket untuk pengujian.',
            'kapasitas_orang' => 2,
            'harga_paket_per_hari' => 80000,
            'gambar' => 'paket_pemula.jpg',
            'status_aktif' => 1,
        ]);
    }
}
