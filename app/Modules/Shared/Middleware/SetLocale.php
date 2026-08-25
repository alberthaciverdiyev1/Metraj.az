<?php

namespace App\Modules\Shared\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('lang', config('app.locale', 'tr'));

        if (!in_array($locale, ['az', 'en', 'ru', 'tr'])) {
            $locale = config('app.locale', 'tr');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
