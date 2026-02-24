<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AnimalController;
//temporal
use Illuminate\Http\Request;

Route::prefix('v1')->group(function() {

    Route::middleware('auth')->group(function (){

        Route::get('/auth-check', function () {
            return response()->json([
            'success' => true,
            'message' => 'Authenticated'
        ]);
    
    });

  Route::get('/animals', [AnimalController::class, 'index']);

  });

});

//Temporal
Route::middleware('auth')->get('/auth-test', function (Request $request) {
    return response()->json([
        'success' => true,
        'data' => [
            'user' => $request->user(),
        ],
    ]);
});
