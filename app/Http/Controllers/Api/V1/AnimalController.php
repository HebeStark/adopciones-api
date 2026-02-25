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

class AnimalController extends Controller
{
   public function __construct(
    private AnimalReadService $service
    ) {}

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

    public function show(Animal $animal): JsonResponse
    {
    return response()->json([
        'success' => true,
        'data' => new AnimalResource($animal),
    ]);
    }

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
