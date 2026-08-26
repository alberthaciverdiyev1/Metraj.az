{{-- Mobile Top Navbar (Bina.az Style: No Image Logo, Brand Name on Left, Currency, Lang & Green Plus on Right) --}}
<header class="md:hidden bg-white border-b border-gray-200 sticky top-0 z-30 px-3.5 h-12 flex items-center justify-between shadow-2xs select-none">
    {{-- Left: Brand Name Only (No Image Logo, Bina.az font style) --}}
    <a href="{{ route('home') }}" class="shrink-0">
        <span class="text-[17px] font-black tracking-tight text-[#8c4e36] uppercase font-sans">
            KİBRİSKARE<span class="text-orange-500 font-extrabold">.COM</span>
        </span>
    </a>

    {{-- Right: Currency + Language Switchers + Green Post Ad Button --}}
    <div class="flex items-center gap-2.5 shrink-0">
        {{-- Currency Native Select --}}
        <div class="relative flex items-center bg-gray-50/80 hover:bg-gray-100 border border-gray-200/80 rounded-lg px-1.5 py-0.5 transition">
            <select onchange="if (this.value) window.location.href = this.value;" 
                    aria-label="Currency"
                    class="appearance-none bg-transparent text-[11px] font-bold text-gray-700 uppercase tracking-wide pr-3.5 py-0.5 focus:outline-none cursor-pointer border-0">
                @foreach($currencySymbols as $cCode => $cSym)
                    <option value="{{ route('currency.switch', $cCode) }}" {{ $currentCurrency === $cCode ? 'selected' : '' }}>
                        {{ $cCode }}
                    </option>
                @endforeach
            </select>
            <i class="fa-solid fa-chevron-down text-[7px] text-gray-400 pointer-events-none absolute right-1.5"></i>
        </div>

        {{-- Language Native Select (RU, AZ, TR, EN) --}}
        <div class="relative flex items-center bg-gray-50/80 hover:bg-gray-100 border border-gray-200/80 rounded-lg px-1.5 py-0.5 transition">
            <select onchange="if (this.value) window.location.href = this.value;" 
                    aria-label="Language"
                    class="appearance-none bg-transparent text-[11px] font-bold text-gray-700 uppercase tracking-wide pr-3.5 py-0.5 focus:outline-none cursor-pointer border-0">
                @foreach($languages as $langCode => $langData)
                    <option value="{{ route('lang.switch', $langCode) }}" {{ $currentLocale === $langCode ? 'selected' : '' }}>
                        {{ $langData['label'] }}
                    </option>
                @endforeach
            </select>
            <i class="fa-solid fa-chevron-down text-[7px] text-gray-400 pointer-events-none absolute right-1.5"></i>
        </div>

        {{-- Green Circular Plus Button (Bina.az Style) --}}
        <a href="{{ route('add-property') }}" 
           class="w-7.5 h-7.5 rounded-full bg-[#27ae60] hover:bg-[#219653] active:scale-95 text-white flex items-center justify-center shadow-xs transition duration-150 shrink-0" 
           title="{{ __('navbar.post_property') }}">
            <i class="fa-solid fa-plus text-sm font-black"></i>
        </a>
    </div>
</header>
