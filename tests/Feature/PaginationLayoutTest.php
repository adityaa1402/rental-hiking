<?php

namespace Tests\Feature;

use App\Models\PesanKontak;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginationLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pagination_uses_compact_previous_and_next_controls(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        for ($index = 1; $index <= 20; $index++) {
            PesanKontak::create([
                'nama' => "Pendaki {$index}",
                'email' => "pendaki{$index}@example.com",
                'subjek' => 'Pertanyaan paket',
                'pesan' => 'Mohon informasi paket.',
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.pesan.index'))
            ->assertOk()
            ->assertSee('pagination-sm')
            ->assertSee('Halaman 1 dari 2')
            ->assertSee('Berikutnya')
            ->assertDontSee('Showing');
    }
}
