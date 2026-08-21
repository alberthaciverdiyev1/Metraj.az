<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public const array VALID_LANGUAGES = ['az', 'en', 'ru'];

    public const array VALID_CURRENCIES = ['AZN', 'USD', 'EUR', 'GBP', 'TRY', 'RUB', 'AED'];

    public function switchLanguage(string $locale): RedirectResponse
    {
        if (in_array($locale, self::VALID_LANGUAGES)) {
            session(['lang' => $locale]);
            app()->setLocale($locale);
        }

        return redirect()->back();
    }

    public function switchCurrency(string $code): RedirectResponse
    {
        $code = strtoupper($code);

        if (in_array($code, self::VALID_CURRENCIES)) {
            session(['currency' => $code]);
        }

        return redirect()->back();
    }
}
