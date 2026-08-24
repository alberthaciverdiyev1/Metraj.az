<header class="hidden md:block bg-white border-b border-gray-200 sticky top-0 z-30">
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

    </div>
  </div>
</header>

<!-- Mobile Instagram-style Bottom Navigation Bar -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200/90 shadow-[0_-4px_25px_rgba(0,0,0,0.08)] px-2 py-2 flex items-center justify-around select-none">
  
  <!-- 1. ƏMLAK -->
  <a href="/listing" class="flex flex-col items-center justify-center flex-1 py-1 text-center transition {{ request()->is('listing*') || request()->is('/') ? 'text-gray-900 font-extrabold' : 'text-gray-400 hover:text-gray-700' }}">
    <i class="fa-solid fa-house text-lg mb-0.5"></i>
    <span class="text-[10px] font-bold uppercase tracking-tight">{{ __('ƏMLAK') }}</span>
  </a>

  <!-- 2. SEÇİLMİŞLƏR -->
  <a href="/favorites" class="flex flex-col items-center justify-center flex-1 py-1 text-center relative transition {{ request()->is('favorites*') ? 'text-gray-900 font-extrabold' : 'text-gray-400 hover:text-gray-700' }}">
    <div class="relative">
      <i class="fa-solid fa-heart text-lg mb-0.5"></i>
      <span id="mobile-fav-count" class="hidden absolute -top-1 -right-2 bg-orange-500 text-white text-[8px] min-w-[14px] h-3.5 px-0.5 flex items-center justify-center rounded-full font-bold shadow-xs">0</span>
    </div>
    <span class="text-[10px] font-bold uppercase tracking-tight">{{ __('SEÇİLMİŞLƏR') }}</span>
  </a>

  <!-- 3. YENİ ELAN (Center Elevated Green Circular Button) -->
  <div class="flex flex-col items-center justify-center flex-1 relative -top-3.5">
    <a href="/add-property" class="w-13 h-13 bg-[#22c55e] hover:bg-[#16a34a] text-white rounded-full shadow-lg flex items-center justify-center border-4 border-white transition-all transform hover:scale-105 active:scale-95" title="{{ __('Yeni Elan') }}">
      <i class="fa-solid fa-plus text-2xl font-black"></i>
    </a>
    <span class="text-[10px] font-bold uppercase tracking-tight text-gray-500 mt-0.5">{{ __('YENİ ELAN') }}</span>
  </div>

  <!-- 4. KABİNET -->
  @auth
    <a href="/profile" class="flex flex-col items-center justify-center flex-1 py-1 text-center transition {{ request()->is('profile*') || request()->is('dashboard*') || request()->is('my-*') ? 'text-gray-900 font-extrabold' : 'text-gray-400 hover:text-gray-700' }}">
      <i class="fa-solid fa-circle-user text-lg mb-0.5"></i>
      <span class="text-[10px] font-bold uppercase tracking-tight truncate max-w-[65px]">{{ __('KABİNET') }}</span>
    </a>
  @else
    <a href="/login" class="flex flex-col items-center justify-center flex-1 py-1 text-center transition {{ request()->is('login*') || request()->is('register*') ? 'text-gray-900 font-extrabold' : 'text-gray-400 hover:text-gray-700' }}">
      <i class="fa-solid fa-circle-user text-lg mb-0.5"></i>
      <span class="text-[10px] font-bold uppercase tracking-tight">{{ __('KABİNET') }}</span>
    </a>
  @endauth

  <!-- 5. DAHA ÇOX -->
  <button type="button" id="mobileMoreDrawerBtn" class="flex flex-col items-center justify-center flex-1 py-1 text-center text-gray-400 hover:text-gray-700 transition cursor-pointer">
    <i class="fa-solid fa-bars text-lg mb-0.5"></i>
    <span class="text-[10px] font-bold uppercase tracking-tight">{{ __('DAHA ÇOX') }}</span>
  </button>

</nav>

