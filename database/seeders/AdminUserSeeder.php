<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        
         $adminRole = Role::where('slug', 'admin')->first();

        if (!$adminRole) {
            throw new \Exception('Admin role not found. Seed roles first.');
        }

         User::create([
            'name' => 'System Admin',
            'email' => 'admin@api.com',
            'password' => Hash::make('Admin123!'),
            'role_id' => $adminRole->id,
        ]);
    }
}
