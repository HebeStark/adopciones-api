<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
         User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin',
                'apellido' => 'System',
                'password' => Hash::make('password'),
                'telefono' => '000000000',
                'direccion' => 'Sistema',
                'dni' => null,
                'role' => UserRole::ADMIN,
        ]);
    }
}
