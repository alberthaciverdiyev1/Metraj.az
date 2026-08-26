{{-- Mobile Top Navbar: Refined Minimalist Design with Semibold Brand & Elegant Controls --}}
<header class="md:hidden bg-white/95 backdrop-blur-md border-b border-gray-200/80 sticky top-0 z-30 px-3.5 h-13 flex items-center justify-between shadow-[0_1px_4px_rgba(0,0,0,0.03)] select-none">
    {{-- Left: Brand Name (Semibold) --}}
    <a href="{{ route('home') }}" class="shrink-0 flex items-center group">
        <span class="text-[17px] font-semibold text-gray-800 tracking-tight font-sans transition-colors group-hover:text-gray-900">
            KibrisKare<span class="text-orange-500 font-semibold">.com</span>
        </span>
    </a>

    {{-- Right: Currency + Language Elegant Badges + Green Post Ad Button --}}
    <div class="flex items-center gap-2 shrink-0">
        {{-- Currency Switcher Pill --}}
        <div class="relative flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100/90 active:bg-gray-100 border border-gray-200/70 rounded-xl px-2.5 py-1.5 transition-all shadow-2xs cursor-pointer">
            <span class="text-[11px] font-bold text-orange-500 leading-none">
                {{ $currencySymbols[$currentCurrency] ?? '₼' }}
            </span>
            <span class="text-[11px] font-semibold text-gray-700 leading-none tracking-tight">
                {{ $currentCurrency }}
            </span>
            <i class="fa-solid fa-chevron-down text-[7px] text-gray-400 pointer-events-none"></i>

            {{-- Native Full-Surface Touch Trigger --}}
            <select onchange="if (this.value) window.location.href = this.value;" 
                    aria-label="Currency"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 text-base">
                @foreach($currencySymbols as $cCode => $cSym)
                    <option value="{{ route('currency.switch', $cCode) }}" {{ $currentCurrency === $cCode ? 'selected' : '' }}>
                        {{ $cSym }} {{ $cCode }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Language Switcher Pill --}}
        <div class="relative flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100/90 active:bg-gray-100 border border-gray-200/70 rounded-xl px-2.5 py-1.5 transition-all shadow-2xs cursor-pointer">
            <span class="text-[11px] font-semibold uppercase text-gray-700 leading-none tracking-wider">
                {{ $activeLang['label'] ?? strtoupper($currentLocale) }}
            </span>
            <i class="fa-solid fa-chevron-down text-[7px] text-gray-400 pointer-events-none"></i>

            {{-- Native Full-Surface Touch Trigger --}}
            <select onchange="if (this.value) window.location.href = this.value;" 
                    aria-label="Language"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 text-base">
                @foreach($languages as $langCode => $langData)
                    <option value="{{ route('lang.switch', $langCode) }}" {{ $currentLocale === $langCode ? 'selected' : '' }}>
                        {{ $langData['name'] }} ({{ $langData['label'] }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Green Circular Plus Button --}}
        <a href="{{ route('add-property') }}" 
           class="w-8 h-8 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-90 text-white flex items-center justify-center shadow-sm shadow-emerald-600/20 transition-all duration-150 shrink-0 ml-0.5" 
           title="{{ __('navbar.post_property') }}">
            <i class="fa-solid fa-plus text-sm font-black"></i>
        </a>
    </div>
</header>
