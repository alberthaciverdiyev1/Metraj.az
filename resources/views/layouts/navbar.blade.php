@php
    $currentCurrency = session('currency', 'AZN');
    $currencySymbols = [
      'AZN' => '₼',
      'USD' => '$',
      'EUR' => '€',
      'GBP' => '£',
      'TRY' => '₺',
      'RUB' => '₽',
      'AED' => 'د.إ',
    ];
    $currentLocale = app()->getLocale() ?? session('lang', config('app.locale', 'tr'));
    $languages = [
      'tr' => ['name' => 'Türkçe', 'flag' => '🇹🇷', 'label' => 'TR'],
      'az' => ['name' => 'Azərbaycan', 'flag' => '🇦🇿', 'label' => 'AZ'],
      'en' => ['name' => 'English', 'flag' => '🇬🇧', 'label' => 'EN'],
      'ru' => ['name' => 'Русский', 'flag' => '🇷🇺', 'label' => 'RU'],
    ];
    $activeLang = $languages[$currentLocale] ?? $languages['tr'];
@endphp

@include('layouts.partials.desktop-navbar')

@include('layouts.partials.mobile-top-navbar')

@include('layouts.partials.mobile-bottom-nav')

@include('layouts.partials.mobile-drawer')

<script src="{{ asset('js/layouts/navbar.js') }}"></script>
