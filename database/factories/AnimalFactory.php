<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\AnimalStatus;

class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'tipo' => fake()->randomElement(['perro', 'gato']),
            'edad' => fake()->numberBetween(1, 15),
            'estado' => AnimalStatus::DISPONIBLE,
            'foto' => fake()->imageUrl(),
        ];
    }
}
