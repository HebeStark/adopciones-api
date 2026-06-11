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
                'foto' => '/images/animals/luna.jpg',
            ],
            [
                'nombre' => 'Rocky',
                'tipo' => 'perro',
                'edad' => 4,
                'estado' => AnimalStatus::DISPONIBLE,
                'descripcion' => 'Perro activo y juguetón.',
                'foto' => '/images/animals/rocky.jpg',
            ],
            [
                'nombre' => 'Michu',
                'tipo' => 'gato',
                'edad' => 1,
                'estado' => AnimalStatus::DISPONIBLE,
                'descripcion' => 'Pequeño y muy curioso.',
                'foto' => '/images/animals/michu.jpg',
            ],
            [
                'nombre' => 'Kyla',
                'tipo' => 'perro',
                'edad' => 3,
                'estado' => AnimalStatus::DISPONIBLE,
                'descripcion' => 'Muy sociable y obediente.',
                'foto' => '/images/animals/kyla.jpg',
            ],
            [
                'nombre' => 'Thor',
                'tipo' => 'perro',
                'edad' => 5,
                'estado' => AnimalStatus::ADOPTADO,
                'descripcion' => 'Fuerte y protector.',
                'foto' => '/images/animals/thor.jpg',
            ],
            [
                'nombre' => 'Nala',
                'tipo' => 'gato',
                'edad' => 2,
                'estado' => AnimalStatus::ADOPTADO,
                'descripcion' => 'Muy dulce y tranquila.',
                'foto' => '/images/animals/nala.jpg',
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
