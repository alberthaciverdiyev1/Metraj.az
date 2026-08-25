@extends('layouts.app')

@section('title', __('requests.page_title') . ' - KibrisKare.com')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6">

    @if(isset($breadcrumbs))
        <div class="mb-5">
            @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
        </div>
    @endif

    <!-- Filter Card with Dynamic JS Mode Switching & Live AJAX -->
    <div class="bg-white border border-gray-200 rounded-3xl p-5 sm:p-6 mb-8 shadow-xs">
        <form id="requestFilterForm" method="GET" action="{{ route('requests.index') }}" class="space-y-5" onsubmit="return false;">

            <!-- Category Tabs (Top) -->
            @php
                $activeType = request('type', '');
            @endphp
            <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-2xl overflow-x-auto" id="categoryTabs">
                <button type="button" data-type=""
                        class="cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer {{ empty($activeType) ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    {{ __('requests.all_requests') }}
                </button>
                <button type="button" data-type="buy"
                        class="cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer {{ $activeType === 'buy' ? 'bg-emerald-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-cart-shopping mr-1 text-[11px]"></i> {{ __('requests.want_to_buy') }}
                </button>
                <button type="button" data-type="rent"
                        class="cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer {{ $activeType === 'rent' || $activeType === 'rent_monthly' ? 'bg-blue-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-key mr-1 text-[11px]"></i> {{ __('requests.looking_for_rent') }}
                </button>
                <button type="button" data-type="daily"
                        class="cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer {{ $activeType === 'daily' || $activeType === 'rent_daily' ? 'bg-amber-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-calendar-day mr-1 text-[11px]"></i> {{ __('requests.daily') }}
                </button>
                <button type="button" data-type="roommate"
                        class="cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer {{ $activeType === 'roommate' ? 'bg-purple-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-people-roof mr-1 text-[11px]"></i> {{ __('requests.roommate') }}
                </button>
            </div>
            <input type="hidden" name="type" id="filterTypeInput" value="{{ $activeType }}">

            <!-- Primary Inputs Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Search -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.search') }}</label>
                    <div class="relative">
                        <input type="text" name="search" id="filterSearchInput" value="{{ request('search') }}"
                               placeholder="{{ __('requests.search_placeholder') }}"
                               autocomplete="off"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl pl-9 pr-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <!-- City -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.city') }}</label>
                    <select name="city_id" id="filterCitySelect"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('requests.all_cities') }}</option>
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

                <!-- Property Type (Dynamically hidden for roommate) -->
                <div id="filterPropertyTypeCol">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.property_type') }}</label>
                    <select name="property_type" id="filterPropertyTypeSelect"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('requests.all_types') }}</option>
                        <option value="Mənzil" {{ request('property_type') === 'Mənzil' ? 'selected' : '' }}>{{ __('requests.apartment') }}</option>
                        <option value="Həyət evi" {{ request('property_type') === 'Həyət evi' ? 'selected' : '' }}>{{ __('requests.house') }}</option>
                        <option value="Villa" {{ request('property_type') === 'Villa' ? 'selected' : '' }}>{{ __('requests.villa') }}</option>
                        <option value="Torpaq" {{ request('property_type') === 'Torpaq' ? 'selected' : '' }}>{{ __('requests.land') }}</option>
                        <option value="Obyekt" {{ request('property_type') === 'Obyekt' ? 'selected' : '' }}>{{ __('requests.commercial') }}</option>
                        <option value="Ofis" {{ request('property_type') === 'Ofis' ? 'selected' : '' }}>{{ __('requests.office') }}</option>
                    </select>
                </div>

                <!-- Roommate Gender Selector (Dynamically shown only for roommate) -->
                <div id="filterRoommateGenderCol" class="hidden">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.gender_requirement') }}</label>
                    <select name="gender_preference" id="filterGenderSelect"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('requests.no_preference') }}</option>
                        <option value="female" {{ request('gender_preference') === 'female' ? 'selected' : '' }}>{{ __('requests.female_only') }}</option>
                        <option value="male" {{ request('gender_preference') === 'male' ? 'selected' : '' }}>{{ __('requests.male_only') }}</option>
                    </select>
                </div>

                <!-- Max Budget -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5" id="budgetLabel">{{ __('requests.budget_azn') }}</label>
                    <input type="number" name="max_budget" id="filterMaxBudgetInput" value="{{ request('max_budget') }}" placeholder="0" min="0"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>

            </div>

            <!-- Dynamic Bottom Checkboxes & Options -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-3 border-t border-gray-100">
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-gray-700" id="dynamicCheckboxes">

                    <!-- Buy Options -->
                    <div id="buyCheckboxes" class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="has_deed" id="filterHasDeed" value="1" {{ request('has_deed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('requests.only_with_deed') }}</span>
                        </label>

                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="mortgage_eligible" id="filterMortgage" value="1" {{ request('mortgage_eligible') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('requests.mortgage_eligible') }}</span>
                        </label>
                    </div>

                    <!-- Rent Options -->
                    <div id="rentCheckboxes" class="hidden items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="bills_included" id="filterBillsIncluded" value="1" {{ request('bills_included') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('requests.bills_included') }}</span>
                        </label>
                    </div>

                    <!-- Roommate Options -->
                    <div id="roommateCheckboxes" class="hidden items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="smoker_allowed" id="filterSmokerAllowed" value="1" {{ request('smoker_allowed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('requests.smoker_allowed') }}</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="pet_allowed" id="filterPetAllowed" value="1" {{ request('pet_allowed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('requests.pet_allowed') }}</span>
                        </label>
                    </div>

                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" id="resetFiltersBtn"
                            class="px-4 py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-xl transition cursor-pointer">
                        {{ __('requests.reset') }}
                    </button>
                    <button type="button" id="submitFilterBtn"
                            class="px-5 py-2 text-xs sm:text-sm font-semibold text-white bg-gray-900 hover:bg-[#f1913d] rounded-xl transition shadow-xs cursor-pointer">
                        <i class="bi bi-funnel mr-1"></i> {{ __('requests.filter_search') }}
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Live Listings Container -->
    <div class="relative min-h-[250px]">
        
        <!-- Loading overlay -->
        <div id="requestLoadingIndicator" class="hidden absolute inset-0 bg-white/70 backdrop-blur-[1px] z-20 flex items-center justify-center rounded-2xl transition-opacity duration-200">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-xl shadow-md text-xs font-semibold">
                <i class="fa-solid fa-spinner fa-spin text-orange-500"></i>
                <span>{{ __('requests.updating') }}</span>
            </div>
        </div>

        <div id="requestListingsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 transition-opacity duration-200">
            @include('pages.requests.partials.cards', ['requests' => $requests])
        </div>

        <!-- Pagination Container -->
        <div id="requestPaginationContainer">
            @include('pages.requests.partials.pagination', ['requests' => $requests])
        </div>
    </div>

</div>

@push('scripts')
    <script>
        window.requestsIndexConfig = {
            routes: {
                index: "{{ route('requests.index') }}"
            },
            labels: {
                roommateBudget: "{{ __('requests.monthly_share_budget') }}",
                rentBudget: "{{ __('requests.monthly_rent_budget') }}",
                dailyBudget: "{{ __('requests.daily_budget') }}",
                buyBudget: "{{ __('requests.buy_budget') }}",
                maxBudget: "{{ __('requests.budget_azn') }}"
            }
        };
    </script>
    <script src="{{ asset('js/pages/requests/index.js') }}"></script>
@endpush
@endsection
