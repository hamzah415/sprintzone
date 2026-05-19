<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Definisikan semua Alias dalam satu tempat
        $middleware->alias([
            '2fa'          => \App\Http\Middleware\Google2FAMiddleware::class,
            'isAdmin'      => \App\Http\Middleware\IsAdmin::class,
            'prevent-back' => \App\Http\Middleware\PreventBackHistory::class,
        ]);

        // 2. Atur Redirect (Hati-hati: guests ke /login bisa memicu loop jika /login adalah /)
        $middleware->redirectTo(
            guests: '/login',
            users: '/dashboard' // Disarankan ke dashboard dulu, baru user pilih menu
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();