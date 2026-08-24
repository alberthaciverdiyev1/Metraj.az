@extends('layouts.app')

@section('content')
    <div class="w-full pt-4">
        @include('components.scroll-top')

        <section class="property-listing py-4">
                    <div class="container mx-auto px-4 text-sm">

                        <form method="GET" action="{{ route('listing') }}" id="filterForm" class="space-y-4">
                            <section class="pt-4 max-w-full mx-auto">
                                <div class="flex justify-between items-center mb-3">
                                    @php
                                        $selectedAdType = request('adType', 'all');
                                        $dealTypes = \App\Modules\Location\Models\FilterOption::where('filter_id', 2)->get();
                                    @endphp
                                    <div
                                        class="flex gap-1 bg-gray-100 p-1 rounded-2xl border border-gray-200/50 max-w-max shadow-sm"
                                        data-role="add-type-toggle">
                                        <button type="button" data-value="all"
                                                class="px-5 py-2.5 rounded-xl font-bold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === 'all' || !$selectedAdType ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}"
                                                data-add-type="all">
                                            {{ __("Hamısı") }}
                                        </button>
                                        @foreach($dealTypes as $dt)
                                            <button type="button" data-value="{{ $dt->value }}"
                                                    class="px-5 py-2.5 rounded-xl font-bold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === $dt->value ? 'bg-white text-orange-500 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}"
                                                    data-add-type="{{ $dt->value }}">
                                                {{ $dt->name['az'] ?? $dt->value }}
                                            </button>
                                        @endforeach
                                        <input type="hidden" name="adType" id="adTypeInput"
                                               value="{{ request('adType') }}">
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
                                            '' => __('Otaq sayı (Hamısı)'),
                                            '1' => '1 ' . __('otaqlı'),
                                            '2' => '2 ' . __('otaqlı'),
                                            '3' => '3 ' . __('otaqlı'),
                                            '4' => '4 ' . __('otaqlı'),
                                            '5' => '5 ' . __('otaqlı'),
                                            '6' => '6+ ' . __('otaqlı'),
                                        ];
                                        $currentRoomsLabel = $roomOptions[$currentRooms] ?? __('Otaq sayı (Hamısı)');
                                    @endphp
                                    <div class="relative">
                                        <button id="filterRoomBtn" type="button"
                                                class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 hover:bg-gray-100/80 border border-gray-200/90 rounded-2xl text-xs sm:text-sm font-bold text-gray-800 transition shadow-2xs cursor-pointer select-none">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <img src="{{ asset('images/door.svg') }}" alt="door" class="w-4 h-4 shrink-0">
                                                <span class="btn-display-text truncate font-bold text-gray-800">{{ $currentRoomsLabel }}</span>
                                            </div>
                                            <i class="bi bi-chevron-down text-xs text-gray-400 transition-transform duration-200 filter-custom-chevron shrink-0" id="filterRoomChevron"></i>
                                        </button>

                                        <div id="filterRoomDropdown"
                                             class="hidden absolute left-0 top-full mt-2 w-full bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden filter-custom-menu max-h-60 overflow-y-auto">
                                            @foreach($roomOptions as $rVal => $rLabel)
                                                <div data-val="{{ $rVal }}"
                                                     class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold {{ (string)$currentRooms === (string)$rVal ? 'text-[#f1913d] bg-orange-50/60 font-bold' : 'text-gray-700 hover:bg-gray-50' }} transition cursor-pointer">
                                                    <span class="item-label">{{ $rLabel }}</span>
                                                    <i class="bi bi-check2 text-sm text-[#f1913d] item-check {{ (string)$currentRooms === (string)$rVal ? '' : 'hidden' }}"></i>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="roomCount" id="roomCountInput" value="{{ $currentRooms }}">
                                    </div>

                                    <!-- Building Type Custom Dropdown (Navbar Style) -->
                                    @php
                                        $currentBuildingType = request('buildingType', '');
                                        $currentBuildingLabel = __('Bütün Kateqoriyalar');
                                        if ($currentBuildingType) {
                                            $matching = collect($buildingTypes)->firstWhere('value', $currentBuildingType);
                                            if ($matching) $currentBuildingLabel = $matching->name['az'] ?? $matching->value;
                                        }
                                    @endphp
                                    <div class="relative">
                                        <button id="filterBuildingBtn" type="button"
                                                class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 hover:bg-gray-100/80 border border-gray-200/90 rounded-2xl text-xs sm:text-sm font-bold text-gray-800 transition shadow-2xs cursor-pointer select-none">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <img src="{{ asset('images/layers.svg') }}" alt="layers" class="w-4 h-4 shrink-0">
                                                <span class="btn-display-text truncate font-bold text-gray-800">{{ $currentBuildingLabel }}</span>
                                            </div>
                                            <i class="bi bi-chevron-down text-xs text-gray-400 transition-transform duration-200 filter-custom-chevron shrink-0" id="filterBuildingChevron"></i>
                                        </button>

                                        <div id="filterBuildingDropdown"
                                             class="hidden absolute left-0 top-full mt-2 w-full bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden filter-custom-menu max-h-60 overflow-y-auto">
                                            <div data-val=""
                                                 class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold {{ empty($currentBuildingType) ? 'text-[#f1913d] bg-orange-50/60 font-bold' : 'text-gray-700 hover:bg-gray-50' }} transition cursor-pointer">
                                                <span class="item-label">{{ __('Bütün Kateqoriyalar') }}</span>
                                                <i class="bi bi-check2 text-sm text-[#f1913d] item-check {{ empty($currentBuildingType) ? '' : 'hidden' }}"></i>
                                            </div>
                                            @foreach($buildingTypes ?? [] as $bType)
                                                @php
                                                    $bLabel = $bType->name['az'] ?? $bType->value;
                                                    $isSel = $currentBuildingType === $bType->value;
                                                @endphp
                                                <div data-val="{{ $bType->value }}"
                                                     class="flex items-center justify-between px-3.5 py-2 text-xs font-semibold {{ $isSel ? 'text-[#f1913d] bg-orange-50/60 font-bold' : 'text-gray-700 hover:bg-gray-50' }} transition cursor-pointer">
                                                    <span class="item-label">{{ $bLabel }}</span>
                                                    <i class="bi bi-check2 text-sm text-[#f1913d] item-check {{ $isSel ? '' : 'hidden' }}"></i>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="buildingType" id="buildingTypeInput" value="{{ $currentBuildingType }}">
                                    </div>

                                    <!-- City Filter Trigger Button (Navbar Style) -->
                                    <div class="relative">
                                        <button id="openModal" type="button"
                                                class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 hover:bg-gray-100/80 border border-gray-200/90 rounded-2xl text-xs sm:text-sm font-bold text-gray-800 transition shadow-2xs cursor-pointer select-none">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <img src="{{ asset('images/city.svg') }}" alt="city" class="w-4 h-4 shrink-0">
                                                <span class="truncate font-bold text-gray-800" data-role="display-value" data-filter="city">
                                                    {{ $cities->firstWhere('id', request('cityId'))?->name['az'] ?? ($cities->firstWhere('id', request('cityId'))?->value ?? __('Bütün Şəhərlər')) }}
                                                </span>
                                            </div>
                                            <i class="bi bi-chevron-down text-xs text-gray-400 shrink-0"></i>
                                        </button>
                                    </div>

                                </div>
                            </section>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                                        <i class="bi bi-currency-dollar text-orange-500 text-xl"></i>
                                        {{ __('Price (AZN)') }}
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="text" name="minPrice" placeholder="{{ __('Min qiymet') }}"
                                               value="{{ request('minPrice') }}"
                                               class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none"/>
                                        <input type="text" name="maxPrice" placeholder="{{ __('Max qiymet') }}"
                                               value="{{ request('maxPrice') }}"
                                               class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none"/>
                                    </div>
                                </div>

                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                                        <i class="bi bi-fullscreen text-orange-500 text-xl"></i>
                                        {{ __('Area (m²)') }}
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="text" name="minArea" placeholder="{{ __('Min olcu') }}"
                                               value="{{ request('minArea') }}"
                                               class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none"/>
                                        <input type="text" name="maxArea" placeholder="{{ __('Max olcu') }}"
                                               value="{{ request('maxArea') }}"
                                               class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none"/>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 w-full md:w-64">
                                    <button type="button" id="moreFiltersBtn"
                                            class="w-full flex items-center justify-center text-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-[var(--primary)] transition">
                                        + {{ __("More") }}
                                    </button>

                                    <button type="submit"
                                            class="w-full px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                                        {{ __('Search') }}
                                    </button>
                                </div>
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
                                    <span class="text-gray-700 font-bold text-sm">{{ __('Yüklənir...') }}</span>
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
        // SEO URL-ləri üçün şəhər id → slug map (listing.js tərəfindən istifadə olunur)
        window.MetrajRoutes = Object.assign({}, window.MetrajRoutes || {}, {
            citySlugs: @json($cities->pluck('slug', 'id'))
        });
    </script>
    <script src="{{ asset('js/pages/property/list-filters.js') }}"></script>
    <script src="{{ asset('js/pages/property/listing.js') }}?v={{ time() }}"></script>
@endpush
