@extends('layouts.app')

@section('title', $listing->title . ' - KibrisKare.com')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6">

    @if(isset($breadcrumbs))
        <div class="mb-5">
            @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- LEFT / MAIN CONTENT (2 COLUMNS) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Title & Top Badges Card -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
                
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    @if($listing->listing_type->value === 'have_room')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500 text-white shadow-xs">
                            <i class="fa-solid fa-door-open"></i>
                            <span>{{ __('roommates.have_room') }}</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-xs">
                            <i class="fa-solid fa-user-group"></i>
                            <span>{{ __('roommates.need_room') }}</span>
                        </span>
                    @endif

                    @if($listing->gender_preference->value === 'female')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-pink-500 text-white shadow-xs">
                            <i class="fa-solid fa-venus"></i>
                            <span>{{ __('roommates.gender_female_label') }}</span>
                        </span>
                    @elseif($listing->gender_preference->value === 'male')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-600 text-white shadow-xs">
                            <i class="fa-solid fa-mars"></i>
                            <span>{{ __('roommates.gender_male_label') }}</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-800 text-white shadow-xs">
                            <i class="fa-solid fa-users"></i>
                            <span>{{ __('roommates.gender_any_badge') }}</span>
                        </span>
                    @endif

                    <span class="text-xs text-gray-400 ml-auto flex items-center gap-1">
                        <i class="bi bi-eye"></i> {{ $listing->views_count }} {{ __('roommates.views') }}
                    </span>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-xs text-gray-400">
                        {{ $listing->created_at ? $listing->created_at->format('d.m.Y') : '' }}
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 tracking-tight mb-3 leading-snug">
                    {{ $listing->title }}
                </h1>

                <div class="flex items-center text-xs sm:text-sm text-gray-600 gap-2">
                    <i class="fa-solid fa-location-dot text-orange-500"></i>
                    <span class="font-medium text-gray-800">
                        @php
                            $cityName = is_array($listing->city?->name) ? ($listing->city->name[app()->getLocale()] ?? $listing->city->name['az'] ?? reset($listing->city->name)) : ($listing->city?->name ?? 'Bakı');
                            $districtName = $listing->district ? (is_array($listing->district->name) ? ($listing->district->name[app()->getLocale()] ?? $listing->district->name['az'] ?? reset($listing->district->name)) : $listing->district->name) : null;
                        @endphp
                        {{ $cityName }} @if($districtName) , {{ $districtName }} @endif
                    </span>
                    @if($listing->location_note)
                        <span class="text-gray-300">•</span>
                        <span class="text-gray-500">{{ $listing->location_note }}</span>
                    @endif
                </div>

            </div>

            <!-- Image Gallery / Slider -->
            @php
                $images = $listing->images;
            @endphp
            @if($images->isNotEmpty())
                <div class="bg-white border border-gray-200/90 rounded-3xl p-4 sm:p-6 shadow-xs space-y-3">
                    <div class="relative aspect-[16/10] w-full rounded-2xl overflow-hidden bg-gray-100 shadow-xs">
                        <img id="mainRoommateImage" src="{{ $images->first()->url }}" alt="{{ $listing->title }}"
                             class="w-full h-full object-cover transition duration-300" />
                    </div>

                    @if($images->count() > 1)
                        <div class="flex items-center gap-2 overflow-x-auto pb-1">
                            @foreach($images as $index => $img)
                                <button type="button" onclick="document.getElementById('mainRoommateImage').src = '{{ $img->url }}'"
                                        class="shrink-0 w-20 h-14 rounded-xl overflow-hidden border-2 border-transparent hover:border-orange-500 focus:border-orange-500 transition cursor-pointer">
                                    <img src="{{ $img->url }}" loading="lazy" decoding="async" class="w-full h-full object-cover" />
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Key Parameters Grid -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">{{ __('roommates.key_parameters') }}</h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('roommates.monthly_payment') }}</span>
                        <div class="text-lg font-bold text-orange-500 mt-1">
                            {{ $listing->formatted_price }}
                        </div>
                        <span class="text-[11px] font-semibold {{ $listing->bills_included ? 'text-emerald-600' : 'text-gray-400' }} mt-0.5">
                            {{ $listing->bills_included ? __('roommates.bills_included') : __('roommates.bills_excluded') }}
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('roommates.gender_preference') }}</span>
                        <div class="text-base font-semibold text-gray-900 mt-1">
                            {{ $listing->gender_preference->label() }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5">{{ __('roommates.gender_preference') }}</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('roommates.occupation') }}</span>
                        <div class="text-base font-semibold text-gray-900 mt-1">
                            {{ $listing->occupation_preference?->label() ?? __('roommates.occupation_any') }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5">{{ __('roommates.occupation') }}</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('roommates.stay_duration') }}</span>
                        <div class="text-base font-semibold text-gray-900 mt-1">
                            {{ $listing->stay_duration ?: __('roommates.occupation_any') }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5">{{ __('roommates.stay_duration') }}</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('roommates.move_in_date') }}</span>
                        <div class="text-base font-semibold text-gray-900 mt-1">
                            {{ $listing->available_from ? $listing->available_from->format('d.m.Y') : __('roommates.immediately') }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5">{{ __('roommates.move_in_date') }}</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('roommates.total_roommates') }}</span>
                        <div class="text-base font-semibold text-gray-900 mt-1">
                            {{ $listing->total_roommates ? __('roommates.persons_count', ['count' => $listing->total_roommates]) : '—' }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5">{{ __('roommates.total_roommates') }}</span>
                    </div>

                </div>
            </div>

            <!-- Rules & Amenities -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                
                <!-- House Rules -->
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">{{ __('roommates.house_rules') }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-xl {{ $listing->smoker_allowed ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }} flex items-center justify-center text-sm">
                                <i class="fa-solid fa-smoking"></i>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-900">
                                    {{ $listing->smoker_allowed ? __('roommates.smoker_allowed') : __('roommates.smoker_forbidden') }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-xl {{ $listing->pet_allowed ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }} flex items-center justify-center text-sm">
                                <i class="fa-solid fa-paw"></i>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-900">
                                    {{ $listing->pet_allowed ? __('roommates.pet_allowed') : __('roommates.pet_forbidden') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amenities Checklist -->
                @if(!empty($listing->amenities))
                    <div class="pt-4 border-t border-gray-100">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">{{ __('roommates.amenities_in_flat') }}</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            @foreach((array)$listing->amenities as $amenity)
                                <div class="flex items-center gap-2 text-xs font-semibold text-gray-800 p-2.5 rounded-xl bg-orange-50/40 border border-orange-100">
                                    <i class="bi bi-check-circle-fill text-orange-500 text-sm"></i>
                                    <span>{{ $amenity }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Description -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">{{ __('roommates.detailed_description') }}</h2>
                <div class="text-xs sm:text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $listing->description }}
                </div>
            </div>

        </div>

        <!-- RIGHT SIDEBAR (1 COLUMN) -->
        <div class="space-y-6">

            <!-- Sticky Contact Card -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-sm lg:sticky lg:top-24 space-y-6">
                
                <!-- Price Box -->
                <div class="pb-5 border-b border-gray-100">
                    <span class="text-xs text-gray-500 font-medium">{{ __('roommates.monthly_payment') }}</span>
                    <div class="text-3xl font-bold text-orange-500 mt-0.5">
                        {{ $listing->formatted_price }}
                    </div>
                    @if($listing->bills_included)
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 mt-1">
                            <i class="bi bi-check2-circle"></i> {{ __('roommates.bills_included') }}
                        </span>
                    @endif
                </div>

                <x-contact-profile :name="$listing->contact_name" :role="__('roommates.listing_owner')" />

                <x-contact-actions :whatsapp="$listing->contact_whatsapp" :phone="$listing->contact_phone"
                    :message="'Salam, KibrisKare.com saytındakı otaq yoldaşı elanınızla bağlı yazıram: ' . $listing->title"
                    :whatsapp-label="__('roommates.write_whatsapp')" />

                <x-safety-note />

            </div>

        </div>

    </div>

    <!-- SIMILAR LISTINGS -->
    @if($similarListings->isNotEmpty())
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                    {{ __('roommates.similar_listings') }}
                </h2>
                <a href="{{ route('roommates.index') }}" class="text-xs sm:text-sm font-semibold text-orange-600 hover:underline">
                    {{ __('roommates.view_all') }} <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @include('pages.roommates.partials.cards', ['listings' => $similarListings])
            </div>
        </div>
    @endif

</div>
@endsection
