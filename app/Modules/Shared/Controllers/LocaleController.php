<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Shared\Enums\Currency;
use App\Modules\Shared\Enums\SupportedLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switchLanguage(Request $request, ?string $lang = null, ?string $locale = null): RedirectResponse
    {
        $targetLocale = strtolower(trim($lang ?? $locale ?? ''));
        $supportedLocales = ['az', 'en', 'ru', 'tr'];

        if (!in_array($targetLocale, $supportedLocales, true)) {
            return redirect()->back();
        }

        session(['lang' => $targetLocale]);
        app()->setLocale($targetLocale);

        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);
        $path = $parsedUrl['path'] ?? '/';
        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

        // Do not alter admin, agency, or API paths
        if (
            str_starts_with($path, '/admin') ||
            str_starts_with($path, '/agency') ||
            str_starts_with($path, '/api') ||
            str_starts_with($path, '/livewire')
        ) {
            return redirect()->to($path . $query);
        }

        // Strip existing locale prefix if present
        $segments = explode('/', trim($path, '/'));
        if (!empty($segments) && in_array($segments[0], $supportedLocales, true)) {
            array_shift($segments);
        }

        $cleanPath = implode('/', $segments);

        // Build target URL
        if ($locale === 'tr') {
            // Default Turkish can be root or /tr
            $targetUrl = '/' . $cleanPath;
        } else {
            $targetUrl = '/' . $locale . ($cleanPath !== '' ? '/' . $cleanPath : '');
        }

        return redirect()->to($targetUrl . $query);
    }

    public function switchCurrency(string $code): RedirectResponse
    {
        $code = strtoupper(trim($code));

        if (Currency::isValid($code)) {
            session(['currency' => $code]);
        }

        return redirect()->back();
    }
}
