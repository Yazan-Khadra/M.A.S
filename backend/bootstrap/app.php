<?php

use App\Http\Middleware\AddminMiddleware;
use App\Http\Middleware\Cors;
use App\Http\Middleware\ImageMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Apply CORS and Image middleware globally
        $middleware->use([
            Cors::class,
            ImageMiddleware::class
        ]);
        
        // Your other middleware configurations
        $middleware->group('api', []);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
