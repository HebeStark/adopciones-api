<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_adopter_can_register_successfully(): void
    {
        
        $payload = [
            'name' => 'Nuevo',
            'apellido' => 'Adoptante',
            'dni' => '12345678A',
            'email' => 'nuevo@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefono' => '600123123',
            'direccion' => 'Calle Test 123',
        ];
        
        $response = $this->postJson('/api/v1/auth/register', $payload);

          $response->assertCreated()
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'nuevo@test.com'
        ]);
    }

    public function test_register_fails_with_invalid_data(): void
    {
    $response = $this->postJson('/api/v1/auth/register', []);

    $response->assertStatus(422)
             ->assertJson([
                 'success' => false,
             ]);
    }

    public function test_register_fails_if_email_already_exists(): void
    {
        \App\Models\User::factory()->create([
            'email' => 'nuevo@test.com',
            'dni' => '11111111A'
        ]);

            $payload = [
            'name' => 'Nuevo',
            'apellido' => 'Adoptante',
            'dni' => '22222222B',
            'email' => 'nuevo@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertStatus(422);
    }

    public function register_fails_if_dni_already_exists(): void
    {
        User::factory()->create([
            'email' => 'otro@test.com',
            'dni' => '12345678A',
        ]);

        $payload = [
            'name' => 'Nuevo',
            'apellido' => 'Adoptante',
            'dni' => '12345678A',
            'email' => 'nuevo@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

         $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertStatus(422);
    }

}
