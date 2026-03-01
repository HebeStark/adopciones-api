<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;


class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_successfully(): void
    {
            $user = User::factory()->create([
                'email' => 'login@test.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::ADOPTER,
            ]);

            $payload = [
                'email' => 'login@test.com',
                'password' => 'password123',
            ];

            $response = $this->postJson('/api/v1/auth/login', $payload);

            $response->assertOk()
                    ->assertJson([
                        'success' => true,
                    ])
                    ->assertJsonStructure([
                        'success',
                        'data' => [
                            'user',
                            'access_token',
                            'token_type',
                        ],
                    ]);
    }

     public function test_login_fails_with_invalid_credentials(): void
    {
            User::factory()->create([
                'email' => 'login@test.com',
                'password' => Hash::make('password123'),
            ]);

            $payload = [
                'email' => 'login@test.com',
                'password' => 'wrong-password',
            ];

            $response = $this->postJson('/api/v1/auth/login', $payload);

            $response->assertStatus(401)
                    ->assertJson([
                        'success' => false,
                    ]);
    }

     public function test_login_fails_with_invalid_data(): void
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                 ]);
    }



}
