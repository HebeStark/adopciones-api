<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Auth',
    type: 'object',
    required: ['user', 'access_token', 'token_type']
)]
class AuthSchema
{
    #[OA\Property(
        property: 'user',
        ref: '#/components/schemas/User'
    )]
    public $user;

    #[OA\Property(
        property: 'access_token',
        type: 'string',
        example: '1|Jkdfh2398dfh2398dfh2398'
    )]
    public $access_token;

    #[OA\Property(
        property: 'token_type',
        type: 'string',
        example: 'Bearer'
    )]
    public $token_type;
}