<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AnimalSummary',
    type: 'object',
    required: ['id', 'nombre', 'estado'],
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
            example: 5
        ),
        new OA\Property(
            property: 'nombre',
            type: 'string',
            example: 'Kyla'
        ),
        new OA\Property(
            property: 'estado',
            type: 'string',
            example: 'disponible'
        )
    ]
)]
class AnimalSummarySchema
{
}