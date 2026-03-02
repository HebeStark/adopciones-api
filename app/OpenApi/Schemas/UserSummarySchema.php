<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserSummary',
    type: 'object',
    required: ['id', 'name'],
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
            example: 3
        ),
        new OA\Property(
            property: 'name',
            type: 'string',
            example: 'Juan Pérez'
        )
    ]
)]
class UserSummarySchema
{
}