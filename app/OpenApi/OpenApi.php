<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;


#[OA\OpenApi(
    openapi: '3.0.3',
    security: [
        ['BearerAuth' => []]
    ]
)]
#[OA\Info(
    title: "API Adopciones",
    version: "1.0.0",
    description: "API REST profesional para gestión de adopciones"
)]
#[OA\Server(
    url: '/api/v1',
    description: 'API Version 1'
)]
#[OA\SecurityScheme(
    securityScheme: 'BearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
class OpenApi 
{
     // Documento raíz OpenAPI
}