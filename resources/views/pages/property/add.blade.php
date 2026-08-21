@extends('layouts.app')

@php
    $pageData = [
        'cities' => $cities,
        'features' => $features,
        'nearbyObjects' => $nearbyObjects,
        'subways' => $subways,
        'propertyTypes' => $propertyTypes,
        'repairTypes' => $repairTypes,
        'currencies' => $currencies,
        'roomCounts' => $roomCounts,
    ];
@endphp

@section('content')
@include('components.breadcrumb', ['items' => [
    ['label' => __('Home'), 'url' => '/'],
    ['label' => __('Add Property')],
]])
@include('components.scroll-top')

<section id="add-property" class="container px-4 mx-auto py-8">
    {{-- Validation errors --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ url('/add-property') }}" enctype="multipart/form-data">
        @csrf

        {{-- Property Type --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Property Type') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Building type --}}
                <div class="custom-select-container relative" id="building-type-container">
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">{{ __('Building Type') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden max-h-60 overflow-y-auto"></ul>
                    <input type="hidden" name="building_type" id="building-type-input">
                </div>

                {{-- Add type --}}
                <div class="custom-select-container relative" id="add-type-container">
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">{{ __('Add Type') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden">
                        <li data-value="sale" class="px-4 py-2 hover:bg-orange-100 cursor-pointer">{{ __('For Sale') }}</li>
                        <li data-value="rent" class="px-4 py-2 hover:bg-orange-100 cursor-pointer">{{ __('For Rent') }}</li>
                    </ul>
                    <input type="hidden" name="add_type" id="add-type-input">
                </div>

                {{-- Rent period (shown when add_type = rent) --}}
                <div class="custom-select-container relative hidden" id="rent-type-container">
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">{{ __('Rent Period') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden">
                        <li data-value="daily" class="px-4 py-2 hover:bg-orange-100 cursor-pointer">{{ __('Daily') }}</li>
                        <li data-value="monthly" class="px-4 py-2 hover:bg-orange-100 cursor-pointer">{{ __('Monthly') }}</li>
                    </ul>
                    <input type="hidden" name="rent_type" id="rent-type-input">
                </div>

                {{-- Property condition --}}
                <div class="custom-select-container relative" id="repair-type-container">
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">{{ __('Condition') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden"></ul>
                    <input type="hidden" name="property_condition" id="repair-type-input">
                </div>
            </div>
        </div>

        {{-- Property Details --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Property Details') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Room count --}}
                <div class="custom-select-container relative" id="room-count-container">
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">{{ __('Room Count') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden max-h-60 overflow-y-auto"></ul>
                    <input type="hidden" name="number_of_rooms" id="room-count-input">
                </div>

                {{-- Number of floors (building total floors) --}}
                <div class="custom-select-container relative" id="floor-container">
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">{{ __('Total Floors') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden max-h-60 overflow-y-auto">
                        @for($i = 1; $i <= 50; $i++)
                        <li data-value="{{ $i }}" class="px-4 py-2 hover:bg-orange-100 cursor-pointer">{{ $i }}</li>
                        @endfor
                    </ul>
                    <input type="hidden" name="number_of_floors" id="floor-input">
                </div>

                {{-- Floor located --}}
                <div>
                    <input type="number" name="floor_located" id="floor-located" placeholder="{{ __('Floor Located') }}" min="1" max="100"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>

                {{-- Area (m²) --}}
                <div id="area-wrapper">
                    <input type="number" name="area" id="area" placeholder="{{ __('Area') }} (m²)" min="1"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>

                {{-- Field area (m²) - shown for LAND type --}}
                <div id="field-area-wrapper" class="hidden">
                    <input type="number" name="field_area" id="field-area" placeholder="{{ __('Field Area') }} (m²)" min="1"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>
            </div>

            {{-- Price row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                <div>
                    <input type="number" name="price" id="price" placeholder="{{ __('Price') }}" min="0"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>
                <div class="custom-select-container relative" id="currency-container">
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">AZN</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden"></ul>
                    <input type="hidden" name="currency" id="currency-input" value="AZN">
                </div>
            </div>

            {{-- Checkboxes --}}
            <div class="flex flex-wrap gap-6 mt-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="in_credit" id="in-credit" value="1" class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                    <span>{{ __('Credit Available') }}</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="has_deed" id="has-deed" value="1" class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                    <span>{{ __('Has Deed') }}</span>
                </label>
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Location') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div class="custom-select-container relative" id="city-container">
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">{{ __('City') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden max-h-60 overflow-y-auto"></ul>
                    <input type="hidden" name="city_id" id="city-input">
                </div>

                <div class="custom-select-container relative hidden" id="district-container">
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">{{ __('District') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden max-h-60 overflow-y-auto"></ul>
                    <input type="hidden" name="district_id" id="district-input">
                </div>

                <div class="custom-select-container relative hidden" id="town-container">
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">{{ __('Town') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden max-h-60 overflow-y-auto"></ul>
                    <input type="hidden" name="town_id" id="town-input">
                </div>
            </div>

            {{-- Address + Map --}}
            <div class="flex flex-col sm:flex-row gap-2 mb-4">
                <input type="text" name="address" id="address" placeholder="{{ __('Full address') }}"
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
                <button type="button" id="searchAddress" class="bg-[var(--primary)] text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition whitespace-nowrap">
                    {{ __('Search on Map') }}
                </button>
            </div>

            <div id="map" style="height: 400px; width: 100%; border-radius: 0.5rem; z-index: 1;"></div>
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <div class="mt-4">
                <label for="google_map_location" class="block font-semibold mb-1">{{ __('Google Maps Link') }} ({{ __('optional') }}):</label>
                <input type="text" name="google_map_location" id="google_map_location" placeholder="https://maps.google.com/..."
                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Description') }}</h2>
            <textarea name="description" id="description" rows="5" placeholder="{{ __('Property description') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300 mb-4"></textarea>
            <textarea name="note_to_admin" id="note-to-admin" rows="3" placeholder="{{ __('Note to admin') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300"></textarea>
        </div>

        {{-- Photos --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Photos') }}</h2>
            <div id="dropzone" class="border-4 border-dashed border-gray-300 rounded-lg py-10 text-center cursor-pointer hover:border-orange-300 transition">
                <label class="bg-[var(--primary)] text-white px-6 py-3 rounded-lg cursor-pointer hover:bg-orange-600 transition inline-block">
                    <i class="bi bi-paperclip"></i> {{ __('Choose Files') }}
                    <input type="file" id="fileInput" name="media[]" multiple accept="image/*" class="hidden">
                </label>
                <p class="text-sm text-gray-500 mt-2">{{ __('Max 10 photos') }}</p>
            </div>
            <div id="gallery" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
        </div>

        {{-- Features --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Features') }}</h2>
            <input type="text" id="featureSearch" placeholder="{{ __('Search') }}..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-orange-300">
            <div id="features-container" class="relative overflow-hidden max-h-48">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2" id="features"></div>
                <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white to-transparent pointer-events-none fade-overlay"></div>
            </div>
            <button type="button" id="toggle-features" class="mt-2 text-[var(--primary)] border border-[var(--primary)] px-3 rounded-md py-1 hover:bg-orange-50 transition">
                {{ __('Show more') }} <i class="bi bi-chevron-down"></i>
            </button>
        </div>

        {{-- Nearby Objects --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Nearby Objects') }}</h2>
            <input type="text" id="nearbySearch" placeholder="{{ __('Search') }}..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-orange-300">
            <div id="nearby-objects-container" class="relative overflow-hidden max-h-48">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2" id="nearby-objects"></div>
                <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white to-transparent pointer-events-none fade-overlay"></div>
            </div>
            <button type="button" id="toggle-nearby-objects" class="mt-2 text-[var(--primary)] border border-[var(--primary)] px-3 rounded-md py-1 hover:bg-orange-50 transition">
                {{ __('Show more') }} <i class="bi bi-chevron-down"></i>
            </button>
        </div>

        {{-- Contact Information --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Contact Information') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="custom-select-container relative" id="advertiser-container">
                    <span class="block font-semibold mb-1">{{ __('Advertiser') }}:*</span>
                    <button type="button" class="custom-select-button w-full border border-gray-300 rounded-lg p-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <span class="custom-select-text">{{ __('Choose one') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <ul class="custom-select-options absolute z-10 bg-white border border-gray-300 rounded-lg mt-1 w-full hidden">
                        <li data-value="owner" class="px-4 py-2 hover:bg-orange-100 cursor-pointer">{{ __('Owner') }}</li>
                        <li data-value="agent" class="px-4 py-2 hover:bg-orange-100 cursor-pointer">{{ __('Agent') }}</li>
                    </ul>
                    <input type="hidden" name="advertiser" id="advertiser-input">
                </div>
                <div>
                    <label for="advertiser-name" class="block font-semibold mb-1">{{ __('Advertiser Name') }}:*</label>
                    <input type="text" name="advertiser_name" id="advertiser-name" placeholder="{{ __('Name') }}"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                @for($i = 1; $i <= 4; $i++)
                <div>
                    <label for="phone_{{ $i }}" class="block font-semibold mb-1">{{ __('Phone') }} {{ $i }}@if($i === 1)<span class="text-red-500">*</span>@endif</label>
                    <input type="text" name="phone_{{ $i }}" id="phone_{{ $i }}" placeholder="{{ __('Enter phone number') }}"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>
                @endfor
            </div>

            <div class="mb-4">
                <label for="email" class="block font-semibold mb-1">{{ __('Email') }}:<span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" placeholder="{{ __('Email') }}"
                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
        </div>

        {{-- Terms + Submit --}}
        <div class="flex items-center gap-2 mb-6">
            <input type="checkbox" id="terms" class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
            <label for="terms" class="select-none text-sm">
                {{ __('I have to accept') }}
                <a href="/istifadeci-razilasi" target="_blank" class="text-[var(--primary)] underline hover:text-orange-500 transition">
                    {{ __('Privacy and policy') }}.
                </a>
                <span class="text-red-500">*</span>
            </label>
        </div>

        <div class="flex justify-center">
            <button type="submit" id="add-property-btn" disabled
                class="bg-[var(--primary)] text-white px-8 py-4 rounded-xl font-semibold hover:bg-orange-600 transition opacity-50 cursor-not-allowed">
                {{ __('Add Property') }}
            </button>
        </div>
    </form>

    {{-- Unsaved data modal --}}
    <div id="unsavedDataModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold mb-4">{{ __('Unsaved Data') }}</h3>
            <p class="mb-6">{{ __('You have unsaved data from a previous session. Would you like to restore it?') }}</p>
            <div class="flex justify-end gap-4">
                <button type="button" id="modalNo" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">{{ __('No') }}</button>
                <button type="button" id="modalYes" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg hover:bg-orange-600">{{ __('Yes') }}</button>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>window.addPropertyData = @json($pageData);</script>
<script src="/js/pages/property/add.js"></script>
@endpush
