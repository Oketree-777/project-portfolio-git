<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_admin_can_access_admin_panel()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/performance');

        $response->assertStatus(200);
    }

    public function test_user_cannot_access_admin_panel()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/admin/performance');

        $response->assertStatus(403);
    }
}
