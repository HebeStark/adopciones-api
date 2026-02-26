<?php
namespace App\OpenApi\System;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'System',
    description: 'System monitoring endpoints'
)]
#[OA\Get(
    path: '/health',
    summary: 'Health check',
    description: 'Verifies that the API is running correctly',
    tags: ['System'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'API is healthy',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'success',
                        type: 'boolean',
                        example: true
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'string',
                        example: 'OK'
                    )
                ]
            )
        )
    ]
)]
class HealthEndpoint
{
    // Clase marcador solo para documentación
}