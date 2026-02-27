<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Auth\RegisterService;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
class AuthController extends Controller
{
   public function __construct(
        private RegisterService $registerService
    ) {
    }

    public function register(RegisterRequest $request)
    {
    $result = $this->registerService->register(
        $request->validated()
    );

        return response()->json([
            'success' => true,
            'data' => new AuthResource($result),
        ], 201);
    }
}
