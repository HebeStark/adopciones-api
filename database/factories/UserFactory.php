<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\UserRole;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'apellido' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'dni' => fake()->unique()->numerify('########A'),
            'telefono' => fake()->phoneNumber(),
            'direccion' => fake()->address(),
            'role' => \App\Enums\UserRole::ADOPTER,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
    return $this->state(fn () => [
        'role' => UserRole::ADMIN,
    ]);
    }

     public function adopter(): static
    {
    return $this->state(fn () => [
        'role' => UserRole::ADOPTER,
    ]);
    }
}
