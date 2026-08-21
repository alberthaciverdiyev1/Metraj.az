<header class="bg-white border-b border-gray-200 sticky top-0 z-[100]">
  <div class="container mx-auto px-4 h-[70px] flex items-center justify-between gap-4">

    <!-- Logo -->
    <a href="/" class="flex items-center space-x-2 shrink-0">
      <img class="h-9 w-auto object-contain" src="/images/metrajlogo1.png" alt="Metraj.az" />
      <div class="leading-tight">
        <div class="text-xl font-extrabold text-[#545454] tracking-tight">Metraj.az</div>
        <div class="text-[8px] text-gray-400 uppercase tracking-[0.18em]">sənin əmlakın</div>
      </div>
    </a>

    <!-- Desktop Navigation Links (Always visible on md and up) -->
    <nav class="hidden md:flex items-center space-x-6 lg:space-x-8 text-[15px] font-medium">
      <a href="/listing" class="{{ request()->is('listing*') || request()->is('properties*') ? 'text-[#f1913d] font-semibold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('Elanlar') }}
      </a>
      <a href="/agencies" class="{{ request()->is('agencies*') || request()->is('agentlik*') ? 'text-[#f1913d] font-semibold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('Agencies') }}
      </a>
      <a href="/blog" class="{{ request()->is('blog*') ? 'text-[#f1913d] font-semibold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('Blog') }}
      </a>
      <a href="/about-us" class="{{ request()->is('about-us*') ? 'text-[#f1913d] font-semibold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('About Us') }}
      </a>
      <a href="/contact" class="{{ request()->is('contact*') ? 'text-[#f1913d] font-semibold' : 'text-gray-700 hover:text-[#f1913d]' }} transition">
        {{ __('Contact') }}
      </a>
    </nav>

    <!-- Right Actions -->
    <div class="flex items-center space-x-3 sm:space-x-4">

      <!-- Favorites -->
      <a href="/favorites" class="relative text-gray-600 hover:text-[#f1913d] p-2 transition hidden sm:inline-flex" title="{{ __('Favorites') }}">
        <i class="fa-regular fa-heart text-xl"></i>
        <span id="favorites-count" class="absolute top-0 right-0 bg-[#f1913d] text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full font-bold">0</span>
      </a>

      <!-- Compares -->
      <a href="/compares" class="relative text-gray-600 hover:text-[#f1913d] p-2 transition hidden sm:inline-flex" title="{{ __('Compare') }}">
        <i class="bi bi-arrow-left-right text-xl"></i>
        <span id="compares-count" class="absolute top-0 right-0 bg-[#f1913d] text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full font-bold">0</span>
      </a>

      <!-- Currency Selector -->
      @php
        $currentCurrency = session('currency', 'AZN');
      @endphp
      <div class="relative">
        <select onchange="window.location.href='/currency/'+this.value"
                class="bg-gray-50 border border-gray-200 text-gray-800 text-xs font-bold rounded-lg px-2 py-2 focus:outline-none focus:border-[#f1913d] cursor-pointer shadow-sm"
                title="Valyuta seçimi">
          <option value="AZN" {{ $currentCurrency == 'AZN' ? 'selected' : '' }}>₼ AZN</option>
          <option value="USD" {{ $currentCurrency == 'USD' ? 'selected' : '' }}>$ USD</option>
          <option value="EUR" {{ $currentCurrency == 'EUR' ? 'selected' : '' }}>€ EUR</option>
          <option value="GBP" {{ $currentCurrency == 'GBP' ? 'selected' : '' }}>£ GBP</option>
          <option value="TRY" {{ $currentCurrency == 'TRY' ? 'selected' : '' }}>₺ TRY</option>
          <option value="RUB" {{ $currentCurrency == 'RUB' ? 'selected' : '' }}>₽ RUB</option>
          <option value="AED" {{ $currentCurrency == 'AED' ? 'selected' : '' }}>AED</option>
        </select>
      </div>

      <!-- Language Selector -->
      @php
        $currentLocale = session('lang', app()->getLocale() ?? 'az');
      @endphp
      <div class="relative">
        <select onchange="window.location.href='/lang/'+this.value"
                class="bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold rounded-lg px-2.5 py-2 focus:outline-none focus:border-[#f1913d] cursor-pointer"
                title="Dil seçimi">
          <option value="az" {{ $currentLocale == 'az' ? 'selected' : '' }}>AZ</option>
          <option value="en" {{ $currentLocale == 'en' ? 'selected' : '' }}>EN</option>
          <option value="ru" {{ $currentLocale == 'ru' ? 'selected' : '' }}>RU</option>
        </select>
      </div>

      <!-- Add Property Button -->
      <a href="/add-property" class="hidden sm:flex items-center px-4 py-2 bg-[#f1913d] hover:bg-[#e07f2c] text-white rounded-lg font-medium text-sm transition shadow-sm">
        <i class="bi bi-plus-circle mr-2"></i>
        <span>{{ __('Add property') }}</span>
      </a>

      <!-- Auth User / Login -->
      <div class="relative">
        @auth
          <button id="navUserMenuBtn" type="button" class="flex items-center space-x-2 px-3 py-1.5 border border-gray-200 rounded-lg text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium">
            <i class="fas fa-user text-gray-400"></i>
            <span class="max-w-[110px] truncate">{{ auth()->user()->name }}</span>
            <i class="bi bi-chevron-down text-xs text-gray-400" id="navUserChevron"></i>
          </button>

          <!-- User Dropdown Menu -->
          <div id="navUserDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
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
          <a href="/login" class="flex items-center px-3.5 py-2 border border-gray-200 rounded-lg text-gray-700 hover:border-[#f1913d] hover:text-[#f1913d] text-sm font-medium transition bg-white">
            <i class="bi bi-person mr-1.5 text-base"></i>
            <span>{{ __('Login / Register') }}</span>
          </a>
        @endauth
      </div>

      <!-- Mobile Hamburger Button -->
      <button type="button" id="mobileNavToggle" class="md:hidden p-2 text-gray-700 hover:text-[#f1913d] focus:outline-none">
        <i class="bi bi-list text-2xl" id="mobileNavIcon"></i>
      </button>

    </div>
  </div>

  <!-- Mobile Dropdown Menu -->
  <div id="mobileNavMenu" class="hidden md:hidden border-t border-gray-100 bg-white px-4 py-4 space-y-3 shadow-md">
    <div class="flex flex-col space-y-2 text-sm font-medium">
      <a href="/" class="px-3 py-2 rounded-md {{ request()->is('/') ? 'text-[#f1913d] bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Home') }}
      </a>
      <a href="/listing" class="px-3 py-2 rounded-md {{ request()->is('listing*') || request()->is('properties*') ? 'text-[#f1913d] bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Elanlar') }}
      </a>
      <a href="/agencies" class="px-3 py-2 rounded-md {{ request()->is('agencies*') || request()->is('agentlik*') ? 'text-[#f1913d] bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Agencies') }}
      </a>
      <a href="/blog" class="px-3 py-2 rounded-md {{ request()->is('blog*') ? 'text-[#f1913d] bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Blog') }}
      </a>
      <a href="/about-us" class="px-3 py-2 rounded-md {{ request()->is('about-us*') ? 'text-[#f1913d] bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('About Us') }}
      </a>
      <a href="/contact" class="px-3 py-2 rounded-md {{ request()->is('contact*') ? 'text-[#f1913d] bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
        {{ __('Contact') }}
      </a>
    </div>

    <div class="pt-3 border-t border-gray-100 flex flex-col space-y-2">
      <a href="/add-property" class="w-full flex items-center justify-center py-2.5 bg-[#f1913d] text-white rounded-lg font-medium text-sm">
        <i class="bi bi-plus-circle mr-2"></i> {{ __('Add property') }}
      </a>

      <div class="flex items-center justify-between gap-2 pt-1">
        <a href="/favorites" class="flex-1 flex items-center justify-center gap-1.5 py-2 border border-gray-200 rounded-lg text-gray-700 text-xs font-medium">
          <i class="fa-regular fa-heart text-[#f1913d]"></i>
          <span>{{ __('Favorites') }}</span>
        </a>
        <a href="/compares" class="flex-1 flex items-center justify-center gap-1.5 py-2 border border-gray-200 rounded-lg text-gray-700 text-xs font-medium">
          <i class="bi bi-arrow-left-right text-[#f1913d]"></i>
          <span>{{ __('Compare') }}</span>
        </a>
      </div>
    </div>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // User dropdown
    const userBtn = document.getElementById('navUserMenuBtn');
    const userDropdown = document.getElementById('navUserDropdown');
    const userChevron = document.getElementById('navUserChevron');

    if (userBtn && userDropdown) {
      userBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        const isHidden = userDropdown.classList.contains('hidden');
        userDropdown.classList.toggle('hidden', !isHidden);
        if (userChevron) userChevron.classList.toggle('rotate-180', isHidden);
      });

      document.addEventListener('click', function (e) {
        if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
          userDropdown.classList.add('hidden');
          if (userChevron) userChevron.classList.remove('rotate-180');
        }
      });
    }

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
