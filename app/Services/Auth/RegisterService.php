<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'name' => $data['name'],
                'apellido' => $data['apellido'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'telefono' => $data['telefono'] ?? null,
                'direccion' => $data['direccion'] ?? null,
                'dni' => $data['dni'],
                'role' => UserRole::ADOPTER,
            ]);

            $tokenResult = $user->createToken('api-token');
            $accessToken = $tokenResult->accessToken;

            return [
                'user' => $user,
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
            ];
        });
    }
}