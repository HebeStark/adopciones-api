<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'User',
    type: 'object',
    required: ['id', 'name', 'email', 'role'],
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
            example: 1
        ),
        new OA\Property(
            property: 'name',
            type: 'string',
            example: 'Hebe Stark'
        ),
        new OA\Property(
            property: 'email',
            type: 'string',
            format: 'email',
            example: 'hebe@email.com'
        ),
        new OA\Property(
            property: 'role',
            type: 'string',
            enum: ['admin', 'adoptante'],
            example: 'admin'
        ),
        new OA\Property(
            property: 'created_at',
            type: 'string',
            format: 'date-time',
            example: '2026-02-22T14:35:00Z'
        )
    ]
)]
class UserSchema
{
}