@php
    $profileUrl = \App\Filament\Pages\EditProfile::getUrl();
    $currentLocale = session('lang', config('app.locale', 'tr'));
    $currentCurrency = session('currency', 'GBP');

    $locales = [
        'tr' => ['name' => 'Türkçe', 'short' => 'TR', 'flag' => '🇹🇷'],
        'az' => ['name' => 'Azərbaycan', 'short' => 'AZ', 'flag' => '🇦🇿'],
        'en' => ['name' => 'English', 'short' => 'EN', 'flag' => '🇬🇧'],
        'ru' => ['name' => 'Русский', 'short' => 'RU', 'flag' => '🇷🇺'],
    ];

    $currencies = [
        'GBP' => ['symbol' => '£', 'label' => 'GBP (£)'],
        'USD' => ['symbol' => '$', 'label' => 'USD ($)'],
        'EUR' => ['symbol' => '€', 'label' => 'EUR (€)'],
        'TRY' => ['symbol' => '₺', 'label' => 'TRY (₺)'],
        'AZN' => ['symbol' => '₼', 'label' => 'AZN (₼)'],
        'RUB' => ['symbol' => '₽', 'label' => 'RUB (₽)'],
    ];
@endphp

<div class="flex items-center gap-1.5 sm:gap-2 mr-2">

    {{-- Currency Switcher Dropdown --}}
    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
        <button type="button" @click="open = !open"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700 cursor-pointer">
            <span class="text-orange-500 font-extrabold text-sm">{{ $currencies[$currentCurrency]['symbol'] ?? '£' }}</span>
            <span>{{ $currentCurrency }}</span>
            <svg class="w-3 h-3 opacity-60 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 z-50 mt-1.5 w-36 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-1.5 overflow-hidden">
            @foreach($currencies as $code => $item)
                <a href="{{ route('currency.switch', ['code' => $code]) }}"
                   class="flex items-center justify-between px-3 py-1.5 text-xs font-medium {{ $currentCurrency === $code ? 'bg-orange-50 text-orange-600 dark:bg-orange-950/40 dark:text-orange-400 font-bold' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }} transition">
                    <span class="flex items-center gap-1.5">
                        <span class="font-bold text-orange-500">{{ $item['symbol'] }}</span>
                        <span>{{ $code }}</span>
                    </span>
                    @if($currentCurrency === $code)
                        <svg class="w-3.5 h-3.5 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- Language Switcher Dropdown --}}
    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
        <button type="button" @click="open = !open"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700 cursor-pointer">
            <span>{{ $locales[$currentLocale]['flag'] ?? '🇹🇷' }}</span>
            <span class="uppercase">{{ $locales[$currentLocale]['short'] ?? 'TR' }}</span>
            <svg class="w-3 h-3 opacity-60 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 z-50 mt-1.5 w-36 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-1.5 overflow-hidden">
            @foreach($locales as $lang => $item)
                <a href="{{ route('lang.switch', ['lang' => $lang]) }}"
                   class="flex items-center justify-between px-3 py-1.5 text-xs font-medium {{ $currentLocale === $lang ? 'bg-orange-50 text-orange-600 dark:bg-orange-950/40 dark:text-orange-400 font-bold' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }} transition">
                    <span class="flex items-center gap-1.5">
                        <span>{{ $item['flag'] }}</span>
                        <span>{{ $item['name'] }}</span>
                    </span>
                    @if($currentLocale === $lang)
                        <svg class="w-3.5 h-3.5 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- Profilim (Masaüstü görünüm) --}}
    <a href="{{ $profileUrl }}"
       class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700">
        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        <span>{{ __('panel.edit_profile') }}</span>
    </a>

    {{-- Sayta Keçid (Masaüstü görünüm) --}}
    <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer"
       class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700">
        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
        </svg>
        <span>{{ __('panel.visit_site') }}</span>
    </a>
</div>
