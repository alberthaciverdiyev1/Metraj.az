<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: base_path('/Modules/WebUI/Routes/web.php'),
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

//web: function () {
//    require base_path('/Modules/WebUI/Routes/web.php');
//    require base_path('/Modules/AdminUI/Routes/web.php');
//},
