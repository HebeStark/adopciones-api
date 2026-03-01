<?php

namespace Tests\Feature\Animals;

use Tests\TestCase;
use App\Models\User;
use App\Models\Animal;
use App\Enums\UserRole;
use App\Enums\AnimalStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class AnimalStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_an_animal(): void
    {
         $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        Passport::actingAs($admin, [], 'api');

        $payload = [
            'nombre' => 'Kyla',
            'tipo' => 'gato', // ⚠️ respetar enum/check
            'edad' => 3,
            'estado' => AnimalStatus::DISPONIBLE->value,
            'foto' => 'https://example.com/kyla.jpg',
        ];

        $response = $this->postJson('/api/v1/animals', $payload);

        $response->assertCreated()
                 ->assertJson([
                     'success' => true,
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data',
                 ]);

        $this->assertDatabaseHas('animals', [
            'nombre' => 'Kyla',
            'tipo' => 'gato',
        ]);
    }

    public function test_adopter_cannot_create_an_animal(): void
    {
        $adopter = User::factory()->create([
            'role' => UserRole::ADOPTER,
        ]);

        Passport::actingAs($adopter, [], 'api');

         $payload = [
            'nombre' => 'Kyla',
            'tipo' => 'gato',
            'edad' => 3,
            'estado' => AnimalStatus::DISPONIBLE->value,
            'foto' => 'https://example.com/kyla.jpg',
        ];

        $response = $this->postJson('/api/v1/animals', $payload);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

     public function test_unauthenticated_user_cannot_create_an_animal(): void
    {
        $payload = [
            'nombre' => 'Kyla',
            'tipo' => 'gato',
            'edad' => 3,
            'estado' => AnimalStatus::DISPONIBLE->value,
            'foto' => 'https://example.com/kyla.jpg',
        ];

         $response = $this->postJson('/api/v1/animals', $payload);

         $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

     public function test_admin_cannot_create_an_animal_with_invalid_data(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        Passport::actingAs($admin, [], 'api');

        $response = $this->postJson('/api/v1/animals', []);
        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

}
