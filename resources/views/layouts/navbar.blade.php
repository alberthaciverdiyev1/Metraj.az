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
    $currentLocale = session('lang', app()->getLocale() ?? 'az');
    $languages = [
      'az' => ['name' => 'Azərbaycan', 'flag' => '🇦🇿', 'label' => 'AZ'],
      'en' => ['name' => 'English', 'flag' => '🇬🇧', 'label' => 'EN'],
      'ru' => ['name' => 'Русский', 'flag' => '🇷🇺', 'label' => 'RU'],
    ];
    $activeLang = $languages[$currentLocale] ?? $languages['az'];
@endphp

@include('layouts.partials.desktop-navbar')

@include('layouts.partials.mobile-bottom-nav')

@include('layouts.partials.mobile-drawer')

<script src="{{ asset('js/layouts/navbar.js') }}"></script>
