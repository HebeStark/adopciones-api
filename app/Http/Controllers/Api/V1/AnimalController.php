<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnimalIndexRequest;
use App\Http\Resources\AnimalResource;
use App\Services\Animal\AnimalReadService;
use Illuminate\Http\JsonResponse;

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
   

}
