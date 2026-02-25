<?php

use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
    )
     ->withMiddleware(function (Middleware $middleware) {
         $middleware->alias([
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
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
