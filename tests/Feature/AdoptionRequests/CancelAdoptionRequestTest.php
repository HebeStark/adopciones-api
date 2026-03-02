<?php

namespace Tests\Feature\AdoptionRequests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Animal;
use App\Models\AdoptionRequest;
use App\Enums\AnimalStatus;
use App\Enums\AdoptionStatus;

class CancelAdoptionRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function endpoint(int $id): string
    {
        return "/api/v1/adoption-requests/{$id}/cancel";
    }

    public function test_it_requires_authentication(): void
    {
        $request = AdoptionRequest::factory()->create();

        $response = $this->patchJson(
            $this->endpoint($request->id)
        );

        $response->assertStatus(401);
    }

     public function test_admin_cannot_cancel(): void
    {
        $admin = User::factory()->admin()->create();
        $request = AdoptionRequest::factory()->create([
            'status' => AdoptionStatus::PENDIENTE,
        ]);

        $response = $this->actingAs($admin, 'api')
            ->patchJson($this->endpoint($request->id));

        $response->assertStatus(403);
    }

     public function test_non_owner_adopter_cannot_cancel(): void
    {
        $owner = User::factory()->adopter()->create();
        $otherUser = User::factory()->adopter()->create();

        $request = AdoptionRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => AdoptionStatus::PENDIENTE,
        ]);

        $response = $this->actingAs($otherUser, 'api')
            ->patchJson($this->endpoint($request->id));

        $response->assertStatus(403);
    }

     public function test_owner_can_cancel_pending_request(): void
    {
        $owner = User::factory()->adopter()->create();

        $animal = Animal::factory()->create([
            'estado' => AnimalStatus::DISPONIBLE,
        ]);

        $request = AdoptionRequest::factory()->create([
            'user_id' => $owner->id,
            'animal_id' => $animal->id,
            'status' => AdoptionStatus::PENDIENTE,
        ]);
        $response = $this->actingAs($owner, 'api')
            ->patchJson($this->endpoint($request->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('adoption_requests', [
            'id' => $request->id,
            'status' => AdoptionStatus::CANCELADA,
        ]);
          $this->assertDatabaseHas('animals', [
            'id' => $animal->id,
            'estado' => AnimalStatus::DISPONIBLE,
        ]);
    }

     public function test_cannot_cancel_if_approved(): void
    {
        $owner = User::factory()->adopter()->create();

        $request = AdoptionRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => AdoptionStatus::APROBADA,
        ]);

         $response = $this->actingAs($owner, 'api')
            ->patchJson($this->endpoint($request->id));

        $response->assertStatus(422);
    }

     public function test_cannot_cancel_if_rejected(): void
    {
        $owner = User::factory()->adopter()->create();

        $request = AdoptionRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => AdoptionStatus::RECHAZADA,
        ]);

         $response = $this->actingAs($owner, 'api')
            ->patchJson($this->endpoint($request->id));

        $response->assertStatus(422);
    }

    public function test_cannot_cancel_if_already_cancelled(): void
    {
        $owner = User::factory()->adopter()->create();

        $request = AdoptionRequest::factory()->create([
            'user_id' => $owner->id,
            'status' => AdoptionStatus::CANCELADA,
        ]);

         $response = $this->actingAs($owner, 'api')
            ->patchJson($this->endpoint($request->id));

        $response->assertStatus(422);
    }

    public function test_returns_404_if_not_found(): void
    {
        $owner = User::factory()->adopter()->create();

        $response = $this->actingAs($owner, 'api')
            ->patchJson($this->endpoint(9999));

        $response->assertStatus(404);
    }

}
