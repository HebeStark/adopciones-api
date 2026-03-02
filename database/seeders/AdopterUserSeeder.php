<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class AdopterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'adopter1@test.com'],
            [
                'name' => 'Laura',
                'apellido' => 'Gomez',
                'password' => Hash::make('password'),
                'telefono' => '111111111',
                'direccion' => 'Calle Uno',
                'dni' => '11111111A',
                'role' => UserRole::ADOPTER,
            ]
        );

         User::updateOrCreate(
            ['email' => 'adopter2@test.com'],
            [
                'name' => 'Carlos',
                'apellido' => 'Perez',
                'password' => Hash::make('password'),
                'telefono' => '222222222',
                'direccion' => 'Calle Dos',
                'dni' => '22222222B',
                'role' => UserRole::ADOPTER,
            ]
        );
    }
}
