<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SolicitudAdopcion',
    type: 'object',
    required: ['id', 'user_id', 'animal_id', 'estado'],
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
            example: 1
        ),
        new OA\Property(
            property: 'user_id',
            type: 'integer',
            example: 5
        ),
        new OA\Property(
            property: 'animal_id',
            type: 'integer',
            example: 2
        ),
        new OA\Property(
            property: 'estado',
            type: 'string',
            enum: ['pendiente', 'aprobada', 'rechazada'],
            example: 'pendiente'
        ),
        new OA\Property(
            property: 'created_at',
            type: 'string',
            format: 'date-time',
            example: '2026-02-22T15:10:00Z'
        )
    ]
)]
class SolicitudAdopcionSchema
{
}