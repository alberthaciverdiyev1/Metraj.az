<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Modules\Shared\Middleware\SetLocale::class,
            \App\Modules\Shared\Middleware\LogActivityMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'api/telegram/webhook',
            '*reveal-phone*',
            '*/listings/*/reveal-phone',
            'listings/*/reveal-phone',
            '*/properties/*/reveal-phone',
            'properties/*/reveal-phone',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, Request $request) {
            $msg = 'Yüklənən faylların ümumi həcmi çox böyükdür (maksimum 100MB). Zəhmət olmasa daha az və ya kiçik ölçülü fayl seçin.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'errors' => [
                        'photos' => [$msg],
                    ],
                ], 422);
            }
            return back()->with('error', $msg);
        });
    })->create();
