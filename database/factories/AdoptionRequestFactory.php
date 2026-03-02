<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Animal;
use App\Models\AdoptionRequest;
use App\Enums\AdoptionStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdoptionRequest>
 */
class AdoptionRequestFactory extends Factory
{
     protected $model = AdoptionRequest::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'animal_id' => Animal::factory(),
            'status' => AdoptionStatus::PENDIENTE,
        ];
    }
}
