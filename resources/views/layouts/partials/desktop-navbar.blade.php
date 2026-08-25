
<header class="hidden md:block bg-white border-b border-gray-200 sticky top-0 z-30">
  <div class="px-5 mx-auto h-17.5 flex items-center justify-between gap-4">

    <!-- Logo -->
    <a href="{{ route('home') }}" class="flex items-center space-x-2 shrink-0">
      <img class="h-9 w-auto object-contain" src="{{ asset('images/kibriskarelogo1.png') }}" alt="KibrisKare.com" />
      <div class="leading-tight">
        <div class="text-xl font-bold text-[#545454] tracking-tight">KibrisKare.com</div>
        <div class="text-[8px] text-gray-400 uppercase tracking-[0.18em]">{{ __('navbar.slogan') }}</div>
      </div>
    </a>

    <!-- Desktop Navigation Links (Always visible on md and up) -->
    <nav class="hidden md:flex items-center space-x-5 lg:space-x-7 text-[15px] font-medium">
      <a href="{{ route('listing.path1', ['first' => 'satilik']) }}" class="{{ request('deal_type') === 'sale' ? 'text-orange-500 font-semibold' : 'text-gray-700 hover:text-orange-500' }} transition">
        {{ __('navbar.sale') }}
      </a>
      <a href="{{ route('listing.path2', ['first' => 'kira', 'second' => 'ayliq']) }}" class="{{ request('deal_type') === 'rent_monthly' ? 'text-orange-500 font-semibold' : 'text-gray-700 hover:text-orange-500' }} transition">
        {{ __('navbar.rent') }}
      </a>
      <a href="{{ route('listing.path2', ['first' => 'kira', 'second' => 'gunluk']) }}" class="{{ request('deal_type') === 'rent_daily' ? 'text-orange-500 font-semibold' : 'text-gray-700 hover:text-orange-500' }} transition">
        {{ __('navbar.daily_rent') }}
      </a>
      <a href="{{ route('requests.index') }}" class="{{ request()->is('axtariram*') || request()->is('otaq-yoldasi*') ? 'text-orange-500 font-semibold' : 'text-gray-700 hover:text-orange-500' }} transition">
        {{ __('navbar.requests') }}
      </a>
      <a href="{{ route('agencies.list') }}" class="{{ request()->is('agencies*') || request()->is('agentlik*') ? 'text-orange-500 font-semibold' : 'text-gray-700 hover:text-orange-500' }} transition">
        {{ __('navbar.agencies') }}
      </a>
      <a href="{{ route('contact') }}" class="{{ request()->is('contact*') ? 'text-orange-500 font-semibold' : 'text-gray-700 hover:text-orange-500' }} transition">
        {{ __('navbar.contact') }}
      </a>
    </nav>

    <!-- Right Actions -->
    <div class="flex items-center space-x-2.5 sm:space-x-3.5">

      <!-- Favorites -->
      <a href="{{ route('favorites') }}" class="relative text-gray-700 hover:text-orange-500 p-2 rounded-xl transition inline-flex items-center justify-center hover:bg-gray-50" title="{{ __('navbar.favorites') }}">
        <i class="fa-regular fa-heart text-xl text-rose-500"></i>
        <span id="favorites-count" class="absolute -top-1 -right-1 bg-orange-500 text-white text-[10px] min-w-[16px] h-4 px-1 flex items-center justify-center rounded-full font-semibold shadow-sm">0</span>
      </a>

      <!-- Compares -->
      <a href="{{ route('compares') }}" class="relative text-gray-700 hover:text-orange-500 p-2 rounded-xl transition inline-flex items-center justify-center hover:bg-gray-50" title="{{ __('navbar.compare') }}">
        <i class="bi bi-arrow-left-right text-xl text-gray-700"></i>
        <span id="compares-count" class="absolute top-0 -right-1 bg-orange-500 text-white text-[10px] min-w-[16px] h-4 px-1 flex items-center justify-center rounded-full font-semibold shadow-sm">0</span>
      </a>

      <!-- Currency Custom Dropdown -->
      <div class="relative">
        <button id="navCurrencyBtn" type="button"
                class="flex items-center gap-1.5 px-2.5 py-1.5 bg-gray-50 hover:bg-gray-100/80 border border-gray-200/90 rounded-xl text-xs font-semibold text-gray-800 transition shadow-2xs cursor-pointer select-none">
          <span class="text-gray-500 font-bold">{{ $currencySymbols[$currentCurrency] ?? '₼' }}</span>
          <span>{{ $currentCurrency }}</span>
          <i class="bi bi-chevron-down text-[10px] text-gray-400 transition-transform duration-200" id="navCurrencyChevron"></i>
        </button>

        <div id="navCurrencyDropdown"
             class="hidden absolute right-0 mt-2 w-36 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden">
          @foreach($currencySymbols as $cCode => $cSym)
            <a href="{{ route('currency.switch', $cCode) }}"
               class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold {{ $currentCurrency === $cCode ? 'text-orange-500 bg-orange-50/60 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
              <span class="flex items-center gap-2">
                <span class="w-4 text-center font-semibold text-gray-400">{{ $cSym }}</span>
                <span>{{ $cCode }}</span>
              </span>
              @if($currentCurrency === $cCode)
                <i class="bi bi-check2 text-sm text-orange-500"></i>
              @endif
            </a>
          @endforeach
        </div>
      </div>

      <!-- Language Custom Dropdown with Flags -->
      <div class="relative">
        <button id="navLangBtn" type="button"
                class="flex items-center gap-1.5 px-2.5 py-1.5 bg-gray-50 hover:bg-gray-100/80 border border-gray-200/90 rounded-xl text-xs font-semibold text-gray-800 transition shadow-2xs cursor-pointer select-none">
          <span class="text-sm leading-none">{{ $activeLang['flag'] }}</span>
          <span>{{ $activeLang['label'] }}</span>
          <i class="bi bi-chevron-down text-[10px] text-gray-400 transition-transform duration-200" id="navLangChevron"></i>
        </button>

        <div id="navLangDropdown"
             class="hidden absolute right-0 mt-2 w-40 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden">
          @foreach($languages as $lKey => $lData)
            <a href="{{ route('lang.switch', $lKey) }}"
               class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold {{ $currentLocale === $lKey ? 'text-orange-500 bg-orange-50/60 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
              <span class="flex items-center gap-2">
                <span class="text-base leading-none">{{ $lData['flag'] }}</span>
                <span>{{ $lData['name'] }}</span>
              </span>
              @if($currentLocale === $lKey)
                <i class="bi bi-check2 text-sm text-orange-500"></i>
              @endif
            </a>
          @endforeach
        </div>
      </div>

      <!-- Post Request Button (Axtarıram) -->
      <a href="{{ route('requests.create') }}" class="hidden lg:flex items-center px-3.5 py-2 border border-orange-500 text-orange-600 hover:bg-orange-50 rounded-xl font-semibold text-xs sm:text-sm transition shadow-2xs" title="{{ __('navbar.post_request') }}">
        <i class="fa-solid fa-bullhorn mr-1.5 text-xs text-orange-500"></i>
        <span>{{ __('navbar.post_request') }}</span>
      </a>

      <!-- Add Property Button -->
      <a href="{{ route('add-property') }}" class="hidden sm:flex items-center px-3.5 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-semibold text-xs sm:text-sm transition shadow-sm">
        <i class="bi bi-plus-circle mr-1.5"></i>
        <span>{{ __('navbar.post_property') }}</span>
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

            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-500">
              <i class="bi bi-grid mr-3 text-gray-400"></i> {{ __('navbar.dashboard') }}
            </a>
            <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-500">
              <i class="bi bi-person mr-3 text-gray-400"></i> {{ __('navbar.my_profile') }}
            </a>
            <a href="{{ route('my-properties') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-500">
              <i class="bi bi-folder-check mr-3 text-gray-400"></i> {{ __('navbar.my_properties') }}
            </a>
            <a href="{{ route('favorites') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-500">
              <i class="bi bi-heart mr-3 text-gray-400"></i> {{ __('navbar.my_favorites') }}
            </a>
            <a href="/mysavesearches" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-500">
              <i class="bi bi-bookmark mr-3 text-gray-400"></i> {{ __('navbar.my_saved_searches') }}
            </a>

            @if(auth()->user()->is_admin ?? false)
              <div class="border-t border-gray-100 my-1"></div>
              <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 font-medium">
                <i class="bi bi-shield-lock mr-3"></i> {{ __('navbar.admin_panel') }}
              </a>
            @endif

            <div class="border-t border-gray-100 my-1"></div>

            <form method="POST" action="{{ route('logout') }}" class="m-0 js-logout">
              @csrf
              <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 text-left font-medium">
                <i class="bi bi-box-arrow-right mr-3"></i> {{ __('navbar.logout') }}
              </button>
            </form>
          </div>
        @else
          <a href="{{ route('login') }}" class="flex items-center px-3.5 py-2 border border-gray-200 rounded-xl text-gray-700 hover:border-orange-500 hover:text-orange-500 text-sm font-semibold transition bg-white shadow-2xs">
            <i class="bi bi-person mr-1.5 text-base"></i>
            <span>{{ __('navbar.login_register') }}</span>
          </a>
        @endauth
      </div>

    </div>
  </div>
</header>
