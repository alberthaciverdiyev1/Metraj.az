@php
    $currentCurrency = session('currency', 'AZN');
    $currencySymbols = \App\Modules\Shared\Enums\Currency::getSymbols();
    $currentLocale = app()->getLocale() ?? session('lang', config('app.locale', 'tr'));
    $languages = \App\Modules\Shared\Enums\SupportedLocale::getList();
    $activeLang = $languages[$currentLocale] ?? $languages['tr'];
@endphp

@include('layouts.partials.desktop-navbar')

@include('layouts.partials.mobile-top-navbar')

@include('layouts.partials.mobile-bottom-nav')

@include('layouts.partials.mobile-drawer')

<script src="{{ asset('js/layouts/navbar.js') }}"></script>
