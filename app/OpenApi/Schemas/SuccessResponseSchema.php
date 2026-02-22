<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SuccessResponse',
    type: 'object',
    required: ['success'],
    properties: [
        new OA\Property(
            property: 'success',
            type: 'boolean',
            example: true
        ),
        new OA\Property(
            property: 'data',
            nullable: true,
            description: 'Response payload (object or array)'
        ),
        new OA\Property(
            property: 'meta',
            type: 'object',
            nullable: true,
            description: 'Pagination or additional metadata',
            properties: [
                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                new OA\Property(property: 'per_page', type: 'integer', example: 10),
                new OA\Property(property: 'total', type: 'integer', example: 35)
            ]
        )
    ]
)]
class SuccessResponseSchema
{
    // Schema base para respuestas exitosas
}