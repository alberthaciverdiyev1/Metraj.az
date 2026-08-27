<?php

namespace App\Modules\Shared\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $segment = strtolower((string) $request->segment(1));
        $supportedLocales = ['az', 'en', 'ru', 'tr'];

        if (in_array($segment, $supportedLocales, true)) {
            $locale = $segment;
            session(['lang' => $locale]);
            app()->setLocale($locale);
        } else {
            $locale = session('lang', config('app.locale', 'tr'));
            if (!in_array($locale, $supportedLocales, true)) {
                $locale = config('app.locale', 'tr');
            }
            app()->setLocale($locale);
        }

        // Set default URL parameter for {locale?} so all route(...) calls preserve the current locale!
        if ($locale !== 'tr' || $segment === 'tr') {
            URL::defaults(['locale' => $locale]);
        } else {
            URL::defaults(['locale' => null]);
        }

        return $next($request);
    }
}
