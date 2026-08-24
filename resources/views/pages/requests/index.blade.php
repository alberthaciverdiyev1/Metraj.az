@extends('layouts.app')

@section('title', __('Axtarıram - Əmlak və Yoldaş Tələbləri') . ' - Metraj.az')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6">

    @if(isset($breadcrumbs))
        <div class="mb-5">
            @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
        </div>
    @endif


    <!-- Filter Card with Dynamic JS Mode Switching -->
    <div class="bg-white border border-gray-200 rounded-3xl p-5 sm:p-6 mb-8 shadow-xs">
        <form id="requestFilterForm" method="GET" action="{{ route('requests.index') }}" class="space-y-5">

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

                <!-- Property Type (Dynamically hidden for roommate) -->
                <div id="filterPropertyTypeCol">
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

                <!-- Roommate Gender Selector (Dynamically shown only for roommate) -->
                <div id="filterRoommateGenderCol" class="hidden">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Cinsiyyət Tələbi') }}</label>
                    <select name="gender_preference"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('Fərqi yoxdur (Hamı)') }}</option>
                        <option value="female" {{ request('gender_preference') === 'female' ? 'selected' : '' }}>{{ __('Yalnız Xanım') }}</option>
                        <option value="male" {{ request('gender_preference') === 'male' ? 'selected' : '' }}>{{ __('Yalnız Bəy') }}</option>
                    </select>
                </div>

                <!-- Max Budget -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5" id="budgetLabel">{{ __('Maksimum Büdcə (₼)') }}</label>
                    <input type="number" name="max_budget" value="{{ request('max_budget') }}" placeholder="0" min="0"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>

            </div>

            <!-- Dynamic Bottom Checkboxes & Options -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-3 border-t border-gray-100">
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-gray-700" id="dynamicCheckboxes">

                    <!-- Buy Options -->
                    <div id="buyCheckboxes" class="flex items-center gap-4">
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

                    <!-- Rent Options -->
                    <div id="rentCheckboxes" class="hidden items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="bills_included" value="1" {{ request('bills_included') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('Kommunal xərclər daxildir') }}</span>
                        </label>
                    </div>

                    <!-- Roommate Options -->
                    <div id="roommateCheckboxes" class="hidden items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="smoker_allowed" value="1" {{ request('smoker_allowed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('Siqaret olar') }}</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="pet_allowed" value="1" {{ request('pet_allowed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span>{{ __('Ev heyvanı olar') }}</span>
                        </label>
                    </div>

                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('requests.index') }}"
                       class="px-4 py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        {{ __('Sıfırla') }}
                    </a>
                    <button type="submit"
                            class="px-5 py-2 text-xs sm:text-sm font-bold text-white bg-gray-900 hover:bg-[#f1913d] rounded-xl transition shadow-xs cursor-pointer">
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

    function updateFilterUI(type) {
        // Reset tab classes
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

        // Dynamic elements based on selection
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
            // All
            propTypeCol.classList.remove('hidden');
            roommateGenderCol.classList.add('hidden');
            budgetLabel.textContent = 'Maksimum Büdcə (₼)';
            buyCheckboxes.classList.remove('hidden');
            rentCheckboxes.classList.remove('hidden');
            roommateCheckboxes.classList.add('hidden');
        }
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const selectedType = btn.getAttribute('data-type');
            typeInput.value = selectedType;
            updateFilterUI(selectedType);
            form.submit();
        });
    });

    // Initial setup on load
    updateFilterUI(typeInput.value);
});
</script>
@endpush
@endsection
