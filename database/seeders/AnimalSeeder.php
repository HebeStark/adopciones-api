<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Animal;
use App\Enums\AnimalStatus;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $animals = [
            [
                'nombre' => 'Luna',
                'tipo' => 'gato',
                'edad' => 2,
                'estado' => AnimalStatus::DISPONIBLE,
                'descripcion' => 'Gata tranquila y cariñosa.',
                'foto' => null,
            ],
            [
                'nombre' => 'Rocky',
                'tipo' => 'perro',
                'edad' => 4,
                'estado' => AnimalStatus::DISPONIBLE,
                'descripcion' => 'Perro activo y juguetón.',
                'foto' => null,
            ],
            [
                'nombre' => 'Michu',
                'tipo' => 'gato',
                'edad' => 1,
                'estado' => AnimalStatus::DISPONIBLE,
                'descripcion' => 'Pequeño y muy curioso.',
                'foto' => null,
            ],
            [
                'nombre' => 'Kyla',
                'tipo' => 'perro',
                'edad' => 3,
                'estado' => AnimalStatus::DISPONIBLE,
                'descripcion' => 'Muy sociable y obediente.',
                'foto' => null,
            ],
            [
                'nombre' => 'Thor',
                'tipo' => 'perro',
                'edad' => 5,
                'estado' => AnimalStatus::ADOPTADO,
                'descripcion' => 'Fuerte y protector.',
                'foto' => null,
            ],
            [
                'nombre' => 'Nala',
                'tipo' => 'gato',
                'edad' => 2,
                'estado' => AnimalStatus::ADOPTADO,
                'descripcion' => 'Muy dulce y tranquila.',
                'foto' => null,
            ],
        ];

        foreach ($animals as $animal) {
            Animal::updateOrCreate(
                ['nombre' => $animal['nombre']],
                $animal
            );
        }
    }
}
