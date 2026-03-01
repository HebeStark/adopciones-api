<?php

namespace Tests\Feature\Animals;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Animal;
use Tests\TestCase;

class AnimalIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_list_animals(): void
    {
        Animal::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/animals');

        $response->assertOk()
                 ->assertJson([
                     'success' => true,
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data',
                 ]);
    }
}
