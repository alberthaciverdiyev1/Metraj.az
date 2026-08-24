@extends('layouts.app')

@section('title', __('Otaq və Ev Yoldaşı Elanları') . ' - Metraj.az')

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
                <i class="fa-solid fa-people-roof"></i>
                <span>{{ __('Otaq & Ev Yoldaşı Axtarışı') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight">
                {{ __('Birlikdə yaşamaq üçün ideal otaq və ya yoldaş tap') }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                {{ __('Evində boş otaq olanlar və ya birgə ev tutmaq istəyənlər üçün xüsusi elan platforması. Rahat filtrlə, əlaqə saxla və büdcənə qənaət et.') }}
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto">
            <a href="{{ route('roommates.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#f1913d] hover:bg-[#e07f2c] text-white font-bold text-sm rounded-2xl shadow-sm transition hover:shadow-md">
                <i class="bi bi-plus-circle-fill text-base"></i>
                <span>{{ __('Otaq Yoldaşı Elanı Ver') }}</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white border border-gray-200 rounded-3xl p-5 sm:p-6 mb-8 shadow-xs">
        <form id="roommateFilterForm" method="GET" action="{{ route('roommates.index') }}" class="space-y-5">

            <!-- Top Filter Row: Listing Type & Gender Pills -->
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                
                <!-- Listing Type Selector -->
                <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-2xl">
                    <button type="button" data-filter-type=""
                            class="filter-type-btn flex-1 sm:flex-none px-4 py-2 text-xs sm:text-sm font-bold rounded-xl transition {{ empty(request('listing_type')) ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                        {{ __('Bütün Elanlar') }}
                    </button>
                    <button type="button" data-filter-type="have_room"
                            class="filter-type-btn flex-1 sm:flex-none px-4 py-2 text-xs sm:text-sm font-bold rounded-xl transition {{ request('listing_type') === 'have_room' ? 'bg-emerald-500 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                        <i class="fa-solid fa-door-open mr-1 text-[11px]"></i> {{ __('Otaq Verilir') }}
                    </button>
                    <button type="button" data-filter-type="need_room"
                            class="filter-type-btn flex-1 sm:flex-none px-4 py-2 text-xs sm:text-sm font-bold rounded-xl transition {{ request('listing_type') === 'need_room' ? 'bg-blue-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                        <i class="fa-solid fa-user-group mr-1 text-[11px]"></i> {{ __('Otaq Axtarır') }}
                    </button>
                </div>
                <input type="hidden" name="listing_type" id="listingTypeInput" value="{{ request('listing_type') }}">

                <!-- Gender Preference Selector -->
                <div class="flex items-center gap-1.5">
                    <span class="text-xs font-bold text-gray-400 hidden sm:inline mr-1">{{ __('Cinsiyyət:') }}</span>
                    <div class="flex items-center gap-1 p-1 bg-gray-100/80 rounded-2xl w-full sm:w-auto">
                        <button type="button" data-filter-gender=""
                                class="filter-gender-btn flex-1 sm:flex-none px-3 py-1.5 text-xs font-bold rounded-xl transition {{ empty(request('gender')) && empty(request('gender_preference')) ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                            {{ __('Hamı') }}
                        </button>
                        <button type="button" data-filter-gender="female"
                                class="filter-gender-btn flex-1 sm:flex-none px-3 py-1.5 text-xs font-bold rounded-xl transition {{ (request('gender') === 'female' || request('gender_preference') === 'female') ? 'bg-pink-500 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                            <i class="fa-solid fa-venus mr-1 text-[10px]"></i> {{ __('Xanımlar') }}
                        </button>
                        <button type="button" data-filter-gender="male"
                                class="filter-gender-btn flex-1 sm:flex-none px-3 py-1.5 text-xs font-bold rounded-xl transition {{ (request('gender') === 'male' || request('gender_preference') === 'male') ? 'bg-indigo-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                            <i class="fa-solid fa-mars mr-1 text-[10px]"></i> {{ __('Bəylər') }}
                        </button>
                    </div>
                    <input type="hidden" name="gender" id="genderInput" value="{{ request('gender', request('gender_preference')) }}">
                </div>
            </div>

            <!-- Middle Inputs Row: Search, City, District, Price -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Search Input -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Axtarış') }}</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('Metro, qəsəbə və ya açar söz...') }}"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl pl-9 pr-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <!-- City Select -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Şəhər') }}</label>
                    <select name="city_id" id="citySelect"
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

                <!-- Min Price -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Min. Qiymət (₼)') }}</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" min="0"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>

                <!-- Max Price -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Maks. Qiymət (₼)') }}</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="1000" min="0"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>

            </div>

            <!-- Bottom Checkboxes & Filter Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-3 border-t border-gray-100">
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-gray-700">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="bills_included" value="1" {{ request('bills_included') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                        <span>{{ __('Kommunal xərclər daxildir') }}</span>
                    </label>

                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="smoker_allowed" value="1" {{ request('smoker_allowed') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                        <span>{{ __('Siqaret çəkməyə icazə var') }}</span>
                    </label>

                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="pet_allowed" value="1" {{ request('pet_allowed') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                        <span>{{ __('Ev heyvanına icazə var') }}</span>
                    </label>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('roommates.index') }}"
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
    <div id="roommateListingsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @include('pages.roommates.partials.cards', ['listings' => $listings])
    </div>

    <!-- Pagination Container -->
    <div id="roommatePaginationContainer">
        @include('pages.roommates.partials.pagination', ['listings' => $listings])
    </div>

</div>

@push('scripts')
    <script src="/js/pages/roommates/index.js"></script>
@endpush
@endsection
