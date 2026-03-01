<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdoptionRequest;
use App\Models\User;
use App\Models\Animal;
use App\Enums\AdoptionStatus;

class AdoptionRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adopter1 = User::where('email', 'adopter1@test.com')->first();
        $adopter2 = User::where('email', 'adopter2@test.com')->first();

        $luna = Animal::where('nombre', 'Luna')->first();
        $rocky = Animal::where('nombre', 'Rocky')->first();
        $thor = Animal::where('nombre', 'Thor')->first();
        $nala = Animal::where('nombre', 'Nala')->first();

        AdoptionRequest::create([
            'user_id' => $adopter1->id,
            'animal_id' => $luna->id,
            'status' => AdoptionStatus::PENDIENTE,
        ]);

        AdoptionRequest::create([
            'user_id' => $adopter2->id,
            'animal_id' => $rocky->id,
            'status' => AdoptionStatus::RECHAZADA,
        ]);

         AdoptionRequest::create([
            'user_id' => $adopter1->id,
            'animal_id' => $thor->id,
            'status' => AdoptionStatus::APROBADA,
        ]);

        AdoptionRequest::create([
            'user_id' => $adopter2->id,
            'animal_id' => $nala->id,
            'status' => AdoptionStatus::CANCELADA,
        ]);
    }
}
