
<div id="mobileMoreDrawer" class="hidden md:hidden fixed inset-0 z-50">
  <div class="w-full h-full flex flex-col justify-end bg-black/60 backdrop-blur-xs transition-opacity duration-300">
  <div class="flex-1" id="mobileMoreDrawerBackdrop"></div>

  <div class="bg-white rounded-t-3xl max-h-[95vh] overflow-y-auto shadow-2xl p-5 space-y-4 border-t border-gray-100 transform transition-transform duration-300">

    <!-- Drawer Header -->
    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
      <div class="flex items-center space-x-2">
        <img class="h-8 w-auto object-contain" src="{{ asset('images/kibriskarelogo1.png') }}" alt="KibrisKare.com" />
        <span class="font-bold text-base text-gray-800">KibrisKare.com</span>
      </div>
      <button type="button" id="closeMobileMoreDrawer" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
        <i class="bi bi-x-lg text-sm"></i>
      </button>
    </div>

    <!-- Quick Action Buttons -->
    <div class="grid grid-cols-2 gap-2.5">
      <a href="{{ route('add-property') }}" class="flex items-center justify-center gap-2 py-3 px-text-orange-500 hover:bg-orange-600 text-white rounded-2xl font-semibold text-xs shadow-sm">
        <i class="fa-solid fa-plus text-sm"></i>
        <span>{{ __('navbar.post_property') }}</span>
      </a>
      <a href="{{ route('requests.create') }}" class="flex items-center justify-center gap-2 py-3 px-4 bg-orange-500 text-white rounded-2xl font-semibold text-xs shadow-sm">
        <i class="fa-solid fa-bullhorn text-xs"></i>
        <span>{{ __('navbar.post_request') }}</span>
      </a>
    </div>

    <!-- Navigation Links List -->
    <div class="space-y-1 py-1">
      <a href="{{ route('home') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->is('/') ? 'text-orange-500 bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-house text-gray-400 w-5 text-center"></i> {{ __('navbar.home') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('listing.path1', ['first' => 'satilik']) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request('deal_type') === 'sale' || request()->is('*satilik*') ? 'text-orange-500 bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-key text-gray-400 w-5 text-center"></i> {{ __('navbar.sale') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('listing.path2', ['first' => 'kiralik', 'second' => 'aylik']) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ in_array(request('deal_type'), ['rent', 'rent_monthly', 'rent_daily']) || request()->is('*kiralik*') || request()->is('*kira*') ? 'text-orange-500 bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-calendar-days text-gray-400 w-5 text-center"></i> {{ __('navbar.rent') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('requests.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->is('*ariyorum*') || request()->is('*oda-arkadasi*') || request()->is('*axtariram*') || request()->is('*otaq-yoldasi*') ? 'text-orange-500 bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-magnifying-glass text-gray-400 w-5 text-center"></i> {{ __('navbar.requests') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('agencies.list') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->is('*emlak-ofis*') || request()->is('*agencies*') || request()->is('*agentlik*') ? 'text-orange-500 bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-building text-gray-400 w-5 text-center"></i> {{ __('navbar.agencies') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('contact') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->is('*iletisim*') || request()->is('*contact*') ? 'text-orange-500 bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-envelope text-gray-400 w-5 text-center"></i> {{ __('navbar.contact') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('compares') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->is('compares*') ? 'text-orange-500 bg-orange-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="bi bi-arrow-left-right text-gray-400 w-5 text-center"></i> {{ __('navbar.compare') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
    </div>

    <!-- Language Selector with Flags -->
    <div class="pt-3 border-t border-gray-100">
      <div class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-2">{{ __('navbar.select_language') }}</div>
      <div class="grid grid-cols-4 gap-1.5">
        @foreach($languages as $lKey => $lData)
          <a href="{{ route('lang.switch', $lKey) }}"
             class="flex items-center justify-center gap-1.5 py-2 px-2 rounded-2xl text-xs font-semibold border {{ $currentLocale === $lKey ? 'border-orange-500 bg-orange-50 text-orange-600 shadow-2xs' : 'border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            <span class="text-sm">{{ $lData['flag'] }}</span>
            <span>{{ $lData['label'] }}</span>
          </a>
        @endforeach
      </div>
    </div>

    <!-- Currency Selector -->
    <div class="pt-3 border-t border-gray-100">
      <div class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-2">{{ __('navbar.currency') }}</div>
      <div class="grid grid-cols-4 gap-1.5">
        @foreach($currencySymbols as $cCode => $cSym)
          <a href="{{ route('currency.switch', $cCode) }}"
             class="flex items-center justify-center py-2 px-2 rounded-xl text-xs font-semibold border {{ $currentCurrency === $cCode ? 'border-orange-500 bg-orange-50 text-orange-600 shadow-2xs' : 'border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            <span>{{ $cSym }} {{ $cCode }}</span>
          </a>
        @endforeach
      </div>
    </div>

    <!-- Auth / Account Actions -->
    <div class="pt-3 border-t border-gray-100">
      @auth
        <div class="space-y-1">
          <a href="{{ route('dashboard') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="bi bi-grid mr-3 text-gray-400"></i> {{ __('navbar.dashboard') }}
          </a>
          <a href="{{ route('profile') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="bi bi-person mr-3 text-gray-400"></i> {{ __('navbar.my_profile') }}
          </a>
          <a href="{{ route('my-properties') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="bi bi-folder-check mr-3 text-gray-400"></i> {{ __('navbar.my_properties') }}
          </a>
          @if(auth()->user()->is_admin ?? false)
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-semibold text-indigo-600 hover:bg-indigo-50">
              <i class="bi bi-shield-lock mr-3"></i> {{ __('navbar.admin_panel') }}
            </a>
          @endif
          <form method="POST" action="{{ route('logout') }}" class="m-0 js-logout pt-1">
            @csrf
            <button type="submit" class="w-full flex items-center px-3.5 py-2.5 text-sm text-red-600 hover:bg-red-50 text-left font-medium rounded-xl">
              <i class="bi bi-box-arrow-right mr-3"></i> {{ __('navbar.logout') }}
            </button>
          </form>
        </div>
      @else
        <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-gray-900 text-white rounded-2xl font-semibold text-xs shadow-sm">
          <i class="bi bi-person text-sm"></i>
          <span>{{ __('navbar.login_register') }}</span>
        </a>
      @endauth
    </div>

    </div>
  </div>
</div>
