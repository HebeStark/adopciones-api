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
                required: false,
                schema: new OA\Schema(type: "integer", example: 20)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful response"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
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
            new OA\Response(response: 200, description: "Animal found"),
            new OA\Response(response: 404, description: "Animal not found")
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
        tags: ["Animals"],
        security: [["BearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nombre", "tipo", "edad"],
                properties: [
                    new OA\Property(property: "nombre", type: "string", example: "Milo"),
                    new OA\Property(property: "tipo", type: "string", example: "perro"),
                    new OA\Property(property: "edad", type: "integer", example: 2),
                    new OA\Property(property: "descripcion", type: "string", nullable: true),
                    new OA\Property(property: "foto", type: "string", nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Animal created"),
            new OA\Response(response: 422, description: "Validation error")
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
            new OA\Response(response: 200, description: "Animal updated"),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 404, description: "Animal not found")
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
            new OA\Response(response: 200, description: "Animal deleted"),
            new OA\Response(response: 404, description: "Animal not found")
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
