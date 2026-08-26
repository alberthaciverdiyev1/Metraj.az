{{-- Mobile Top Navbar (Bina.az Style: Clean Typography, Elegant Pills for Currency & Lang, Green Plus) --}}
<header class="md:hidden bg-white border-b border-gray-200/90 sticky top-0 z-30 px-3.5 h-12 flex items-center justify-between shadow-2xs select-none">
    {{-- Left: Brand Name Only (Bina.az clean typography style) --}}
    <a href="{{ route('home') }}" class="shrink-0 flex items-center">
        <span class="text-[17px] font-black tracking-tight text-[#7c3a21] uppercase font-sans">
            KİBRİSKARE<span class="text-orange-500 font-black">.COM</span>
        </span>
    </a>

    {{-- Right: Currency + Language Styled Pills + Green Post Ad Button --}}
    <div class="flex items-center gap-2 shrink-0">
        {{-- Currency Pill (Pixel-Perfect Custom UI + 100% Native Mobile Tap) --}}
        <div class="relative flex items-center gap-1 bg-gray-100 hover:bg-gray-200/70 active:bg-gray-200 transition-all rounded-lg px-2 py-1 cursor-pointer">
            <span class="text-[11px] font-black text-orange-500 leading-none">
                {{ $currencySymbols[$currentCurrency] ?? '₼' }}
            </span>
            <span class="text-[11px] font-bold text-gray-700 leading-none tracking-tight">
                {{ $currentCurrency }}
            </span>
            <i class="fa-solid fa-chevron-down text-[7px] text-gray-400 ml-0.5 pointer-events-none"></i>

            {{-- Full-surface touch overlay --}}
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

        {{-- Language Pill (Pixel-Perfect Custom UI + 100% Native Mobile Tap) --}}
        <div class="relative flex items-center gap-1 bg-gray-100 hover:bg-gray-200/70 active:bg-gray-200 transition-all rounded-lg px-2 py-1 cursor-pointer">
            <span class="text-xs leading-none">
                {{ $activeLang['flag'] ?? '🌐' }}
            </span>
            <span class="text-[11px] font-extrabold text-gray-700 leading-none uppercase tracking-wider">
                {{ $activeLang['label'] ?? strtoupper($currentLocale) }}
            </span>
            <i class="fa-solid fa-chevron-down text-[7px] text-gray-400 ml-0.5 pointer-events-none"></i>

            {{-- Full-surface touch overlay --}}
            <select onchange="if (this.value) window.location.href = this.value;" 
                    aria-label="Language"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 text-base">
                @foreach($languages as $langCode => $langData)
                    <option value="{{ route('lang.switch', $langCode) }}" {{ $currentLocale === $langCode ? 'selected' : '' }}>
                        {{ $langData['flag'] }} {{ $langData['name'] }} ({{ $langData['label'] }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Green Circular Plus Button (Bina.az Style) --}}
        <a href="{{ route('add-property') }}" 
           class="w-7.5 h-7.5 rounded-full bg-[#27ae60] hover:bg-[#219653] active:scale-90 text-white flex items-center justify-center shadow-xs transition duration-150 shrink-0 ml-0.5" 
           title="{{ __('navbar.post_property') }}">
            <i class="fa-solid fa-plus text-sm font-black"></i>
        </a>
    </div>
</header>
