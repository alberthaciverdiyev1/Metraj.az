<nav id="mobileBottomNav" class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200/90 px-2 flex items-center justify-around select-none">

  <!-- 1. ƏMLAK -->
  <a href="{{ route('listing') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center transition {{ request()->routeIs('listing*') || request()->routeIs('home') ? 'text-orange-500 font-semibold' : 'text-gray-400 hover:text-gray-700' }}">
    <i class="fa-solid fa-house text-lg mb-0.5"></i>
    <span class="text-[8px] font-semibold uppercase tracking-tight">{{ __('navbar.mobile_properties') }}</span>
  </a>

  <!-- 2. SEÇİLMİŞLƏR -->
  <a href="{{ route('favorites') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center relative transition {{ request()->is('favorites*') ? 'text-orange-500 font-semibold' : 'text-gray-400 hover:text-gray-700' }}">
    <div class="relative">
      <i class="fa-solid fa-heart text-lg mb-0.5"></i>
      <span id="mobile-fav-count" class="hidden absolute -top-1 -right-2 bg-orange-500 text-white text-[8px] min-w-[14px] h-3.5 px-0.5 flex items-center justify-center rounded-full font-semibold shadow-xs">0</span>
    </div>
    <span class="text-[8px] font-semibold uppercase tracking-tight">{{ __('navbar.mobile_favorites') }}</span>
  </a>

  <!-- 3. YENİ ELAN (Center Elevated Orange Circular Button) -->
  <div class="flex flex-col items-center justify-center flex-1 relative -top-3.5">
    <a href="{{ route('add-property') }}" class="w-13 h-13 bg-orange-500 hover:bg-orange-600 text-white rounded-full shadow-lg flex items-center justify-center border-4 border-white transition-all transform hover:scale-105 active:scale-95" title="{{ __('navbar.post_property') }}">
      <i class="fa-solid fa-plus text-2xl font-black"></i>
    </a>
    <span class="text-[8px] font-semibold uppercase tracking-tight text-gray-500 mt-0.5">{{ __('navbar.mobile_new_ad') }}</span>
  </div>

  <!-- 4. KABİNET -->
  @auth
    <a href="{{ route('profile') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center transition {{ request()->is('profile*') || request()->is('dashboard*') || request()->is('my-*') ? 'text-orange-500 font-semibold' : 'text-gray-400 hover:text-gray-700' }}">
      <i class="fa-solid fa-circle-user text-lg mb-0.5"></i>
      <span class="text-[8px] font-semibold uppercase tracking-tight truncate max-w-[65px]">{{ __('navbar.mobile_cabinet') }}</span>
    </a>
  @else
    <a href="{{ route('login') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-center transition {{ request()->is('login*') || request()->is('register*') ? 'text-orange-500 font-semibold' : 'text-gray-400 hover:text-gray-700' }}">
      <i class="fa-solid fa-circle-user text-lg mb-0.5"></i>
      <span class="text-[8px] font-semibold uppercase tracking-tight">{{ __('navbar.mobile_cabinet') }}</span>
    </a>
  @endauth

  <!-- 5. DAHA ÇOX -->
  <button type="button" id="mobileMoreDrawerBtn" class="flex flex-col items-center justify-center flex-1 py-1 text-center text-gray-400 hover:text-gray-700 transition cursor-pointer">
    <i class="fa-solid fa-bars text-lg mb-0.5"></i>
    <span class="text-[8px] font-semibold uppercase tracking-tight">{{ __('navbar.mobile_more') }}</span>
  </button>

</nav>