<!-- Mobile "Daha Çox" Fullscreen Screen -->
<div id="mobileMoreDrawer" class="hidden md:hidden fixed inset-0 z-50 bg-white flex flex-col overflow-y-auto select-none">
  
  <!-- Header with Back Arrow and Title -->
  <div class="h-14 border-b border-gray-100 flex items-center justify-between px-4 sticky top-0 bg-white z-10 shrink-0">
    <button type="button" id="closeMobileMoreDrawer" class="w-10 h-10 flex items-center justify-start text-gray-500 hover:text-gray-900 text-xl cursor-pointer">
      <i class="bi bi-chevron-left text-lg"></i>
    </button>
    <h1 class="text-base font-medium text-gray-900">{{ __('Daha çox') }}</h1>
    <div class="w-10"></div>
  </div>

  <!-- Content Sections -->
  <div class="flex-1 pb-24 divide-y divide-gray-100">
    
    <!-- Language Row -->
    <div class="px-5 py-4 flex items-center justify-between bg-white cursor-pointer hover:bg-gray-50 transition" id="mobileLangDrawerBtn">
      <div class="flex items-center gap-3.5">
        <i class="bi bi-globe text-xl text-gray-500"></i>
        <span class="text-[15px] font-normal text-gray-900">
          @if($currentLocale === 'az')
            Русский язык
          @elseif($currentLocale === 'ru')
            Azərbaycan dili
          @else
            English
          @endif
        </span>
      </div>
      <div class="flex items-center gap-1.5 text-xs text-gray-400 font-bold uppercase">
        <span>{{ $currentLocale }}</span>
        <i class="bi bi-chevron-down text-xs text-gray-400 transition-transform duration-200" id="mobileLangChevron"></i>
      </div>
    </div>

    <!-- Hidden / Expandable Language Selection Panel -->
    <div id="mobileLangDrawerList" class="hidden bg-gray-50/80 px-5 py-3 space-y-2 border-t border-b border-gray-100">
      @foreach($languages as $lKey => $lData)
        <a href="/lang/{{ $lKey }}" class="flex items-center justify-between py-2 px-3 rounded-xl {{ $currentLocale === $lKey ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-700 bg-white hover:bg-gray-100' }} text-sm transition">
          <span class="flex items-center gap-2.5">
            <span class="text-base">{{ $lData['flag'] }}</span>
            <span>{{ $lData['name'] }}</span>
          </span>
          @if($currentLocale === $lKey)
            <i class="bi bi-check2 text-orange-600 font-bold"></i>
          @endif
        </a>
      @endforeach
    </div>

    <!-- Section 1: Complex, Agency, Partner -->
    <div class="py-1">
      <a href="/listing?building_type=complex" class="block px-5 py-3.5 text-[15px] text-gray-900 hover:bg-gray-50 transition">
        {{ __('Yaşayış kompleksləri') }}
      </a>
      <a href="/agencies" class="block px-5 py-3.5 text-[15px] text-gray-900 hover:bg-gray-50 transition">
        {{ __('Agentliklər') }}
      </a>
      <a href="/agencies" class="flex items-center gap-2.5 px-5 py-3.5 text-[15px] font-bold text-gray-900 hover:bg-gray-50 transition">
        <i class="fa-solid fa-certificate text-neutral-800 text-base"></i>
        <span>PASHA Real Estate</span>
      </a>
    </div>

    <!-- Section 2: Info & Legal Pages -->
    <div class="py-1">
      <a href="/about-us" class="block px-5 py-3.5 text-[15px] text-gray-900 hover:bg-gray-50 transition">
        {{ __('Layihə haqda') }}
      </a>
      <a href="/user-agreement" class="block px-5 py-3.5 text-[15px] text-gray-900 hover:bg-gray-50 transition">
        {{ __('İstifadəçi razılaşması') }}
      </a>
      <a href="/faq" class="block px-5 py-3.5 text-[15px] text-gray-900 hover:bg-gray-50 transition">
        {{ __('Qaydalar') }}
      </a>
      <a href="/privacy-policy" class="block px-5 py-3.5 text-[15px] text-gray-900 hover:bg-gray-50 transition">
        {{ __('Məxfilik siyasəti') }}
      </a>
      <a href="/contact" class="flex items-center justify-between px-5 py-3.5 text-[15px] text-gray-900 hover:bg-gray-50 transition">
        <span>{{ __('Reklam yerləşdirin') }}</span>
        <span class="bg-[#70B345] text-white text-[11px] font-semibold px-2 py-0.5 rounded-full lowercase tracking-tight leading-none">{{ __('yeni') }}</span>
      </a>
      <a href="/contact" class="block px-5 py-3.5 text-[15px] text-gray-900 hover:bg-gray-50 transition">
        {{ __('Bizimlə əlaqə') }}
      </a>
    </div>

    <!-- Section 3: Full Desktop Version & Account -->
    <div class="py-1">
      <a href="/?desktop=1" class="block px-5 py-3.5 text-[15px] text-gray-900 hover:bg-gray-50 transition">
        {{ __('Tam versiya') }}
      </a>
      @auth
        <form method="POST" action="{{ route('logout') }}" class="m-0 js-logout">
          @csrf
          <button type="submit" class="w-full text-left px-5 py-3.5 text-[15px] text-red-600 hover:bg-red-50 transition font-medium">
            <i class="bi bi-box-arrow-right mr-2"></i> {{ __('Çıxış') }}
          </button>
        </form>
      @endauth
    </div>

  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Nav count badges
    function updateNavBadges() {
      const favBadge = document.getElementById('favorites-count');
      const mobileFavBadge = document.getElementById('mobile-bottom-fav-count');
      const compBadge = document.getElementById('compares-count');

      try {
        const favs = JSON.parse(localStorage.getItem('favorites')) || [];
        if (favBadge) favBadge.textContent = favs.length;
        if (mobileFavBadge) {
          mobileFavBadge.textContent = favs.length;
          mobileFavBadge.classList.toggle('hidden', favs.length === 0);
        }
      } catch(e) {}

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

    // Mobile "Daha Çox" Fullscreen toggle
    const mobileMoreBtn = document.getElementById('mobileMoreDrawerBtn');
    const mobileDrawer = document.getElementById('mobileMoreDrawer');
    const closeDrawerBtn = document.getElementById('closeMobileMoreDrawer');
    const langDrawerBtn = document.getElementById('mobileLangDrawerBtn');
    const langDrawerList = document.getElementById('mobileLangDrawerList');
    const langChevron = document.getElementById('mobileLangChevron');

    function openDrawer() {
      if (mobileDrawer) mobileDrawer.classList.remove('hidden');
    }
    function closeDrawer() {
      if (mobileDrawer) mobileDrawer.classList.add('hidden');
    }

    if (mobileMoreBtn) mobileMoreBtn.addEventListener('click', openDrawer);
    if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', closeDrawer);

    if (langDrawerBtn && langDrawerList) {
      langDrawerBtn.addEventListener('click', function () {
        const isHidden = langDrawerList.classList.contains('hidden');
        langDrawerList.classList.toggle('hidden', !isHidden);
        if (langChevron) langChevron.classList.toggle('rotate-180', isHidden);
      });
    }
  });
</script>
