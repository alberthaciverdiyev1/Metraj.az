<?php

namespace App\Modules\Shared\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Shared\Enums\Currency;
use App\Modules\Shared\Enums\SupportedLocale;
use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function switchLanguage(string $locale): RedirectResponse
    {
        $locale = strtolower(trim($locale));

        if (SupportedLocale::isValid($locale)) {
            session(['lang' => $locale]);
            app()->setLocale($locale);
        }

        return redirect()->back();
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
