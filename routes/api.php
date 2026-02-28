<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AnimalController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AdoptionRequestController;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });

        Route::middleware(['auth.api:api'])->group(function () {

        Route::middleware(['role:admin'])->group(function () {
            Route::apiResource('animals', AnimalController::class);

        });

        Route::post('adoption-requests', [AdoptionRequestController::class, 'store']);

        Route::get('adoption-requests', [AdoptionRequestController::class, 'index']);

        Route::patch(
            'adoption-requests/{adoptionRequest}/approve',
            [AdoptionRequestController::class, 'aprobar']
        )->middleware('role:admin');

         Route::patch(
            'adoption-requests/{adoptionRequest}/reject',
            [AdoptionRequestController::class, 'rechazar']
        )->middleware('role:admin');

         Route::patch(
            'adoption-requests/{adoptionRequest}/cancel',
            [AdoptionRequestController::class, 'cancelar']
        );
    });


});
