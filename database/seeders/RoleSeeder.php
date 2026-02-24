<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'System administrator with full access.',
        ]);

        Role::create([
            'name' => 'Adopter',
            'slug' => 'adopter',
            'description' => 'User allowed to adopt animals.',
        ]);
    }
}
