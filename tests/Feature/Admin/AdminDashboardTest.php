<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Animal;
use App\Models\AdoptionRequest;
use App\Enums\AnimalStatus;
use App\Enums\AdoptionStatus;


class AdminDashboardTest extends TestCase
{
     use RefreshDatabase;

    protected string $endpoint = '/api/v1/admin/dashboard';

    public function test_it_requires_authentication(): void
    {
         $response = $this->getJson($this->endpoint);

        $response->assertStatus(401);
    }

    public function test_only_admin_can_access_dashboard(): void
    {
        $adopter = User::factory()->adopter()->create();

        $response = $this->actingAs($adopter, 'api')
            ->getJson($this->endpoint);

        $response->assertStatus(403);
    }

     public function test_returns_correct_statistics(): void
    {
        $admin = User::factory()->admin()->create();

        $availableAnimals = Animal::factory()->count(3)->create([
            'estado' => AnimalStatus::DISPONIBLE,
        ]);

       $adoptedAnimals = Animal::factory()->count(2)->create([
             'estado' => AnimalStatus::ADOPTADO,
        ]);

        AdoptionRequest::factory()->count(1)->create([
            'status' => AdoptionStatus::PENDIENTE,
            'animal_id' => $availableAnimals->first()->id,
        ]);

        AdoptionRequest::factory()->count(2)->create([
            'status' => AdoptionStatus::APROBADA,
            'animal_id' => $availableAnimals->first()->id,
        ]);

        $response = $this->actingAs($admin, 'api')
            ->getJson($this->endpoint);

             $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'animals' => [
                        'total' => 5,
                        'available' => 3,
                    ],
                    'adoption_requests' => [
                        'pending' => 1,
                        'approved' => 2,
                    ],
                ],
            ]);
    }
}
