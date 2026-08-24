@extends('layouts.app')

@section('title', __('Axtarıram - Əmlak və Yoldaş Tələbləri') . ' - Metraj.az')

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
                        class="cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer {{ empty($activeType) ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    {{ __('Bütün Tələblər') }}
                </button>
                <button type="button" data-type="buy"
                        class="cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer {{ $activeType === 'buy' ? 'bg-emerald-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-cart-shopping mr-1 text-[11px]"></i> {{ __('Almaq İstəyirəm') }}
                </button>
                <button type="button" data-type="rent"
                        class="cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer {{ $activeType === 'rent' || $activeType === 'rent_monthly' ? 'bg-blue-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-key mr-1 text-[11px]"></i> {{ __('Kirayə Axtarıram') }}
                </button>
                <button type="button" data-type="daily"
                        class="cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer {{ $activeType === 'daily' || $activeType === 'rent_daily' ? 'bg-amber-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-calendar-day mr-1 text-[11px]"></i> {{ __('Günlük') }}
                </button>
                <button type="button" data-type="roommate"
                        class="cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer {{ $activeType === 'roommate' ? 'bg-purple-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fa-solid fa-people-roof mr-1 text-[11px]"></i> {{ __('Otaq Yoldaşı') }}
                </button>
            </div>
            <input type="hidden" name="type" id="filterTypeInput" value="{{ $activeType }}">

            <!-- Primary Inputs Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Search -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Axtarış') }}</label>
                    <div class="relative">
                        <input type="text" name="search" id="filterSearchInput" value="{{ request('search') }}"
                               placeholder="{{ __('Açar söz, metro, rayon...') }}"
                               autocomplete="off"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl pl-9 pr-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <!-- City -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Şəhər') }}</label>
                    <select name="city_id" id="filterCitySelect"
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

                <!-- Property Type (Dynamically hidden for roommate) -->
                <div id="filterPropertyTypeCol">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Əmlak Növü') }}</label>
                    <select name="property_type" id="filterPropertyTypeSelect"
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

                <!-- Roommate Gender Selector (Dynamically shown only for roommate) -->
                <div id="filterRoommateGenderCol" class="hidden">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Cinsiyyət Tələbi') }}</label>
                    <select name="gender_preference" id="filterGenderSelect"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('Fərqi yoxdur (Hamı)') }}</option>
                        <option value="female" {{ request('gender_preference') === 'female' ? 'selected' : '' }}>{{ __('Yalnız Xanım') }}</option>
                        <option value="male" {{ request('gender_preference') === 'male' ? 'selected' : '' }}>{{ __('Yalnız Bəy') }}</option>
                    </select>
                </div>

                <!-- Max Budget -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5" id="budgetLabel">{{ __('Maksimum Büdcə (₼)') }}</label>
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
                            <span>{{ __('Yalnız Kupçalı') }}</span>
                        </label>

                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="mortgage_eligible" id="filterMortgage" value="1" {{ request('mortgage_eligible') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('İpotekaya yararlı') }}</span>
                        </label>
                    </div>

                    <!-- Rent Options -->
                    <div id="rentCheckboxes" class="hidden items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="bills_included" id="filterBillsIncluded" value="1" {{ request('bills_included') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('Kommunal xərclər daxildir') }}</span>
                        </label>
                    </div>

                    <!-- Roommate Options -->
                    <div id="roommateCheckboxes" class="hidden items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="smoker_allowed" id="filterSmokerAllowed" value="1" {{ request('smoker_allowed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('Siqaret olar') }}</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="pet_allowed" id="filterPetAllowed" value="1" {{ request('pet_allowed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('Ev heyvanı olar') }}</span>
                        </label>
                    </div>

                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" id="resetFiltersBtn"
                            class="px-4 py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-xl transition cursor-pointer">
                        {{ __('Sıfırla') }}
                    </button>
                    <button type="button" id="submitFilterBtn"
                            class="px-5 py-2 text-xs sm:text-sm font-bold text-white bg-gray-900 hover:bg-[#f1913d] rounded-xl transition shadow-xs cursor-pointer">
                        <i class="bi bi-funnel mr-1"></i> {{ __('Axtar') }}
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
                <span>{{ __('Yenilənir...') }}</span>
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
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('requestFilterForm');
    const typeInput = document.getElementById('filterTypeInput');
    const tabBtns = document.querySelectorAll('.cat-tab-btn');

    const propTypeCol = document.getElementById('filterPropertyTypeCol');
    const roommateGenderCol = document.getElementById('filterRoommateGenderCol');
    const budgetLabel = document.getElementById('budgetLabel');
    const buyCheckboxes = document.getElementById('buyCheckboxes');
    const rentCheckboxes = document.getElementById('rentCheckboxes');
    const roommateCheckboxes = document.getElementById('roommateCheckboxes');

    const listingsContainer = document.getElementById('requestListingsContainer');
    const paginationContainer = document.getElementById('requestPaginationContainer');
    const loadingIndicator = document.getElementById('requestLoadingIndicator');

    let debounceTimer = null;
    let currentAbortController = null;

    function updateFilterUI(type) {
        tabBtns.forEach(btn => {
            const btnType = btn.getAttribute('data-type');
            btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer text-gray-600 hover:text-gray-900';

            if (btnType === type) {
                if (type === 'buy') btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer bg-emerald-600 text-white shadow-xs';
                else if (type === 'rent') btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer bg-blue-600 text-white shadow-xs';
                else if (type === 'daily') btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer bg-amber-600 text-white shadow-xs';
                else if (type === 'roommate') btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer bg-purple-600 text-white shadow-xs';
                else btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl whitespace-nowrap transition cursor-pointer bg-white text-gray-900 shadow-xs';
            }
        });

        if (type === 'roommate') {
            propTypeCol.classList.add('hidden');
            roommateGenderCol.classList.remove('hidden');
            budgetLabel.textContent = 'Aylıq Pay Büdcəsi (₼)';
            buyCheckboxes.classList.add('hidden');
            rentCheckboxes.classList.remove('hidden');
            roommateCheckboxes.classList.remove('hidden');
        } else if (type === 'rent') {
            propTypeCol.classList.remove('hidden');
            roommateGenderCol.classList.add('hidden');
            budgetLabel.textContent = 'Aylıq Kirayə Büdcəsi (₼)';
            buyCheckboxes.classList.add('hidden');
            rentCheckboxes.classList.remove('hidden');
            roommateCheckboxes.classList.add('hidden');
        } else if (type === 'daily') {
            propTypeCol.classList.remove('hidden');
            roommateGenderCol.classList.add('hidden');
            budgetLabel.textContent = 'Günlük Büdcə (₼)';
            buyCheckboxes.classList.add('hidden');
            rentCheckboxes.classList.add('hidden');
            roommateCheckboxes.classList.add('hidden');
        } else if (type === 'buy') {
            propTypeCol.classList.remove('hidden');
            roommateGenderCol.classList.add('hidden');
            budgetLabel.textContent = 'Alış Büdcəsi (₼)';
            buyCheckboxes.classList.remove('hidden');
            rentCheckboxes.classList.add('hidden');
            roommateCheckboxes.classList.add('hidden');
        } else {
            propTypeCol.classList.remove('hidden');
            roommateGenderCol.classList.add('hidden');
            budgetLabel.textContent = 'Maksimum Büdcə (₼)';
            buyCheckboxes.classList.remove('hidden');
            rentCheckboxes.classList.remove('hidden');
            roommateCheckboxes.classList.add('hidden');
        }
    }

    function buildQueryString(page = null) {
        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value !== '' && value !== null) {
                params.append(key, value);
            }
        }

        if (page && page > 1) {
            params.set('page', page);
        }

        return params.toString();
    }

    function fetchFilteredResults(page = 1, updateUrl = true) {
        if (currentAbortController) {
            currentAbortController.abort();
        }
        currentAbortController = new AbortController();

        const queryString = buildQueryString(page);
        const url = `/axtariram${queryString ? '?' + queryString : ''}`;

        if (updateUrl) {
            window.history.pushState({ path: url }, '', url);
        }

        loadingIndicator.classList.remove('hidden');
        listingsContainer.style.opacity = '0.5';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            signal: currentAbortController.signal
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                listingsContainer.innerHTML = data.html;
                paginationContainer.innerHTML = data.pagination;
                bindPaginationEvents();
            }
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error('Filter AJAX error:', err);
            }
        })
        .finally(() => {
            loadingIndicator.classList.add('hidden');
            listingsContainer.style.opacity = '1';
        });
    }

    function bindPaginationEvents() {
        if (!paginationContainer) return;
        const links = paginationContainer.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                if (href) {
                    try {
                        const urlObj = new URL(href, window.location.origin);
                        const page = urlObj.searchParams.get('page') || 1;
                        fetchFilteredResults(page, true);
                        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } catch (e) {
                        window.location.href = href;
                    }
                }
            });
        });
    }

    // Category Tabs click
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const selectedType = btn.getAttribute('data-type');
            typeInput.value = selectedType;
            updateFilterUI(selectedType);
            fetchFilteredResults(1, true);
        });
    });

    // Inputs with instant change
    const instantInputs = form.querySelectorAll('select, input[type="checkbox"]');
    instantInputs.forEach(input => {
        input.addEventListener('change', function () {
            fetchFilteredResults(1, true);
        });
    });

    // Text & Number inputs with debounce
    const textInputs = form.querySelectorAll('input[type="text"], input[type="number"]');
    textInputs.forEach(input => {
        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchFilteredResults(1, true);
            }, 350);
        });
    });

    // Reset button
    const resetBtn = document.getElementById('resetFiltersBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            form.reset();
            typeInput.value = '';
            updateFilterUI('');
            fetchFilteredResults(1, true);
        });
    }

    // Search button
    const submitBtn = document.getElementById('submitFilterBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function () {
            fetchFilteredResults(1, true);
        });
    }

    // Handle browser Back/Forward navigation
    window.addEventListener('popstate', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const currentType = urlParams.get('type') || '';
        typeInput.value = currentType;
        updateFilterUI(currentType);

        // Fill form fields from URL params
        const searchInput = document.getElementById('filterSearchInput');
        if (searchInput) searchInput.value = urlParams.get('search') || '';

        const citySelect = document.getElementById('filterCitySelect');
        if (citySelect) citySelect.value = urlParams.get('city_id') || '';

        const propTypeSelect = document.getElementById('filterPropertyTypeSelect');
        if (propTypeSelect) propTypeSelect.value = urlParams.get('property_type') || '';

        const budgetInput = document.getElementById('filterMaxBudgetInput');
        if (budgetInput) budgetInput.value = urlParams.get('max_budget') || '';

        fetchFilteredResults(urlParams.get('page') || 1, false);
    });

    // Initial setup
    updateFilterUI(typeInput.value);
    bindPaginationEvents();
});
</script>
@endpush
@endsection
