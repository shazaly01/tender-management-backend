<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // --- [بداية إضافة CORS] ---
        // تسجيل HandleCors كـ Middleware عالمي.
        // سيقرأ إعداداته من ملف config/cors.php الذي أنشأناه.
        $middleware->use([
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
        // --- [نهاية إضافة CORS] ---


        // تعطيل حماية CSRF لمسارات الـ API
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
