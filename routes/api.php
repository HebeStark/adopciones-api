<?php
Use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {

Route::middleware('auth:api')->get('/auth-check', function () {
    return response()->json([
        'success' => true,
        'message' => 'Authenticated'
    ]);
});
});

