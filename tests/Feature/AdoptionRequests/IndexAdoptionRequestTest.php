<?php

namespace Tests\Feature\AdoptionRequests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AdoptionRequest;
use App\Enums\AdoptionStatus;


class IndexAdoptionRequestTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = '/api/v1/adoption-requests';

    public function test_it_requires_authentication(): void
    {
       $response = $this->getJson($this->endpoint);

        $response->assertStatus(401);
    }

    public function test_adopter_only_sees_their_own_requests(): void
    {
        $owner = User::factory()->adopter()->create();
        $other = User::factory()->adopter()->create();

        AdoptionRequest::factory()->create([
            'user_id' => $owner->id,
        ]);

        AdoptionRequest::factory()->create([
            'user_id' => $other->id,
        ]);

         $response = $this->actingAs($owner, 'api')
            ->getJson($this->endpoint);

        $response->assertStatus(200);

        $this->assertCount(1, $response->json('data'));
    }

     public function test_admin_sees_all_requests(): void
    {
        $admin = User::factory()->admin()->create();

        AdoptionRequest::factory()->count(3)->create();

        $response = $this->actingAs($admin, 'api')
            ->getJson($this->endpoint);

        $response->assertStatus(200);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_filter_by_status(): void
    {
        $admin = User::factory()->admin()->create();

        AdoptionRequest::factory()->create([
            'status' => AdoptionStatus::PENDIENTE,
        ]);

        AdoptionRequest::factory()->create([
            'status' => AdoptionStatus::APROBADA,
        ]);
        $response = $this->actingAs($admin, 'api')
        ->getJson($this->endpoint . '?status=' . AdoptionStatus::PENDIENTE->value);

        $response->assertStatus(200);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_can_filter_by_animal_id(): void
    {
        $admin = User::factory()->admin()->create();

        $animalA = \App\Models\Animal::factory()->create();
        $animalB = \App\Models\Animal::factory()->create();

        AdoptionRequest::factory()->create([
            'animal_id' => $animalA->id,
        ]);

        AdoptionRequest::factory()->create([
            'animal_id' => $animalB->id,
        ]);

        $response = $this->actingAs($admin, 'api')
        ->getJson($this->endpoint . '?animal_id=' . $animalA->id);

        $response->assertStatus(200);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_can_filter_by_status_and_animal(): void
    {
        $admin = User::factory()->admin()->create();

        $animal = \App\Models\Animal::factory()->create();

        AdoptionRequest::factory()->create([
            'animal_id' => $animal->id,
            'status' => AdoptionStatus::PENDIENTE,
        ]);

        AdoptionRequest::factory()->create([
            'animal_id' => $animal->id,
            'status' => AdoptionStatus::RECHAZADA,
        ]);

        $response = $this->actingAs($admin, 'api')
        ->getJson($this->endpoint .
            '?animal_id=' . $animal->id .
            '&status=' . AdoptionStatus::PENDIENTE->value
        );

        $response->assertStatus(200);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_respects_per_page_parameter(): void
    {
        $admin = User::factory()->admin()->create();

        AdoptionRequest::factory()->count(5)->create();

        $response = $this->actingAs($admin, 'api')
            ->getJson($this->endpoint . '?per_page=2');

        $response->assertStatus(200);

        $this->assertCount(2, $response->json('data'));
    }

    public function test_returns_pagination_meta(): void
    {
        $admin = User::factory()->admin()->create();

        AdoptionRequest::factory()->count(3)->create();

        $response = $this->actingAs($admin, 'api')
        ->getJson($this->endpoint . '?per_page=2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => [
                    'current_page',
                    'last_page',
                    'total',
                ],
                ]);

        $this->assertEquals(3, $response->json('meta.total'));
    }
}
