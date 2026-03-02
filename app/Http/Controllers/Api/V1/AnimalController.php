<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnimalIndexRequest;
use App\Http\Requests\AnimalUpdateRequest;
use App\Http\Resources\AnimalResource;
use App\Services\Animal\AnimalReadService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AnimalStoreRequest;
use App\Services\Animal\AnimalWriteService;
use App\Services\Animal\AnimalUpdateService;
use App\Models\Animal;
use App\Services\Animal\AnimalDeleteService;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Animals",
    description: "Endpoints for managing animals"
)]
class AnimalController extends Controller
{
   public function __construct(
    private AnimalReadService $service
    ) {}

    #[OA\Get(
         path: "/animals",
        summary: "List animals",
        description: "Returns paginated list of animals",
        tags: ["Animals"],
        security: [["BearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "page",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "per_page",
                in: "query",
                schema: new OA\Schema(type: "integer", example: 10)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Animals retrieved successfully",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "data" => [],
                        "meta" => [
                            "current_page" => 1,
                            "last_page" => 1,
                            "per_page" => 10,
                            "total" => 1
                        ]
                    ]
                )
            ),
             new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
           
    public function index(AnimalIndexRequest $request): JsonResponse
    {
        $perPage = $request->validated()['per_page'];

        $animals =$this->service->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => AnimalResource::collection($animals),
            'meta' => [
                'current_page' => $animals->currentPage(),
                'last_page' => $animals->lastPage(),
                'per_page' => $animals->perPage(),
                'total' => $animals->total(),
            ],
        ]);
    }

    #[OA\Get(
        path: "/animals/{id}",
        summary: "Get animal by ID",
        tags: ["Animals"],
        security: [["BearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
         responses: [
            new OA\Response(
                response: 200,
                description: "Animal found",
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: "#/components/schemas/SuccessResponse"),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: "data",
                                    ref: "#/components/schemas/Animal"
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Animal not found",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]

    public function show(Animal $animal): JsonResponse
    {
    return response()->json([
        'success' => true,
        'data' => new AnimalResource($animal),
    ]);
    }

     #[OA\Post(
        path: "/animals",
        summary: "Create animal",
        description: "Creates a new animal (admin only)",
        tags: ["Animals"],
        security: [["BearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nombre", "tipo", "edad", "estado"],
                properties: [
                   new OA\Property(property: "nombre", type: "string", example: "Milo"),
                   new OA\Property(property: "tipo", type: "string", enum: ["Perro", "Gato"]),
                   new OA\Property(property: "edad", type: "integer", example: 2),
                   new OA\Property(property: "estado", type: "string", enum: ["disponible", "adoptado"]),
                   new OA\Property(property: "foto", type: "string", nullable: true)
                ]
            )
        ),
         responses: [
            new OA\Response(
                response: 201,
                description: "Animal created successfully",
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: "#/components/schemas/SuccessResponse"),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: "data",
                                    ref: "#/components/schemas/Animal"
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 403, description: "Forbidden (admin only)"),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            )
        ]
    )]

    public function store(
        AnimalStoreRequest $request,
        AnimalWriteService $writeService
    ): JsonResponse
    {
        $animal = $writeService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => new AnimalResource($animal)
        ], 201);
    }   
    
     #[OA\Put(
        path: "/animals/{id}",
        summary: "Update animal",
        description: "Updates an existing animal (admin only)",
        tags: ["Animals"],
        security: [["BearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Animal ID",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nombre", "tipo", "edad", "estado"],
                properties: [
                    new OA\Property(
                        property: "nombre",
                        type: "string",
                        example: "Kyla"
                    ),
                    new OA\Property(
                        property: "tipo",
                        type: "string",
                        enum: ["Perro", "Gato"],
                        example: "Perro"
                    ),
                    new OA\Property(
                        property: "edad",
                        type: "integer",
                        example: 3
                    ),
                    new OA\Property(
                        property: "estado",
                        type: "string",
                        enum: ["disponible", "adoptado"],
                        example: "disponible"
                    ),
                    new OA\Property(
                        property: "foto",
                        type: "string",
                        format: "uri",
                        nullable: true,
                        example: "https://api.adopciones.com/storage/animals/kyla.jpg"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Animal updated successfully",
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: "#/components/schemas/SuccessResponse"),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: "data",
                                    ref: "#/components/schemas/Animal"
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden (admin only)"
            ),
            new OA\Response(
                response: 404,
                description: "Animal not found",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/ErrorResponse"
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/ErrorResponse"
                )
            )
        ]
    )]

    public function update(
        AnimalUpdateRequest $request,
        Animal $animal,
        AnimalUpdateService $service
    ) {
        $updatedAnimal = $service->execute(
            $animal,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'data' => new AnimalResource($updatedAnimal)
        ]);
    }

    #[OA\Delete(
        path: "/animals/{id}",
        summary: "Delete animal",
        description: "Deletes an animal (admin only)",
        tags: ["Animals"],
        security: [["BearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
       responses: [
            new OA\Response(
                response: 200,
                description: "Animal deleted successfully",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Animal eliminado correctamente"
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 403, description: "Forbidden (admin only)"),
            new OA\Response(
                response: 404,
                description: "Animal not found",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            )
        ]
    )]

    public function destroy(
        Animal $animal,
        AnimalDeleteService $service
        ) {
        $service->execute($animal);

        return response()->json([
            'success' => true,
            'message' => 'Animal eliminado correctamente'
        ]);
     }
}
