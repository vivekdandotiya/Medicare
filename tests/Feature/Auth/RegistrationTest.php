<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_customer_can_register_without_code(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'customer',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_new_admin_can_register_with_correct_code(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
            'access_code' => '123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_new_admin_cannot_register_with_incorrect_code(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
            'access_code' => 'wrong_code',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['access_code']);
    }

    public function test_new_staff_can_register_with_correct_code(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Staff',
            'email' => 'staff@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'staff',
            'access_code' => '1234',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_new_staff_cannot_register_with_incorrect_code(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Staff',
            'email' => 'staff@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'staff',
            'access_code' => 'wrong_code',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['access_code']);
    }
}
