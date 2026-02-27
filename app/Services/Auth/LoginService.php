<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;



class LoginService
{
    public function login(array $credentials): array
    {
         $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationException('Credenciales inválidas.');
        }

            $tokenResult = $user->createToken('api-token');
            $accessToken = $tokenResult->accessToken;

        return [
            'user' => $user,
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
        ];
    }
}