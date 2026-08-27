{{-- Mobile Top Navbar: Semibold Brand on Left, Currency & Language Dropdowns on Right (No Plus Button) --}}
<header class="md:hidden bg-white border-b border-gray-200/80 sticky top-0 z-30 px-3.5 h-13 flex items-center justify-between shadow-2xs select-none">
    {{-- Left: Brand Name (Semibold) --}}
    <a href="{{ route('home') }}" class="shrink-0 flex items-center group">
        <span class="text-[17px] font-semibold text-gray-800 tracking-tight font-sans transition-colors group-hover:text-gray-900">
            KibrisKare<span class="text-orange-500 font-semibold">.com</span>
        </span>
    </a>

    {{-- Right: Currency + Language Dropdowns --}}
    <div class="flex items-center gap-2 shrink-0">
        {{-- Currency Custom Dropdown --}}
        <div class="relative" id="mobileCurrencyContainer">
            <button id="mobileNavCurrencyBtn" 
                    type="button"
                    onclick="toggleMobileNavbarMenu('mobileNavCurrencyDropdown', 'mobileNavCurrencyChevron', event)"
                    class="flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100 active:bg-gray-200/70 border border-gray-200/80 rounded-xl px-2.5 py-1.5 transition-all shadow-2xs cursor-pointer select-none">
                <span class="text-[11px] font-bold text-orange-500 leading-none">
                    {{ $currencySymbols[$currentCurrency] ?? '₼' }}
                </span>
                <span class="text-[11px] font-semibold text-gray-800 leading-none tracking-tight">
                    {{ $currentCurrency }}
                </span>
                <i id="mobileNavCurrencyChevron" class="fa-solid fa-chevron-down text-[8px] text-gray-400 transition-transform duration-200 pointer-events-none"></i>
            </button>

            {{-- Currency Menu --}}
            <div id="mobileNavCurrencyDropdown" 
                 class="hidden absolute right-0 mt-2 w-44 bg-white rounded-2xl shadow-2xl border border-gray-100 py-1.5 z-50 overflow-hidden">
                <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 border-b border-gray-100 mb-1">
                    {{ __('navbar.currency') ?? 'Valyuta' }}
                </div>
                @foreach($currencySymbols as $cCode => $cSym)
                    <a href="{{ route('currency.switch', ['code' => $cCode]) }}" 
                       class="flex items-center justify-between px-3 py-2 text-xs transition {{ $currentCurrency === $cCode ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg {{ $currentCurrency === $cCode ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center font-bold text-xs">
                                {{ $cSym }}
                            </span>
                            <span>{{ $cCode }}</span>
                        </div>
                        @if($currentCurrency === $cCode)
                            <i class="fa-solid fa-check text-[11px] text-orange-500"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Language Custom Dropdown --}}
        <div class="relative" id="mobileLangContainer">
            <button id="mobileNavLangBtn" 
                    type="button"
                    onclick="toggleMobileNavbarMenu('mobileNavLangDropdown', 'mobileNavLangChevron', event)"
                    class="flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100 active:bg-gray-200/70 border border-gray-200/80 rounded-xl px-2.5 py-1.5 transition-all shadow-2xs cursor-pointer select-none">
                <span class="text-xs leading-none">
                    {{ $activeLang['flag'] ?? '🌐' }}
                </span>
                <span class="text-[11px] font-semibold text-gray-800 leading-none uppercase tracking-wider">
                    {{ $activeLang['label'] ?? strtoupper($currentLocale) }}
                </span>
                <i id="mobileNavLangChevron" class="fa-solid fa-chevron-down text-[8px] text-gray-400 transition-transform duration-200 pointer-events-none"></i>
            </button>

            {{-- Language Menu --}}
            <div id="mobileNavLangDropdown" 
                 class="hidden absolute right-0 mt-2 w-44 bg-white rounded-2xl shadow-2xl border border-gray-100 py-1.5 z-50 overflow-hidden">
                <div class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 border-b border-gray-100 mb-1">
                    {{ __('navbar.language') ?? 'Dil' }}
                </div>
                @foreach($languages as $langCode => $langData)
                    <a href="{{ route('lang.switch', ['lang' => $langCode]) }}" 
                       class="flex items-center justify-between px-3 py-2 text-xs transition {{ $currentLocale === $langCode ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="text-base leading-none">{{ $langData['flag'] }}</span>
                            <span>{{ $langData['name'] }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] uppercase font-bold {{ $currentLocale === $langCode ? 'text-orange-500' : 'text-gray-400' }}">{{ $langData['label'] }}</span>
                            @if($currentLocale === $langCode)
                                <i class="fa-solid fa-check text-[11px] text-orange-500"></i>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</header>

<script>
function toggleMobileNavbarMenu(menuId, chevronId, event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }
    var menu = document.getElementById(menuId);
    var chevron = document.getElementById(chevronId);
    var allDropdowns = ['mobileNavCurrencyDropdown', 'mobileNavLangDropdown'];
    var allChevrons = ['mobileNavCurrencyChevron', 'mobileNavLangChevron'];

    allDropdowns.forEach(function(id) {
        if (id !== menuId) {
            var el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        }
    });

    allChevrons.forEach(function(id) {
        if (id !== chevronId) {
            var el = document.getElementById(id);
            if (el) el.classList.remove('rotate-180');
        }
    });

    if (menu) {
        var isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden', !isHidden);
        if (chevron) chevron.classList.toggle('rotate-180', isHidden);
    }
}

document.addEventListener('click', function(event) {
    var cContainer = document.getElementById('mobileCurrencyContainer');
    var lContainer = document.getElementById('mobileLangContainer');
    
    if (cContainer && !cContainer.contains(event.target)) {
        var cMenu = document.getElementById('mobileNavCurrencyDropdown');
        var cChev = document.getElementById('mobileNavCurrencyChevron');
        if (cMenu) cMenu.classList.add('hidden');
        if (cChev) cChev.classList.remove('rotate-180');
    }
    if (lContainer && !lContainer.contains(event.target)) {
        var lMenu = document.getElementById('mobileNavLangDropdown');
        var lChev = document.getElementById('mobileNavLangChevron');
        if (lMenu) lMenu.classList.add('hidden');
        if (lChev) lChev.classList.remove('rotate-180');
    }
});
</script>
