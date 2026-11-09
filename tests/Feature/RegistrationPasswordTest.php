<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_rejects_passwords_shorter_than_eight_characters(): void
    {
        $this->from('/register')
            ->post(route('register.post'), [
                'nama_lengkap' => 'Pelanggan Baru',
                'email' => 'pelanggan@example.com',
                'no_hp' => '08123456789',
                'password' => '1234567',
                'password_confirmation' => '1234567',
            ])
            ->assertRedirect('/register')
            ->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'pelanggan@example.com']);
    }

    public function test_registration_accepts_password_with_eight_characters(): void
    {
        $this->post(route('register.post'), [
            'nama_lengkap' => 'Pelanggan Baru',
            'email' => 'pelanggan@example.com',
            'no_hp' => '08123456789',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'pelanggan@example.com',
            'role' => 'customer',
        ]);
    }
}
