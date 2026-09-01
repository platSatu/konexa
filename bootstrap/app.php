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
    ->withMiddleware(function (Middleware $middleware): void {
        // Dipasang manual per-route di routes/web.php (bukan global),
        // supaya cuma 5 halaman publik yang kecatat, bukan semua route
        // termasuk /up health check -- lihat App\Http\Middleware\
        // LogVisitorMiddleware.
        $middleware->alias([
            'log.visitor' => \App\Http\Middleware\LogVisitorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
