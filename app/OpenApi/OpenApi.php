<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "API Adopciones",
    version: "1.0.0",
    description: "API REST profesional para gestión de adopciones"
)]
#[OA\Server(
    url: "http://127.0.0.1:8000",
    description: "Servidor local"
)]
#[OA\Get(
    path: "/health",
    summary: "Health check",
    tags: ["System"],
    responses: [
        new OA\Response(
            response: 200,
            description: "OK"
        )
    ]
)]
class OpenApi {}