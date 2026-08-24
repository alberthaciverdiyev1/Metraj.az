@extends('layouts.app')

@section('title', $listing->title . ' - Metraj.az')

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
                            <span>{{ __('Otaq verilir') }}</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-xs">
                            <i class="fa-solid fa-user-group"></i>
                            <span>{{ __('Otaq axtarır') }}</span>
                        </span>
                    @endif

                    @if($listing->gender_preference->value === 'female')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-pink-500 text-white shadow-xs">
                            <i class="fa-solid fa-venus"></i>
                            <span>{{ __('Yalnız Xanım') }}</span>
                        </span>
                    @elseif($listing->gender_preference->value === 'male')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-600 text-white shadow-xs">
                            <i class="fa-solid fa-mars"></i>
                            <span>{{ __('Yalnız Bəy') }}</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-800 text-white shadow-xs">
                            <i class="fa-solid fa-users"></i>
                            <span>{{ __('Hamı üçün') }}</span>
                        </span>
                    @endif

                    <span class="text-xs text-gray-400 ml-auto flex items-center gap-1">
                        <i class="bi bi-eye"></i> {{ $listing->views_count }} baxış
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
                                    <img src="{{ $img->url }}" class="w-full h-full object-cover" />
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Key Parameters Grid -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">{{ __('Əsas Parametrlər') }}</h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('Aylıq Ödəniş') }}</span>
                        <div class="text-lg font-bold text-[#f1913d] mt-1">
                            {{ $listing->formatted_price }}
                        </div>
                        <span class="text-[11px] font-semibold {{ $listing->bills_included ? 'text-emerald-600' : 'text-gray-400' }} mt-0.5">
                            {{ $listing->bills_included ? __('Kommunal daxildir') : __('Kommunal xaricdir') }}
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('Cinsiyyət Tələbi') }}</span>
                        <div class="text-base font-semibold text-gray-900 mt-1">
                            {{ $listing->gender_preference->label() }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Otaq yoldaşı üçün') }}</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('Məşğuliyyət') }}</span>
                        <div class="text-base font-semibold text-gray-900 mt-1">
                            {{ $listing->occupation_preference?->label() ?? 'Fərqi yoxdur' }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Tələbə və ya işləyən') }}</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('Qalma Müddəti') }}</span>
                        <div class="text-base font-semibold text-gray-900 mt-1">
                            {{ $listing->stay_duration ?: __('Fərqi yoxdur') }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Müqavilə müddəti') }}</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('Köçmə Tarixi') }}</span>
                        <div class="text-base font-semibold text-gray-900 mt-1">
                            {{ $listing->available_from ? $listing->available_from->format('d.m.Y') : __('Dərhal') }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Mənzil hazırdır') }}</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('Evdə Adam Sayı') }}</span>
                        <div class="text-base font-semibold text-gray-900 mt-1">
                            {{ $listing->total_roommates ? $listing->total_roommates . ' nəfər' : '—' }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Ümumi sakin') }}</span>
                    </div>

                </div>
            </div>

            <!-- Rules & Amenities -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                
                <!-- House Rules -->
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">{{ __('Ev Qaydaları') }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-xl {{ $listing->smoker_allowed ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }} flex items-center justify-center text-sm">
                                <i class="fa-solid fa-smoking"></i>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-900">
                                    {{ $listing->smoker_allowed ? __('Siqaret çəkməyə icazə var') : __('Siqaret çəkmək qadağandır') }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-xl {{ $listing->pet_allowed ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }} flex items-center justify-center text-sm">
                                <i class="fa-solid fa-paw"></i>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-900">
                                    {{ $listing->pet_allowed ? __('Ev heyvanına icazə var') : __('Ev heyvanı saxlamaq olmaz') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amenities Checklist -->
                @if(!empty($listing->amenities))
                    <div class="pt-4 border-t border-gray-100">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">{{ __('Mənzildə Olan Şərait') }}</h2>
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
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">{{ __('Ətraflı Təsvir') }}</h2>
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
                    <span class="text-xs text-gray-500 font-medium">{{ __('Aylıq Ödəniş') }}</span>
                    <div class="text-3xl font-bold text-[#f1913d] mt-0.5">
                        {{ $listing->formatted_price }}
                    </div>
                    @if($listing->bills_included)
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 mt-1">
                            <i class="bi bi-check2-circle"></i> {{ __('Bütün kommunal xərclər daxildir') }}
                        </span>
                    @endif
                </div>

                <!-- Contact Profile -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center font-semibold text-lg">
                        {{ mb_strtoupper(mb_substr($listing->contact_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm sm:text-base text-gray-900">{{ $listing->contact_name }}</div>
                        <div class="text-xs text-gray-400">{{ __('Elan sahibi') }}</div>
                    </div>
                </div>

                <!-- Contact Action Buttons -->
                <div class="space-y-3 pt-2">
                    
                    @if($listing->contact_whatsapp)
                        @php
                            $wa = preg_replace('/[^0-9]/', '', $listing->contact_whatsapp);
                        @endphp
                        <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Salam, Metraj.az saytındakı otaq yoldaşı elanınızla bağlı yazıram: ' . $listing->title) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm rounded-2xl shadow-xs transition hover:shadow-md">
                            <i class="bi bi-whatsapp text-lg"></i>
                            <span>{{ __('WhatsApp ilə Yaz') }}</span>
                        </a>
                    @endif

                    <a href="tel:{{ $listing->contact_phone }}"
                       class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-gray-900 hover:bg-[#f1913d] text-white font-semibold text-sm rounded-2xl shadow-xs transition hover:shadow-md">
                        <i class="bi bi-telephone-fill text-base"></i>
                        <span>{{ $listing->contact_phone }}</span>
                    </a>

                </div>

                <!-- Safety Note -->
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 text-[11px] text-gray-500 leading-relaxed space-y-1">
                    <div class="font-semibold text-gray-700 flex items-center gap-1.5">
                        <i class="bi bi-shield-check text-emerald-600"></i>
                        <span>{{ __('Təhlükəsizlik Tövsiyəsi') }}</span>
                    </div>
                    <p>{{ __('Mənzillə əyani tanış olmadan və şərtləri razılaşdırmadan heç kimə əvvəlcədən beh / ödəniş göndərməyin.') }}</p>
                </div>

            </div>

        </div>

    </div>

    <!-- SIMILAR LISTINGS -->
    @if($similarListings->isNotEmpty())
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                    {{ __('Oxşar Otaq Yoldaşı Elanları') }}
                </h2>
                <a href="{{ route('roommates.index') }}" class="text-xs sm:text-sm font-semibold text-orange-600 hover:underline">
                    {{ __('Hamısına Bax') }} <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @include('pages.roommates.partials.cards', ['listings' => $similarListings])
            </div>
        </div>
    @endif

</div>
@endsection
