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
        $supportedLocales = ['az', 'en', 'ru', 'tr'];
        $segment = strtolower((string) $request->segment(1));

        // Skip non-public / internal / asset paths
        if (
            $request->is('admin*') ||
            $request->is('agency*') ||
            $request->is('api*') ||
            $request->is('livewire*') ||
            $request->is('lang*') ||
            $request->is('currency*') ||
            $request->is('build*') ||
            $request->is('vendor*') ||
            $request->is('storage*') ||
            $request->is('css*') ||
            $request->is('js*') ||
            $request->is('images*') ||
            $request->is('fonts*') ||
            $request->is('favicon.ico') ||
            $request->is('robots.txt') ||
            $request->is('sitemap*.xml')
        ) {
            return $next($request);
        }

        // If URL starts with a valid language prefix (az, en, ru, tr)
        if (in_array($segment, $supportedLocales, true)) {
            $locale = $segment;
            session(['lang' => $locale]);
            app()->setLocale($locale);
            URL::defaults(['locale' => $locale]);

            return $next($request);
        }

        // If URL has NO language prefix, detect active/session language (default: tr) and redirect to /<locale>/...
        $locale = session('lang', config('app.locale', 'tr'));
        if (!in_array($locale, $supportedLocales, true)) {
            $locale = 'tr';
        }
        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);

        $path = $request->path();
        $targetPath = '/' . $locale . ($path === '/' || $path === '' ? '' : '/' . $path);
        $query = $request->getQueryString() ? '?' . $request->getQueryString() : '';

        return redirect()->to($targetPath . $query);
    }
}
