<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AnimalController;

Route::prefix('v1')->group(function() {

    Route::middleware('auth:api')->group(function (){

        Route::get('/auth-check', function () {
            return response()->json([
            'success' => true,
            'message' => 'Authenticated'
        ]);
    
    });

  Route::get('/animals', [AnimalController::class, 'index']);

  });

});

