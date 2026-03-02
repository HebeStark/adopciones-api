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


class ApproveAdoptionRequestTest extends TestCase
{
     use RefreshDatabase;

      protected function endpoint(int $id): string
    {
        return "/api/v1/adoption-requests/{$id}/approve";
    }

    public function test_it_requires_authentication(): void
    {
       $adoptionRequest = AdoptionRequest::factory()->create();

        $response = $this->patchJson(
            $this->endpoint($adoptionRequest->id)
        );

        $response->assertStatus(401);
    }

    public function test_only_admin_can_approve(): void
    {
        $adopter = User::factory()->adopter()->create();

        $animal = Animal::factory()->create([
            'estado' => AnimalStatus::DISPONIBLE,
        ]);

        $adoptionRequest = AdoptionRequest::factory()->create([
            'user_id' => $adopter->id,
            'animal_id' => $animal->id,
            'status' => AdoptionStatus::PENDIENTE,
        ]);

         $response = $this->actingAs($adopter, 'api')
            ->patchJson($this->endpoint($adoptionRequest->id));

        $response->assertStatus(403);
    }

    public function test_admin_can_approve_pending_request(): void
    {
        $admin = User::factory()->admin()->create();
        $adopter = User::factory()->adopter()->create();

        $animal = Animal::factory()->create([
            'estado' => AnimalStatus::DISPONIBLE,
        ]);

        $adoptionRequest = AdoptionRequest::factory()->create([
            'user_id' => $adopter->id,
            'animal_id' => $animal->id,
            'status' => AdoptionStatus::PENDIENTE,
        ]);

        $response = $this->actingAs($admin, 'api')
            ->patchJson($this->endpoint($adoptionRequest->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

             $this->assertDatabaseHas('adoption_requests', [
            'id' => $adoptionRequest->id,
            'status' => AdoptionStatus::APROBADA,
        ]);

        $this->assertDatabaseHas('animals', [
            'id' => $animal->id,
            'estado' => AnimalStatus::ADOPTADO,
        ]);
    }

    public function test_cannot_approve_if_already_approved(): void
    {
        $admin = User::factory()->admin()->create();
        $adopter = User::factory()->adopter()->create();

        $animal = Animal::factory()->create([
            'estado' => AnimalStatus::DISPONIBLE,
        ]);

        $adoptionRequest = AdoptionRequest::factory()->create([
            'user_id' => $adopter->id,
            'animal_id' => $animal->id,
            'status' => AdoptionStatus::APROBADA,
        ]);

        $response = $this->actingAs($admin, 'api')
            ->patchJson($this->endpoint($adoptionRequest->id));

        $response->assertStatus(422);
    }

    public function test_cannot_approve_if_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        $adopter = User::factory()->adopter()->create();

        $animal = Animal::factory()->create([
            'estado' => AnimalStatus::DISPONIBLE,
        ]);

        $adoptionRequest = AdoptionRequest::factory()->create([
            'user_id' => $adopter->id,
            'animal_id' => $animal->id,
            'status' => AdoptionStatus::RECHAZADA,
        ]);

        $response = $this->actingAs($admin, 'api')
            ->patchJson($this->endpoint($adoptionRequest->id));

        $response->assertStatus(422);
    }   

    public function test_cannot_approve_if_cancelled(): void
    {
        $admin = User::factory()->admin()->create();
        $adopter = User::factory()->adopter()->create();

        $animal = Animal::factory()->create([
            'estado' => AnimalStatus::DISPONIBLE,
        ]);

        $adoptionRequest = AdoptionRequest::factory()->create([
            'user_id' => $adopter->id,
            'animal_id' => $animal->id,
            'status' => AdoptionStatus::CANCELADA,
        ]);

        $response = $this->actingAs($admin, 'api')
            ->patchJson($this->endpoint($adoptionRequest->id));

        $response->assertStatus(422);
    }

    public function test_returns_404_if_not_found(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin, 'api')
            ->patchJson($this->endpoint(9999));

        $response->assertStatus(404);
    }
}
