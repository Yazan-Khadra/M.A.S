<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_logs_in_successfully()
    {
        $user = User::factory()->create([
            'email' => 'nour@example.com',
            'password' => bcrypt('nour1212'),
        ]);
        $credentials = [
            'email' => 'nour@example.com',
            'password' => 'nour1212',
        ];
        $response = $this->postJson('api/login', $credentials);
        $response->assertStatus(200);
        // ensure that token created successfully
        $this->assertArrayHasKey('token', $response->json());
        $response->assertJson(['message' => 'Admin successfully loggedin']);
    }

    #[Test]
    public function test_fails_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'naya@example.com',
            'password' => bcrypt('naya1212'),
        ]);
        $credentials = [
            'email' => 'naya@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('api/login', $credentials);
        $response->assertStatus(401);
        $response->assertJson(['error' => 'Invalid credentials']);
    }
}
