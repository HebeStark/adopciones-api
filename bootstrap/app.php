<?php

use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
    )
     ->withMiddleware(function (Middleware $middleware) {
        
         $middleware->alias([
            'auth.api' => \App\Http\Middleware\ApiAuthenticate::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
         ]);
    })   
    ->withExceptions(function ($exceptions) {
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        });
    })->create();
