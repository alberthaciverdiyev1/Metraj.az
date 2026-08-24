<header class="bg-white border-b border-gray-200 sticky top-0 z-30">
  <div class="ml-4 mx-auto h-17.5 flex items-center justify-between gap-4">

    <!-- Logo -->
    <a href="/" class="flex items-center space-x-2 shrink-0">
      <img class="h-9 w-auto object-contain" src="/images/metrajlogo1.png" alt="Metraj.az" />
      <div class="leading-tight">
        <div class="text-xl font-extrabold text-[#545454] tracking-tight">Metraj.az</div>
        <div class="text-[8px] text-gray-400 uppercase tracking-[0.18em]">sənin əmlakın</div>
      </div>
    </a>

    <!-- Desktop Navigation Links (Always visible on md and up) -->
    <nav class="hidden md:flex items-center space-x-5 lg:space-x-7 text-[15px] font-medium">
      <a href="/listing?deal_type=sale" class="{{ request()->is('listing*') && request('deal_type') === 'sale' ? 'text-[#f1913d] font-bold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('Alqı-satqı') }}
      </a>
      <a href="/listing?deal_type=rent_monthly" class="{{ request()->is('listing*') && request('deal_type') === 'rent_monthly' ? 'text-[#f1913d] font-bold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('Kirayə') }}
      </a>
      <a href="/listing?deal_type=rent_daily" class="{{ request()->is('listing*') && request('deal_type') === 'rent_daily' ? 'text-[#f1913d] font-bold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('Günlük') }}
      </a>
      <a href="/axtariram" class="{{ request()->is('axtariram*') || request()->is('otaq-yoldasi*') ? 'text-[#f1913d] font-bold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('Axtarıram') }}
      </a>
      <a href="/agencies" class="{{ request()->is('agencies*') || request()->is('agentlik*') ? 'text-[#f1913d] font-bold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('Agencies') }}
      </a>
      <a href="/contact" class="{{ request()->is('contact*') ? 'text-[#f1913d] font-bold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('Contact') }}
      </a>
    </nav>

    <!-- Right Actions -->
    <div class="flex items-center space-x-2.5 sm:space-x-3.5">

      <!-- Favorites -->
      <a href="/favorites" class="relative text-gray-700 hover:text-orange-500 p-2 rounded-xl transition inline-flex items-center justify-center hover:bg-gray-50" title="{{ __('Favorites') }}">
        <i class="fa-regular fa-heart text-xl text-rose-500"></i>
        <span id="favorites-count" class="absolute -top-1 -right-1 bg-orange-500 text-white text-[10px] min-w-[16px] h-4 px-1 flex items-center justify-center rounded-full font-bold shadow-sm">0</span>
      </a>

      <!-- Compares -->
      <a href="/compares" class="relative text-gray-700 hover:text-orange-500 p-2 rounded-xl transition inline-flex items-center justify-center hover:bg-gray-50" title="{{ __('Compare') }}">
        <i class="bi bi-arrow-left-right text-xl text-gray-700"></i>
        <span id="compares-count" class="absolute top-0 -right-1 bg-orange-500 text-white text-[10px] min-w-[16px] h-4 px-1 flex items-center justify-center rounded-full font-bold shadow-sm">0</span>
      </a>

      <!-- Currency Custom Dropdown -->
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
      @endphp
      <div class="relative">
        <button id="navCurrencyBtn" type="button"
                class="flex items-center gap-1.5 px-2.5 py-1.5 bg-gray-50 hover:bg-gray-100/80 border border-gray-200/90 rounded-xl text-xs font-bold text-gray-800 transition shadow-2xs cursor-pointer select-none">
          <span class="text-gray-500 font-extrabold">{{ $currencySymbols[$currentCurrency] ?? '₼' }}</span>
          <span>{{ $currentCurrency }}</span>
          <i class="bi bi-chevron-down text-[10px] text-gray-400 transition-transform duration-200" id="navCurrencyChevron"></i>
        </button>

        <div id="navCurrencyDropdown"
             class="hidden absolute right-0 mt-2 w-36 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden">
          @foreach($currencySymbols as $cCode => $cSym)
            <a href="/currency/{{ $cCode }}"
               class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold {{ $currentCurrency === $cCode ? 'text-[#f1913d] bg-orange-50/60 font-bold' : 'text-gray-700 hover:bg-gray-50' }} transition">
              <span class="flex items-center gap-2">
                <span class="w-4 text-center font-bold text-gray-400">{{ $cSym }}</span>
                <span>{{ $cCode }}</span>
              </span>
              @if($currentCurrency === $cCode)
                <i class="bi bi-check2 text-sm text-[#f1913d]"></i>
              @endif
            </a>
          @endforeach
        </div>
      </div>

      <!-- Language Custom Dropdown with Flags -->
      @php
        $currentLocale = session('lang', app()->getLocale() ?? 'az');
        $languages = [
          'az' => ['name' => 'Azərbaycan', 'flag' => '🇦🇿', 'label' => 'AZ'],
          'en' => ['name' => 'English', 'flag' => '🇬🇧', 'label' => 'EN'],
          'ru' => ['name' => 'Русский', 'flag' => '🇷🇺', 'label' => 'RU'],
        ];
        $activeLang = $languages[$currentLocale] ?? $languages['az'];
      @endphp
      <div class="relative">
        <button id="navLangBtn" type="button"
                class="flex items-center gap-1.5 px-2.5 py-1.5 bg-gray-50 hover:bg-gray-100/80 border border-gray-200/90 rounded-xl text-xs font-bold text-gray-800 transition shadow-2xs cursor-pointer select-none">
          <span class="text-sm leading-none">{{ $activeLang['flag'] }}</span>
          <span>{{ $activeLang['label'] }}</span>
          <i class="bi bi-chevron-down text-[10px] text-gray-400 transition-transform duration-200" id="navLangChevron"></i>
        </button>

        <div id="navLangDropdown"
             class="hidden absolute right-0 mt-2 w-40 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden">
          @foreach($languages as $lKey => $lData)
            <a href="/lang/{{ $lKey }}"
               class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold {{ $currentLocale === $lKey ? 'text-[#f1913d] bg-orange-50/60 font-bold' : 'text-gray-700 hover:bg-gray-50' }} transition">
              <span class="flex items-center gap-2">
                <span class="text-base leading-none">{{ $lData['flag'] }}</span>
                <span>{{ $lData['name'] }}</span>
              </span>
              @if($currentLocale === $lKey)
                <i class="bi bi-check2 text-sm text-[#f1913d]"></i>
              @endif
            </a>
          @endforeach
        </div>
      </div>

      <!-- Post Request Button (Axtarıram) -->
      <a href="/axtariram/elan-ver" class="hidden lg:flex items-center px-3.5 py-2 border border-orange-500 text-orange-600 hover:bg-orange-50 rounded-xl font-semibold text-xs sm:text-sm transition shadow-2xs" title="{{ __('Tələb Elanı Yerləşdir') }}">
        <i class="fa-solid fa-bullhorn mr-1.5 text-xs text-orange-500"></i>
        <span>{{ __('Tələb yerləşdir') }}</span>
      </a>

      <!-- Add Property Button -->
      <a href="/add-property" class="hidden sm:flex items-center px-3.5 py-2 bg-[#f1913d] hover:bg-[#e07f2c] text-white rounded-xl font-semibold text-xs sm:text-sm transition shadow-sm">
        <i class="bi bi-plus-circle mr-1.5"></i>
        <span>{{ __('Elan yerləşdir') }}</span>
      </a>

      <!-- Auth User / Login -->
      <div class="relative">
        @auth
          <button id="navUserMenuBtn" type="button" class="flex items-center space-x-2 px-3 py-1.5 border border-gray-200 rounded-xl text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium cursor-pointer">
            <i class="fas fa-user text-gray-400"></i>
            <span class="max-w-[110px] truncate">{{ auth()->user()->name }}</span>
            <i class="bi bi-chevron-down text-xs text-gray-400 transition-transform duration-200" id="navUserChevron"></i>
          </button>

          <!-- User Dropdown Menu -->
          <div id="navUserDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
            <div class="px-4 py-2 border-b border-gray-100">
              <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
            </div>

            <a href="/dashboard" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#f1913d]">
              <i class="bi bi-grid mr-3 text-gray-400"></i> {{ __('Dashboard') }}
            </a>
            <a href="/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#f1913d]">
              <i class="bi bi-person mr-3 text-gray-400"></i> {{ __('My profile') }}
            </a>
            <a href="/my-properties" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#f1913d]">
              <i class="bi bi-folder-check mr-3 text-gray-400"></i> {{ __('My properties') }}
            </a>
            <a href="/favorites" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#f1913d]">
              <i class="bi bi-heart mr-3 text-gray-400"></i> {{ __('My favorites') }}
            </a>
            <a href="/mysavesearches" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-[#f1913d]">
              <i class="bi bi-bookmark mr-3 text-gray-400"></i> {{ __('My save searches') }}
            </a>

            @if(auth()->user()->is_admin ?? false)
              <div class="border-t border-gray-100 my-1"></div>
              <a href="/admin" class="flex items-center px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 font-medium">
                <i class="bi bi-shield-lock mr-3"></i> Admin Panel
              </a>
            @endif

            <div class="border-t border-gray-100 my-1"></div>

            <form method="POST" action="{{ route('logout') }}" class="m-0 js-logout">
              @csrf
              <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 text-left font-medium">
                <i class="bi bi-box-arrow-right mr-3"></i> {{ __('Logout') }}
              </button>
            </form>
          </div>
        @else
          <a href="/login" class="flex items-center px-3.5 py-2 border border-gray-200 rounded-xl text-gray-700 hover:border-[#f1913d] hover:text-[#f1913d] text-sm font-semibold transition bg-white shadow-2xs">
            <i class="bi bi-person mr-1.5 text-base"></i>
            <span>{{ __('Login / Register') }}</span>
          </a>
        @endauth
      </div>

      <!-- Mobile Hamburger Button -->
      <button type="button" id="mobileNavToggle" class="md:hidden p-2 text-gray-700 hover:text-[#f1913d] focus:outline-none cursor-pointer">
        <i class="bi bi-list text-2xl" id="mobileNavIcon"></i>
      </button>

    </div>
  </div>

  <!-- Mobile Dropdown Menu -->
  <div id="mobileNavMenu" class="hidden md:hidden border-t border-gray-100 bg-white px-4 py-4 space-y-4 shadow-lg">
    <div class="flex flex-col space-y-1 text-sm font-medium">
      <a href="/" class="px-3 py-2 rounded-xl {{ request()->is('/') ? 'text-[#f1913d] bg-orange-50 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Home') }}
      </a>
      <a href="/listing?deal_type=sale" class="px-3 py-2 rounded-xl {{ request()->is('listing*') && request('deal_type') === 'sale' ? 'text-[#f1913d] bg-orange-50 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Alqı-satqı') }}
      </a>
      <a href="/listing?deal_type=rent_monthly" class="px-3 py-2 rounded-xl {{ request()->is('listing*') && request('deal_type') === 'rent_monthly' ? 'text-[#f1913d] bg-orange-50 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Kirayə') }}
      </a>
      <a href="/listing?deal_type=rent_daily" class="px-3 py-2 rounded-xl {{ request()->is('listing*') && request('deal_type') === 'rent_daily' ? 'text-[#f1913d] bg-orange-50 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Günlük') }}
      </a>
      <a href="/axtariram" class="px-3 py-2 rounded-xl {{ request()->is('axtariram*') || request()->is('otaq-yoldasi*') ? 'text-[#f1913d] bg-orange-50 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Axtarıram') }}
      </a>
      <a href="/agencies" class="px-3 py-2 rounded-xl {{ request()->is('agencies*') || request()->is('agentlik*') ? 'text-[#f1913d] bg-orange-50 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Agencies') }}
      </a>
      <a href="/contact" class="px-3 py-2 rounded-xl {{ request()->is('contact*') ? 'text-[#f1913d] bg-orange-50 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Contact') }}
      </a>
    </div>

    <!-- Mobile Language Selector -->
    <div class="pt-2 border-t border-gray-100">
      <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">{{ __('Dil seçimi') }}</div>
      <div class="grid grid-cols-3 gap-2">
        @foreach($languages as $lKey => $lData)
          <a href="/lang/{{ $lKey }}"
             class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-xs font-bold border {{ $currentLocale === $lKey ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            <span>{{ $lData['flag'] }}</span>
            <span>{{ $lData['label'] }}</span>
          </a>
        @endforeach
      </div>
    </div>

    <!-- Mobile Currency Selector -->
    <div class="pt-2 border-t border-gray-100">
      <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">{{ __('Valyuta') }}</div>
      <div class="grid grid-cols-4 gap-1.5">
        @foreach($currencySymbols as $cCode => $cSym)
          <a href="/currency/{{ $cCode }}"
             class="flex items-center justify-center py-1.5 px-2 rounded-lg text-xs font-bold border {{ $currentCurrency === $cCode ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            <span>{{ $cCode }}</span>
          </a>
        @endforeach
      </div>
    </div>

    <div class="pt-3 border-t border-gray-100 flex flex-col space-y-2">
      <div class="grid grid-cols-2 gap-2">
        <a href="/axtariram/elan-ver" class="w-full flex items-center justify-center py-2.5 border border-orange-500 text-orange-600 rounded-xl font-semibold text-xs text-center">
          <i class="fa-solid fa-bullhorn mr-1.5 text-xs"></i> {{ __('Tələb yerləşdir') }}
        </a>
        <a href="/add-property" class="w-full flex items-center justify-center py-2.5 bg-[#f1913d] text-white rounded-xl font-semibold text-xs text-center">
          <i class="bi bi-plus-circle mr-1.5"></i> {{ __('Elan yerləşdir') }}
        </a>
      </div>

      <div class="flex items-center justify-between gap-2 pt-1">
        <a href="/favorites" class="flex-1 flex items-center justify-center gap-1.5 py-2 border border-gray-200 rounded-xl text-gray-700 text-xs font-medium hover:border-orange-500 hover:text-orange-500 transition">
          <i class="fa-regular fa-heart text-rose-500"></i>
          <span>{{ __('Favorites') }}</span>
        </a>
        <a href="/compares" class="flex-1 flex items-center justify-center gap-1.5 py-2 border border-gray-200 rounded-xl text-gray-700 text-xs font-medium hover:border-orange-500 hover:text-orange-500 transition">
          <i class="bi bi-arrow-left-right text-gray-700"></i>
          <span>{{ __('Compare') }}</span>
        </a>
      </div>
    </div>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Nav count badges
    function updateNavBadges() {
      const favBadge = document.getElementById('favorites-count');
      const compBadge = document.getElementById('compares-count');
      if (favBadge) {
        try {
          const favs = JSON.parse(localStorage.getItem('favorites')) || [];
          favBadge.textContent = favs.length;
        } catch(e) {}
      }
      if (compBadge) {
        try {
          const comps = JSON.parse(localStorage.getItem('compareList')) || [];
          compBadge.textContent = comps.length;
        } catch(e) {}
      }
    }
    updateNavBadges();
    window.addEventListener('storage', updateNavBadges);

    // Generic Dropdown Helper
    function setupDropdown(btnId, menuId, chevronId) {
      const btn = document.getElementById(btnId);
      const menu = document.getElementById(menuId);
      const chevron = chevronId ? document.getElementById(chevronId) : null;
      if (!btn || !menu) return;

      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        // Close other dropdowns
        ['navCurrencyDropdown', 'navLangDropdown', 'navUserDropdown'].forEach(id => {
          if (id !== menuId) {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
          }
        });
        ['navCurrencyChevron', 'navLangChevron', 'navUserChevron'].forEach(id => {
          if (id !== chevronId) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('rotate-180');
          }
        });

        const isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden', !isHidden);
        if (chevron) chevron.classList.toggle('rotate-180', isHidden);
      });
    }

    setupDropdown('navCurrencyBtn', 'navCurrencyDropdown', 'navCurrencyChevron');
    setupDropdown('navLangBtn', 'navLangDropdown', 'navLangChevron');
    setupDropdown('navUserMenuBtn', 'navUserDropdown', 'navUserChevron');

    document.addEventListener('click', function () {
      ['navCurrencyDropdown', 'navLangDropdown', 'navUserDropdown'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
      });
      ['navCurrencyChevron', 'navLangChevron', 'navUserChevron'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.remove('rotate-180');
      });
    });

    // Mobile navigation toggle
    const mobileBtn = document.getElementById('mobileNavToggle');
    const mobileMenu = document.getElementById('mobileNavMenu');
    const mobileIcon = document.getElementById('mobileNavIcon');

    if (mobileBtn && mobileMenu) {
      mobileBtn.addEventListener('click', function () {
        const isHidden = mobileMenu.classList.contains('hidden');
        mobileMenu.classList.toggle('hidden', !isHidden);
        if (mobileIcon) {
          mobileIcon.classList.toggle('bi-list', !isHidden);
          mobileIcon.classList.toggle('bi-x-lg', isHidden);
        }
      });
    }
  });
</script>
