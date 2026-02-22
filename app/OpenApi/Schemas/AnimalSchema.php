<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Animal',
    type: 'object',
    required: ['id', 'nombre', 'tipo', 'edad', 'estado'],
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
            example: 1
        ),
        new OA\Property(
            property: 'nombre',
            type: 'string',
            example: 'Kyla'
        ),
        new OA\Property(
            property: 'tipo',
            type: 'string',
            enum: ['Perro', 'Gato'],
            example: 'Perro'
        ),
        new OA\Property(
            property: 'edad',
            type: 'integer',
            minimum: 0,
            example: 3
        ),
        new OA\Property(
            property: 'estado',
            type: 'string',
            enum: ['disponible', 'adoptado'],
            example: 'disponible'
        ),
        new OA\Property(
            property: 'foto',
            type: 'string',
            format: 'uri',
            nullable: true,
            example: 'https://api.adopciones.com/storage/animals/kyla.jpg'
        ),
        new OA\Property(
            property: 'created_at',
            type: 'string',
            format: 'date-time',
            example: '2026-02-22T14:35:00Z'
        )
    ]
)]
class AnimalSchema
{
}