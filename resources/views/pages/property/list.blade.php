@extends('layouts.app')

@if(isset($pageTitle) && $pageTitle)
    @section('title', $pageTitle . ' - KibrisKare.com')
@endif

@if(isset($metaDescription) && $metaDescription)
    @section('meta_description', $metaDescription)
@endif

@section('content')
    <div class="w-full pt-4">
        @include('components.scroll-top')

        <section class="property-listing py-2">
            <div class="container mx-auto px-4 text-sm">
                @if(isset($pageTitle) && $pageTitle)
                    <div class="mb-4">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 tracking-tight">{{ $pageTitle }}</h1>
                    </div>
                @endif

                <form method="GET" action="{{ route('listing') }}" id="filterForm" class="space-y-4 mt-2">
                    <section class="pt-2 max-w-full mx-auto">
                        <div class="flex justify-between items-center mb-3">
                            @php
                                $selectedAdType = request('adType', 'all');
                                if (request('deal_type') === 'sale') {
                                    $selectedAdType = 'sale';
                                } elseif (request('deal_type') === 'rent_daily' || $selectedAdType === 'rent_daily') {
                                    $selectedAdType = 'rent_daily';
                                } elseif (in_array(request('deal_type'), ['rent', 'rent_monthly']) || $selectedAdType === 'rent_monthly' || $selectedAdType === 'rent') {
                                    $selectedAdType = 'rent';
                                }
                            @endphp
                            <div
                                class="flex gap-1 bg-gray-100 p-1 rounded-2xl border border-gray-200/50 max-w-max shadow-sm"
                                data-role="add-type-toggle">
                                <button type="button" data-value="all"
                                        class="px-5 py-2.5 rounded-xl font-semibold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === 'all' || !$selectedAdType ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}"
                                        data-add-type="all">
                                    {{ __("listing.all") }}
                                </button>
                                <button type="button" data-value="sale"
                                        class="px-5 py-2.5 rounded-xl font-semibold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === 'sale' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}"
                                        data-add-type="sale">
                                    {{ __("navbar.sale") }}
                                </button>
                                <button type="button" data-value="rent"
                                        class="px-5 py-2.5 rounded-xl font-semibold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === 'rent' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}"
                                        data-add-type="rent">
                                    {{ __("navbar.rent") }}
                                </button>
                                <button type="button" data-value="rent_daily"
                                        class="desktop-only-tab px-5 py-2.5 rounded-xl font-semibold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === 'rent_daily' ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}"
                                        data-add-type="rent_daily">
                                    {{ __("navbar.daily_rent") }}
                                </button>
                                <input type="hidden" name="adType" id="adTypeInput"
                                       value="{{ $selectedAdType }}">
                            </div>

                                    <div class="flex gap-2">
                                        <button type="button" id="resetFiltersBtn"
                                                class="px-4 py-1 border border-gray-300 rounded-lg hover:bg-gray-100 flex items-center justify-center">
                                            <i class="bi bi-arrow-clockwise text-lg text-gray-600"></i>
                                        </button>

                                        <button type="button" id="gridViewBtn"
                                                class="hidden md:flex flex-1 px-4 py-1 rounded-md bg-[var(--primary)] text-white items-center justify-center"
                                                data-view="grid">
                                            <i class="bi bi-grid-3x3-gap"></i>
                                        </button>

                                        <button type="button" id="listViewBtn"
                                                class="hidden md:flex flex-1 px-4 py-1 border border-gray-300 rounded-md text-gray-500 items-center justify-center"
                                                data-view="list">
                                            <i class="fas fa-list"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative z-20">
                                    <!-- Room Count Custom Dropdown (Navbar Style) -->
                                    @php
                                        $currentRooms = request('roomCount', '');
                                        $roomOptions = [
                                            '' => __('listing.room_count_all'),
                                            '1' => '1 ' . __('listing.rooms_suffix'),
                                            '2' => '2 ' . __('listing.rooms_suffix'),
                                            '3' => '3 ' . __('listing.rooms_suffix'),
                                            '4' => '4 ' . __('listing.rooms_suffix'),
                                            '5' => '5 ' . __('listing.rooms_suffix'),
                                            '6' => '6+ ' . __('listing.rooms_suffix'),
                                        ];
                                        $currentRoomsLabel = $roomOptions[$currentRooms] ?? __('listing.room_count_all');
                                    @endphp
                                    <div class="relative">
                                        <button id="filterRoomBtn" type="button"
                                                class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 hover:bg-gray-100/80 border border-gray-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-gray-800 transition shadow-2xs cursor-pointer select-none">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <img src="{{ asset('images/door.svg') }}" alt="door" class="w-4 h-4 shrink-0">
                                                <span class="btn-display-text truncate font-semibold text-gray-800">{{ $currentRoomsLabel }}</span>
                                            </div>
                                            <i class="bi bi-chevron-down text-xs text-gray-400 transition-transform duration-200 filter-custom-chevron shrink-0" id="filterRoomChevron"></i>
                                        </button>

                                        <div id="filterRoomDropdown"
                                             class="hidden absolute left-0 top-full mt-2 w-full bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden filter-custom-menu max-h-60 overflow-y-auto">
                                            @foreach($roomOptions as $rVal => $rLabel)
                                                <div data-val="{{ $rVal }}"
                                                     class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold {{ (string)$currentRooms === (string)$rVal ? 'text-orange-500 bg-orange-50/60 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition cursor-pointer">
                                                    <span class="item-label">{{ $rLabel }}</span>
                                                    <i class="bi bi-check2 text-sm text-orange-500 item-check {{ (string)$currentRooms === (string)$rVal ? '' : 'hidden' }}"></i>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="roomCount" id="roomCountInput" value="{{ $currentRooms }}">
                                    </div>

                                    <!-- Building Type Custom Dropdown (Navbar Style) -->
                                    @php
                                        $currentBuildingType = request('buildingType', '');
                                        $currentBuildingLabel = __('listing.all_categories');
                                        if ($currentBuildingType) {
                                            $matching = collect($buildingTypes)->first(function ($b) use ($currentBuildingType) {
                                                $val = is_object($b) ? ($b->value ?? null) : (is_array($b) ? ($b['value'] ?? null) : $b);
                                                return (string) $val === (string) $currentBuildingType;
                                            });
                                            if ($matching) {
                                                $currentBuildingLabel = is_object($matching) ? ($matching->localized_name ?? ($matching->name['az'] ?? $matching->value)) : (is_array($matching) ? ($matching['name']['az'] ?? $matching['value'] ?? '') : (string) $matching);
                                            }
                                        }
                                    @endphp
                                    <div class="relative">
                                        <button id="filterBuildingBtn" type="button"
                                                class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 hover:bg-gray-100/80 border border-gray-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-gray-800 transition shadow-2xs cursor-pointer select-none">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <img src="{{ asset('images/layers.svg') }}" alt="layers" class="w-4 h-4 shrink-0">
                                                <span class="btn-display-text truncate font-semibold text-gray-800">{{ $currentBuildingLabel }}</span>
                                            </div>
                                            <i class="bi bi-chevron-down text-xs text-gray-400 transition-transform duration-200 filter-custom-chevron shrink-0" id="filterBuildingChevron"></i>
                                        </button>

                                        <div id="filterBuildingDropdown"
                                             class="hidden absolute left-0 top-full mt-2 w-full bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden filter-custom-menu max-h-60 overflow-y-auto">
                                            <div data-val=""
                                                 class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold {{ empty($currentBuildingType) ? 'text-orange-500 bg-orange-50/60 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition cursor-pointer">
                                                <span class="item-label">{{ __('listing.all_categories') }}</span>
                                                <i class="bi bi-check2 text-sm text-orange-500 item-check {{ empty($currentBuildingType) ? '' : 'hidden' }}"></i>
                                            </div>
                                            @foreach($buildingTypes ?? [] as $bType)
                                                @php
                                                    $bVal = is_object($bType) ? ($bType->value ?? '') : (is_array($bType) ? ($bType['value'] ?? '') : (string) $bType);
                                                    $bLabel = is_object($bType) ? ($bType->localized_name ?? ($bType->name['az'] ?? $bType->value)) : (is_array($bType) ? ($bType['name']['az'] ?? $bType['value'] ?? '') : (string) $bType);
                                                    $isSel = (string) $currentBuildingType === (string) $bVal;
                                                @endphp
                                                <div data-val="{{ $bVal }}"
                                                     class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold {{ $isSel ? 'text-orange-500 bg-orange-50/60 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition cursor-pointer">
                                                    <span class="item-label">{{ $bLabel }}</span>
                                                    <i class="bi bi-check2 text-sm text-orange-500 item-check {{ $isSel ? '' : 'hidden' }}"></i>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="buildingType" id="buildingTypeInput" value="{{ $currentBuildingType }}">
                                    </div>

                                    <!-- City Filter Trigger Button (Navbar Style) -->
                                    <div class="relative">
                                        <button id="openModal" type="button"
                                                class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 hover:bg-gray-100/80 border border-gray-200/90 rounded-2xl text-xs sm:text-sm font-semibold text-gray-800 transition shadow-2xs cursor-pointer select-none">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <img src="{{ asset('images/city.svg') }}" alt="city" class="w-4 h-4 shrink-0">
                                                @php
                                                    $selCity = $cities ? collect($cities)->first(fn($c) => (is_object($c) ? ($c->id ?? null) : ($c['id'] ?? null)) == request('cityId')) : null;
                                                    $selCityName = is_object($selCity) ? ($selCity->localized_name ?? ($selCity->name['az'] ?? '')) : (is_array($selCity) ? ($selCity['name']['az'] ?? '') : '');
                                                @endphp
                                                <span class="truncate font-semibold text-gray-800" data-role="display-value" data-filter="city" data-default-label="{{ __('listing.all_cities') }}">
                                                    {{ $selCityName ?: __('listing.all_cities') }}
                                                </span>
                                            </div>
                                            <i class="bi bi-chevron-down text-xs text-gray-400 shrink-0"></i>
                                        </button>
                                    </div>

                                </div>
                            </section>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="p-3 bg-gray-50 rounded-2xl border border-gray-200/90">
                                    <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2 text-xs sm:text-sm">
                                        <i class="bi bi-currency-dollar text-orange-500 text-base"></i>
                                        {{ __('listing.price') }}
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="text" name="minPrice" placeholder="{{ __('listing.min_price') }}"
                                               value="{{ request('minPrice') }}"
                                               class="w-1/2 px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-orange-500"/>
                                        <input type="text" name="maxPrice" placeholder="{{ __('listing.max_price') }}"
                                               value="{{ request('maxPrice') }}"
                                               class="w-1/2 px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-orange-500"/>
                                    </div>
                                </div>

                                <div class="desktop-only-block p-3 bg-gray-50 rounded-2xl border border-gray-200/90">
                                    <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2 text-xs sm:text-sm">
                                        <i class="bi bi-fullscreen text-orange-500 text-base"></i>
                                        {{ __('listing.area') }}
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="text" name="minArea" placeholder="{{ __('listing.min_area') }}"
                                               value="{{ request('minArea') }}"
                                               class="w-1/2 px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-orange-500"/>
                                        <input type="text" name="maxArea" placeholder="{{ __('listing.max_area') }}"
                                               value="{{ request('maxArea') }}"
                                               class="w-1/2 px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-orange-500"/>
                                    </div>
                                </div>

                                <div class="flex items-end">
                                    <button type="submit"
                                            class="w-full h-[42px] bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold text-xs sm:text-sm rounded-xl transition duration-200 shadow-2xs hover:shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                                        <i class="bi bi-search text-xs sm:text-sm"></i>
                                        <span>{{ __('listing.search') }}</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Gelişmiş Seçenekler Full-Width Bottom Bar -->
                            <div class="w-full pt-0.5">
                                <button type="button" id="moreFiltersBtn"
                                        class="w-full py-2.5 px-4 bg-[#fafafa] hover:bg-orange-50/60 border border-gray-200/90 hover:border-orange-200 rounded-2xl text-orange-500 hover:text-orange-600 font-semibold text-xs sm:text-sm flex items-center justify-center gap-2 transition duration-200 shadow-2xs group cursor-pointer">
                                    <span>{{ __('listing.advanced_options') }}</span>
                                    <i class="bi bi-sliders text-xs sm:text-sm text-orange-500 group-hover:scale-110 transition-transform duration-200"></i>
                                </button>
                            </div>

                            @include('components.modals.city-filter')
                            @include('components.modals.filter-more')
                        </form>

                        <hr class="text-gray-300 mt-7">

                        <div class="relative min-h-[400px]">
                            <!-- Loading overlay -->
                            <div id="listingLoader"
                                  class="hidden absolute inset-0 z-30 flex items-center justify-center bg-white/60 backdrop-blur-xs rounded-3xl transition duration-200">
                                <div class="bg-white/95 border border-gray-150 rounded-2xl p-6 shadow-xl flex items-center gap-3">
                                    <div
                                        class="w-7 h-7 border-3 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
                                    <span class="text-gray-700 font-semibold text-sm">{{ __('listing.loading') }}</span>
                                </div>
                            </div>

                            <div id="propertyContainer"
                                 class="pt-8 grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-3 sm:gap-6">
                                @include('pages.property.partials.cards', ['properties' => $properties])
                            </div>

                            <div id="paginationContainer" class="mt-14">
                                @include('pages.property.partials.pagination', ['properties' => $properties])
                            </div>
                        </div>
                    </div>
                </section>
    </div>
@endsection

@push('scripts')
    <script>
        // SEO URL-ləri üçün şəhər id → slug map və dinamik lokalizasiya tərcümələri
        window.KibrisKareRoutes = Object.assign({}, window.KibrisKareRoutes || {}, {
            citySlugs: @json($cities->pluck('slug', 'id'))
        });
        window.__trans = {
            all_categories: @json(__('listing.all_categories')),
            room_count: @json(__('listing.room_count')),
            rooms_suffix: @json(__('listing.rooms_suffix')),
            all_cities: @json(__('listing.all_cities')),
            district_suffix: @json(__('listing.district_suffix')),
        };
    </script>
    <script src="{{ asset('js/pages/property/list-filters.js') }}"></script>
    <script src="{{ asset('js/pages/property/listing.js') }}?v=2"></script>
@endpush
