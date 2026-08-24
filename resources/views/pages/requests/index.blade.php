@extends('layouts.app')

@section('title', __('Axtarıram - Əmlak və Yoldaş Tələbləri') . ' - Metraj.az')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6">

    @if(isset($breadcrumbs))
        <div class="mb-5">
            @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
        </div>
    @endif

    <!-- Hero Header Banner -->
    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 mb-8 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-200/60">
                <i class="fa-solid fa-bullhorn"></i>
                <span>{{ __('Tələb və İstək Elanları') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight">
                {{ __('Nə axtardığınızı bildirin, uyğun təklifləri qəbul edin') }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                {{ __('Ev almaq, kirayələmək, günlük qalmaq və ya otaq yoldaşı tapmaq istəyirsinizsə elanınızı yerləşdirin. Əmlak sahibləri və agentlər sizinlə birbaşa əlaqə saxlasın.') }}
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto">
            <a href="{{ route('requests.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#f1913d] hover:bg-[#e07f2c] text-white font-bold text-sm rounded-2xl shadow-sm transition hover:shadow-md">
                <i class="bi bi-plus-circle-fill text-base"></i>
                <span>{{ __('Tələb Elanı Yerləşdir') }}</span>
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white border border-gray-200 rounded-3xl p-5 sm:p-6 mb-8 shadow-xs">
        <form id="requestFilterForm" method="GET" action="{{ route('requests.index') }}" class="space-y-5">

            <!-- Category Tabs (Top) -->
            <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-2xl overflow-x-auto">
                <a href="{{ route('requests.index') }}"
                   class="px-4 py-2 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition {{ empty(request('type')) ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    {{ __('Bütün Tələblər') }}
                </a>
                <a href="{{ route('requests.index', ['type' => 'buy']) }}"
                   class="px-4 py-2 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition {{ request('type') === 'buy' ? 'bg-emerald-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-cart-shopping mr-1 text-[11px]"></i> {{ __('Almaq İstəyirəm') }}
                </a>
                <a href="{{ route('requests.index', ['type' => 'rent']) }}"
                   class="px-4 py-2 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition {{ request('type') === 'rent' || request('type') === 'rent_monthly' ? 'bg-blue-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-key mr-1 text-[11px]"></i> {{ __('Kirayə Axtarıram') }}
                </a>
                <a href="{{ route('requests.index', ['type' => 'daily']) }}"
                   class="px-4 py-2 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition {{ request('type') === 'daily' || request('type') === 'rent_daily' ? 'bg-amber-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-calendar-day mr-1 text-[11px]"></i> {{ __('Günlük') }}
                </a>
                <a href="{{ route('requests.index', ['type' => 'roommate']) }}"
                   class="px-4 py-2 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition {{ request('type') === 'roommate' ? 'bg-purple-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-people-roof mr-1 text-[11px]"></i> {{ __('Otaq Yoldaşı') }}
                </a>
            </div>
            <input type="hidden" name="type" value="{{ request('type') }}">

            <!-- Inputs Row: Search, City, Property Type, Budget -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Search -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Axtarış') }}</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('Açar söz, metro, rayon...') }}"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl pl-9 pr-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <!-- City -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Şəhər') }}</label>
                    <select name="city_id"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('Bütün Şəhərlər') }}</option>
                        @foreach($cities as $city)
                            @php
                                $cName = is_array($city->name) ? ($city->name[app()->getLocale()] ?? $city->name['az'] ?? reset($city->name)) : $city->name;
                            @endphp
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $cName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Property Type (if not roommate) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Əmlak Növü') }}</label>
                    <select name="property_type"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('Bütün Növlər') }}</option>
                        <option value="Mənzil" {{ request('property_type') === 'Mənzil' ? 'selected' : '' }}>{{ __('Mənzil') }}</option>
                        <option value="Həyət evi" {{ request('property_type') === 'Həyət evi' ? 'selected' : '' }}>{{ __('Həyət evi / Bağ') }}</option>
                        <option value="Villa" {{ request('property_type') === 'Villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                        <option value="Torpaq" {{ request('property_type') === 'Torpaq' ? 'selected' : '' }}>{{ __('Torpaq') }}</option>
                        <option value="Obyekt" {{ request('property_type') === 'Obyekt' ? 'selected' : '' }}>{{ __('Obyekt') }}</option>
                        <option value="Ofis" {{ request('property_type') === 'Ofis' ? 'selected' : '' }}>{{ __('Ofis') }}</option>
                    </select>
                </div>

                <!-- Max Budget -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Maksimum Büdcə (₼)') }}</label>
                    <input type="number" name="max_budget" value="{{ request('max_budget') }}" placeholder="Məs: 150000" min="0"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>

            </div>

            <!-- Bottom Checkboxes & Filter Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-3 border-t border-gray-100">
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-gray-700">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="has_deed" value="1" {{ request('has_deed') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                        <span>{{ __('Yalnız Kupçalı') }}</span>
                    </label>

                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="mortgage_eligible" value="1" {{ request('mortgage_eligible') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                        <span>{{ __('İpotekaya yararlı') }}</span>
                    </label>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('requests.index') }}"
                       class="px-4 py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        {{ __('Sıfırla') }}
                    </a>
                    <button type="submit"
                            class="px-5 py-2 text-xs sm:text-sm font-bold text-white bg-gray-900 hover:bg-[#f1913d] rounded-xl transition shadow-xs">
                        <i class="bi bi-funnel mr-1"></i> {{ __('Axtar') }}
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Listings Container -->
    <div id="requestListingsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @include('pages.requests.partials.cards', ['requests' => $requests])
    </div>

    <!-- Pagination Container -->
    <div id="requestPaginationContainer">
        @include('pages.requests.partials.pagination', ['requests' => $requests])
    </div>

</div>
@endsection
