<?php

namespace Tests\Feature;

use App\Models\PesanKontak;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_saves_the_message_for_admin_review(): void
    {
        $this->post(route('kontak.send'), [
            'nama' => 'Pendaki Baru',
            'email' => 'pendaki@example.com',
            'subjek' => 'Tanya paket',
            'pesan' => 'Apakah paket tersedia akhir pekan ini?',
        ])->assertRedirect(route('kontak'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pesan_kontak', [
            'nama' => 'Pendaki Baru',
            'email' => 'pendaki@example.com',
            'subjek' => 'Tanya paket',
            'pesan' => 'Apakah paket tersedia akhir pekan ini?',
        ]);
    }

    public function test_signed_in_customer_contact_message_is_linked_to_their_account(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('kontak'))
            ->assertOk()
            ->assertSee('value="'.$customer->email.'"', false);

        $this->post(route('kontak.send'), [
            'nama' => $customer->nama_lengkap,
            'email' => $customer->email,
            'subjek' => 'Tanya paket',
            'pesan' => 'Apakah paket tersedia akhir pekan ini?',
        ])->assertRedirect(route('kontak'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pesan_kontak', [
            'id_user' => $customer->id,
            'email' => $customer->email,
            'subjek' => 'Tanya paket',
        ]);
    }

    public function test_admin_can_view_message_list_and_message_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $message = PesanKontak::create([
            'nama' => 'Pendaki Baru',
            'email' => 'pendaki@example.com',
            'subjek' => 'Tanya paket',
            'pesan' => 'Apakah paket tersedia akhir pekan ini?',
            'id_user' => $customer->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pesan.index'))
            ->assertOk()
            ->assertSee('Pendaki Baru')
            ->assertSee('Tanya paket');

        $this->get(route('admin.pesan.show', $message->id_pesan))
            ->assertOk()
            ->assertSee('Apakah paket tersedia akhir pekan ini?')
            ->assertSee(route('admin.customer.show', $customer->id));
    }

    public function test_customer_cannot_view_contact_messages_in_admin(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('admin.pesan.index'))
            ->assertForbidden();
    }
}
