@extends('layouts.app')

@section('content')
    <div class="w-full pt-4">
        @include('components.scroll-top')

        <section class="property-listing relative py-4">
                    <div class="container mx-auto px-4 text-sm">

                        <form method="GET" action="/listing" id="filterForm" class="space-y-4">
                            <section class="pt-4 max-w-full mx-auto relative z-10">
                                <div class="flex justify-between items-center mb-3">
                                    @php
                                        $selectedAdType = request('adType', 'all');
                                        $dealTypes = \App\Core\Infrastructure\Persistence\Eloquent\Models\FilterOption::where('filter_id', 2)->get();
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

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div
                                        class="relative p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer dropdown-select"
                                        data-filter="roomCount">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <img src="/images/door.svg" alt="door">
                                                <span class="text-gray-700" data-role="display-value"
                                                      data-filter="roomCount">
                                                {{ request('roomCount') ? request('roomCount').' otaqli' : __('Otaq sayi') }}
                                            </span>
                                            </div>
                                            <i class="bi bi-chevron-down text-orange-500 transition-transform"></i>
                                        </div>
                                        <div
                                            class="absolute left-0 mt-2 w-full bg-white shadow-lg border border-gray-200 rounded-lg z-10 hidden dropdown-menu">
                                            <ul class="divide-y divide-gray-100">
                                                <li class="p-2 hover:bg-gray-100 cursor-pointer"
                                                    data-value="">{{ __('Hamisi') }}</li>
                                                @for($i=1; $i<6; $i++)

                                                    <li class="p-2 hover:bg-gray-100 cursor-pointer"
                                                        data-value="{{$i}}">{{$i}}
                                                        otaqli
                                                    </li>
                                                @endfor
                                                <li class="p-2 hover:bg-gray-100 cursor-pointer" data-value="6">6+
                                                    otaqli
                                                </li>
                                            </ul>
                                        </div>
                                        <input type="hidden" name="roomCount" value="{{ request('roomCount') }}">
                                    </div>

                                    <div
                                        class="relative p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer dropdown-select"
                                        data-filter="buildingType">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <img src="/images/layers.svg" alt="layers">
                                                <span class="text-gray-700" data-role="display-value"
                                                      data-filter="buildingType">
                                                @php
                                                    $selectedType = request('buildingType');
                                                    $selectedTypeLabel = __('Butun Kateqoriyalar');
                                                    if ($selectedType) {
                                                        $matchingType = collect($buildingTypes)->firstWhere('value', $selectedType);
                                                        if ($matchingType) $selectedTypeLabel = $matchingType->name['az'] ?? $matchingType->value;
                                                    }
                                                @endphp
                                                    {{ $selectedTypeLabel }}
                                            </span>
                                            </div>
                                            <i class="bi bi-chevron-down text-orange-500 transition-transform"></i>
                                        </div>
                                        <div
                                            class="absolute dropdown-menu left-0 mt-2 w-full bg-white shadow-lg border border-gray-200 rounded-lg z-10 hidden">
                                            <ul class="divide-y divide-gray-100">
                                                <li class="p-2 hover:bg-gray-100 cursor-pointer"
                                                    data-value="">{{ __('Butun Kateqoriyalar') }}</li>
                                                @foreach($buildingTypes ?? [] as $buildingType)
                                                    <li class="p-2 hover:bg-gray-100 cursor-pointer"
                                                        data-value="{{ $buildingType->value }}">
                                                        {{ $buildingType->name['az'] ?? $buildingType->value }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <input type="hidden" name="buildingType" value="{{ request('buildingType') }}">
                                    </div>

                                    <div
                                        class="relative p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer w-full"
                                        id="openModal">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <img src="/images/city.svg" alt="city">
                                                <span class="text-gray-700" data-role="display-value"
                                                      data-filter="city">
                                                {{ $cities->firstWhere('id', request('cityId'))?->name['az'] ?? ($cities->firstWhere('id', request('cityId'))?->value ?? __('Butun Seherler')) }}
                                            </span>
                                            </div>
                                            <i class="bi bi-chevron-down text-orange-500 transition-transform"></i>
                                        </div>
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
                        <h2 class="text-xl text-gray-700 mt-6">
                            {{ __('Butun elanlar') }}
                        </h2>

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
                                @forelse($properties as $property)
                                    @include('components.property-card', ['property' => $property])
                                @empty
                                    <p class="col-span-full text-center text-gray-500 py-10">{{ __('Elan tapılmadı.') }}</p>
                                @endforelse
                            </div>

                            <div id="paginationContainer" class="mt-14">
                                {{ $properties->onEachSide(2)->appends(request()->query())->links('pagination.metraj') }}
                            </div>
                        </div>
                    </div>
                </section>
    </div>
@endsection

@push('scripts')
    <script src="/js/pages/property/listing.js"></script>
@endpush
