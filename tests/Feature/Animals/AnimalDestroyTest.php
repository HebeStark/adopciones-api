<?php

namespace Tests\Feature\Animals;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\User;
use App\Models\Animal;
use App\Enums\UserRole;

class AnimalDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_an_animal(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $animal = Animal::factory()->create();

        Passport::actingAs($admin, [], 'api');

        $response = $this->deleteJson("/api/v1/animals/{$animal->id}");

        $response->assertOk()
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseMissing('animals', [
            'id' => $animal->id,
        ]);
    }

     public function test_adopter_cannot_delete_an_animal(): void
    {
        $adopter = User::factory()->create([
            'role' => UserRole::ADOPTER,
        ]);

        $animal = Animal::factory()->create();

        Passport::actingAs($adopter, [], 'api');

        $response = $this->deleteJson("/api/v1/animals/{$animal->id}");

         $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

     public function test_unauthenticated_user_cannot_delete_an_animal(): void
    {
        $animal = Animal::factory()->create();

        $response = $this->deleteJson("/api/v1/animals/{$animal->id}");

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function test_deleting_non_existing_animal_returns_404(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        Passport::actingAs($admin, [], 'api');

        $response = $this->deleteJson('/api/v1/animals/9999');

        $response->assertStatus(404);
    }

}
