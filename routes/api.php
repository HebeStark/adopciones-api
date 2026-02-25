<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AnimalController;

Route::prefix('v1')->group(function () {

    Route::get('/animals', [AnimalController::class, 'index']);

    Route::get('/animals/{animal}', [AnimalController::class, 'show']);

});