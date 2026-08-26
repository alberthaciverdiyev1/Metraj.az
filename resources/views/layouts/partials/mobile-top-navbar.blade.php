{{-- Mobile Top Navbar (Bina.az Style: Compact, Logo on Left, Lang & Green Plus on Right) --}}
<header class="md:hidden bg-white border-b border-gray-200 sticky top-0 z-30 px-3.5 h-12 flex items-center justify-between shadow-xs select-none">
    {{-- Left: Brand Logo & Name --}}
    <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 group">
        <img class="h-6.5 w-auto object-contain" src="{{ asset('images/kibriskarelogo1.png') }}" alt="KibrisKare.com" />
        <span class="text-base font-extrabold tracking-tight text-[#484848] uppercase">
            KibrisKare<span class="text-orange-500 font-black">.com</span>
        </span>
    </a>

    {{-- Right: Language Switcher & Green Post Ad Button --}}
    <div class="flex items-center gap-3 shrink-0">
        {{-- Language Selector (Compact Text e.g. RU, AZ, TR, EN) --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button type="button" 
                    @click="open = !open" 
                    class="text-xs font-bold text-gray-700 hover:text-gray-900 flex items-center gap-1 uppercase tracking-wider py-1 px-1.5 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <span>{{ $activeLang['label'] ?? strtoupper($currentLocale) }}</span>
                <i class="fa-solid fa-chevron-down text-[9px] text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>

            {{-- Mobile Lang Dropdown Menu --}}
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                 x-cloak
                 class="absolute right-0 mt-1.5 w-28 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50 overflow-hidden">
                @foreach($languages as $langCode => $langData)
                    <a href="{{ route('lang.switch', $langCode) }}" 
                       class="flex items-center justify-between px-3 py-1.5 text-xs font-medium transition {{ $currentLocale === $langCode ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <span>{{ $langData['name'] }}</span>
                        <span class="text-[10px] uppercase font-bold {{ $currentLocale === $langCode ? 'text-orange-500' : 'text-gray-400' }}">{{ $langData['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Green Circular Plus Button (Bina.az Style) --}}
        <a href="{{ route('add-property') }}" 
           class="w-7.5 h-7.5 rounded-full bg-[#27ae60] hover:bg-[#219653] active:scale-95 text-white flex items-center justify-center shadow-xs transition duration-150" 
           title="{{ __('navbar.post_property') }}">
            <i class="fa-solid fa-plus text-sm font-black"></i>
        </a>
    </div>
</header>
