<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdoptionRequest',
    type: 'object',
    required: ['id', 'status', 'animal', 'adopter', 'created_at']
)]
class AdoptionRequestSchema
{
    #[OA\Property(
        property: 'id',
        type: 'integer',
        example: 12
    )]
    public $id;

    #[OA\Property(
        property: 'status',
        type: 'string',
        example: 'pending'
    )]
    public $status;

    #[OA\Property(
        property: 'animal',
        ref: '#/components/schemas/AnimalSummary'
    )]
    public $animal;

    #[OA\Property(
        property: 'adopter',
        ref: '#/components/schemas/UserSummary'
    )]
    public $adopter;

    #[OA\Property(
        property: 'created_at',
        type: 'string',
        format: 'date-time',
        example: '2026-02-26T10:30:00Z'
    )]
    public $created_at;
}