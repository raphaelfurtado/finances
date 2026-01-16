<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'premium' => \App\Http\Middleware\CheckPremium::class,
            'admin' => \App\Http\Middleware\CheckAdmin::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\LogUserActivity::class,
        ]);

        // Exclude Stripe webhook from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
            'webhook/stripe',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(function (Throwable $e) {
            try {
                \App\Models\SystemError::create([
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => str_replace(base_path(), '', $e->getFile()),
                    'line' => $e->getLine(),
                    'url' => request()->fullUrl(),
                    'user_id' => auth()->id(),
                ]);
            } catch (\Exception $ex) {
                // Fail silently to avoid infinite loops if DB fails
            }
        });
    })->create();
