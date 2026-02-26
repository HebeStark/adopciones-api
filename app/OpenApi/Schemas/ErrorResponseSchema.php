<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ErrorResponse',
    type: 'object',
    required: ['success', 'message'],
    properties: [
        new OA\Property(
            property: 'success',
            type: 'boolean',
            example: false
        ),
        new OA\Property(
            property: 'message',
            type: 'string',
            example: 'Validation error'
        ),
        new OA\Property(
            property: 'errors',
            type: 'object',
            nullable: true,
            additionalProperties: new OA\AdditionalProperties(
            type: 'array',
            items: new OA\Items(type: 'string')
            ),
            example: [
                'email' => ['The email field is required.'],
                'password' => ['The password must be at least 8 characters.']
            ]
        )
    ]
)]
class ErrorResponseSchema
{
    // Schema base para respuestas de error
}