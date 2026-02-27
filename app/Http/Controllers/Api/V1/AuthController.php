<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Auth\RegisterService;
Use App\Http\Requests\Auth\RegisterRequest;

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
            'data' => $result,
        ], 201);
    }
}
