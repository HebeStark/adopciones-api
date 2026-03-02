<?php

namespace Tests\Feature\AdoptionRequests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Animal;
use App\Models\AdoptionRequest;
use App\Enums\UserRole;
use App\Enums\AnimalStatus;
use App\Enums\AdoptionStatus;


class StoreAdoptionRequestTest extends TestCase
{
    use RefreshDatabase;
    protected string $endpoint = '/api/v1/adoption-requests';

    public function test_it_requires_authentication(): void
    {
        $animal = Animal::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'animal_id' => $animal->id,
        ]);

        $response->assertStatus(401);
    }

    public function test_only_adopter_can_create_adoption_request(): void
    {
        $admin = User::factory()->admin()->create();
        $animal = Animal::factory()->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson($this->endpoint, [
            'animal_id' => $animal->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_cannot_create_request_if_animal_is_not_available(): void
    {
        $adopter = User::factory()->adopter()->create();

        $animal = Animal::factory()->create([
            'estado' => AnimalStatus::ADOPTADO,
        ]);

        $response = $this->actingAs($adopter, 'api')
            ->postJson($this->endpoint, [
                'animal_id' => $animal->id,
            ]);

        $response->assertStatus(422);
    }

     public function test_adopter_can_create_adoption_request(): void
    {
        $adopter = User::factory()->adopter()->create();

        $animal = Animal::factory()->create([
            'estado' => AnimalStatus::DISPONIBLE,
        ]);

        $response = $this->actingAs($adopter, 'api')
            ->postJson($this->endpoint, [
                'animal_id' => $animal->id,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

             $this->assertDatabaseHas('adoption_requests', [
            'user_id'   => $adopter->id,
            'animal_id' => $animal->id,
            'status'    => AdoptionStatus::PENDIENTE,
        ]);
    }

    public function test_animal_id_is_required(): void
    {
        $adopter = User::factory()->adopter()->create();

        $response = $this->actingAs($adopter, 'api')
            ->postJson($this->endpoint, []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('animal_id');
    }

    public function test_animal_must_exist(): void
    {
        $adopter = User::factory()->adopter()->create();

        $response = $this->actingAs($adopter, 'api')
            ->postJson($this->endpoint, [
                'animal_id' => 9999,
            ]);

        $response->assertStatus(422)
        ->assertJsonValidationErrors('animal_id');
    }
}
