<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_update_email_address(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'lama@example.com',
            'no_hp' => '08123456789',
        ]);

        $this->actingAs($customer)
            ->put(route('customer.profil.update'), [
                'nama_lengkap' => $customer->nama_lengkap,
                'email' => 'baru@example.com',
                'no_hp' => $customer->no_hp,
                'alamat' => $customer->alamat,
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Profil berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'email' => 'baru@example.com',
        ]);
    }

    public function test_customer_cannot_update_email_to_another_users_email(): void
    {
        $customer = User::factory()->create(['role' => 'customer', 'no_hp' => '08123456789']);
        $otherUser = User::factory()->create(['role' => 'customer', 'no_hp' => '08123456780']);

        $this->actingAs($customer)
            ->from(route('customer.profil.index'))
            ->put(route('customer.profil.update'), [
                'nama_lengkap' => $customer->nama_lengkap,
                'email' => $otherUser->email,
                'no_hp' => $customer->no_hp,
                'alamat' => $customer->alamat,
            ])
            ->assertRedirect(route('customer.profil.index'))
            ->assertSessionHasErrors('email');

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'email' => $customer->email,
        ]);
    }
}
