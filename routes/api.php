<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AnimalController;

Route::prefix('v1')->group(function () {

    Route::apiResource('animals', AnimalController::class);
});