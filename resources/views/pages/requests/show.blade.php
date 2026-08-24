@extends('layouts.app')

@section('title', $propertyRequest->title . ' - Metraj.az')

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
                    
                    <!-- Request Type Badge -->
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $propertyRequest->request_type->badgeClass() }} shadow-xs">
                        @if($propertyRequest->request_type->value === 'buy')
                            <i class="fa-solid fa-cart-shopping text-[10px]"></i>
                        @elseif($propertyRequest->request_type->value === 'rent_monthly')
                            <i class="fa-solid fa-key text-[10px]"></i>
                        @elseif($propertyRequest->request_type->value === 'rent_daily')
                            <i class="fa-solid fa-calendar-day text-[10px]"></i>
                        @else
                            <i class="fa-solid fa-people-roof text-[10px]"></i>
                        @endif
                        <span>{{ $propertyRequest->request_type->badgeLabel() }}</span>
                    </span>

                    @if($propertyRequest->property_type)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-800">
                            {{ $propertyRequest->property_type }}
                        </span>
                    @endif

                    <span class="text-xs text-gray-400 ml-auto flex items-center gap-1">
                        <i class="bi bi-eye"></i> {{ $propertyRequest->views_count }} {{ __('baxış') }}
                    </span>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-xs text-gray-400">
                        {{ $propertyRequest->created_at ? $propertyRequest->created_at->format('d.m.Y') : '' }}
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight mb-3 leading-snug">
                    {{ $propertyRequest->title }}
                </h1>

                <div class="flex items-center text-xs sm:text-sm text-gray-600 gap-2">
                    <i class="fa-solid fa-location-dot text-orange-500"></i>
                    <span class="font-medium text-gray-800">
                        @php
                            $cityName = is_array($propertyRequest->city?->name) ? ($propertyRequest->city->name[app()->getLocale()] ?? $propertyRequest->city->name['az'] ?? reset($propertyRequest->city->name)) : ($propertyRequest->city?->name ?? 'Bakı');
                            $districtName = $propertyRequest->district ? (is_array($propertyRequest->district->name) ? ($propertyRequest->district->name[app()->getLocale()] ?? $propertyRequest->district->name['az'] ?? reset($propertyRequest->district->name)) : $propertyRequest->district->name) : null;
                        @endphp
                        {{ $cityName }} @if($districtName) , {{ $districtName }} @endif
                    </span>
                    @if($propertyRequest->location_note)
                        <span class="text-gray-300">•</span>
                        <span class="text-gray-500">{{ $propertyRequest->location_note }}</span>
                    @endif
                </div>

            </div>

            <!-- Image Gallery (If images exist) -->
            @php
                $requestImages = $propertyRequest->images;
            @endphp
            @if($requestImages->isNotEmpty())
                <div class="bg-white border border-gray-200/90 rounded-3xl p-4 sm:p-6 shadow-xs space-y-3">
                    <div class="relative aspect-[16/10] w-full rounded-2xl overflow-hidden bg-gray-100 shadow-xs">
                        <img id="mainRequestImage" src="{{ $requestImages->first()->url }}" alt="{{ $propertyRequest->title }}"
                             class="w-full h-full object-cover transition duration-300" />
                    </div>

                    @if($requestImages->count() > 1)
                        <div class="flex items-center gap-2 overflow-x-auto pb-1">
                            @foreach($requestImages as $index => $img)
                                <button type="button" onclick="document.getElementById('mainRequestImage').src = '{{ $img->url }}'"
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
                <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-4">{{ __('Tələbin Təfərrüatları') }}</h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    
                    <!-- Budget Box -->
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                        <span class="text-xs text-gray-500 font-medium">{{ __('Büdcə') }}</span>
                        <div class="text-lg font-extrabold text-[#f1913d] mt-1">
                            {{ $propertyRequest->formatted_budget }}
                        </div>
                        <span class="text-[11px] font-semibold {{ $propertyRequest->bills_included ? 'text-emerald-600' : 'text-gray-400' }} mt-0.5">
                            {{ $propertyRequest->bills_included ? __('Kommunal daxildir') : '' }}
                        </span>
                    </div>

                    @if($propertyRequest->property_type)
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs text-gray-500 font-medium">{{ __('Əmlak Növü') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">
                                {{ $propertyRequest->property_type }}
                            </div>
                            <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Axtarılan kateqoriya') }}</span>
                        </div>
                    @endif

                    @if($propertyRequest->rooms)
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs text-gray-500 font-medium">{{ __('Otaq Sayı') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">
                                {{ $propertyRequest->rooms }} {{ __('otaqlı') }}
                            </div>
                            <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Planlaşdırma') }}</span>
                        </div>
                    @endif

                    @if($propertyRequest->has_deed !== null)
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs text-gray-500 font-medium">{{ __('Sənəd Tələbi') }}</span>
                            <div class="text-base font-bold {{ $propertyRequest->has_deed ? 'text-emerald-600' : 'text-gray-800' }} mt-1">
                                {{ $propertyRequest->has_deed ? __('Kupçalı (Çıxarış)') : __('Fərqi yoxdur') }}
                            </div>
                            <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Mülkiyyət sənədi') }}</span>
                        </div>
                    @endif

                    @if($propertyRequest->mortgage_eligible !== null)
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs text-gray-500 font-medium">{{ __('İpoteka') }}</span>
                            <div class="text-base font-bold {{ $propertyRequest->mortgage_eligible ? 'text-blue-600' : 'text-gray-800' }} mt-1">
                                {{ $propertyRequest->mortgage_eligible ? __('İpotekaya yararlı') : __('Fərqi yoxdur') }}
                            </div>
                            <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Bank ipotekası') }}</span>
                        </div>
                    @endif

                    @if($propertyRequest->occupancy_type)
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs text-gray-500 font-medium">{{ __('Kimlər qalacaq') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">
                                {{ $propertyRequest->occupancy_type }}
                            </div>
                            <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Sakin növü') }}</span>
                        </div>
                    @endif

                    @if($propertyRequest->gender_preference)
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between">
                            <span class="text-xs text-gray-500 font-medium">{{ __('Cinsiyyət Tələbi') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">
                                {{ $propertyRequest->gender_preference === 'female' ? __('Yalnız Xanım') : ($propertyRequest->gender_preference === 'male' ? __('Yalnız Bəy') : __('Fərqi yoxdur')) }}
                            </div>
                            <span class="text-[11px] text-gray-400 mt-0.5">{{ __('Otaq yoldaşı üçün') }}</span>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Description -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
                <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-3">{{ __('Ətraflı Təsvir və Şərtlər') }}</h2>
                <div class="text-xs sm:text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $propertyRequest->description }}
                </div>
            </div>

        </div>

        <!-- RIGHT SIDEBAR (1 COLUMN) -->
        <div class="space-y-6">

            <!-- Sticky Contact Card -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-sm lg:sticky lg:top-24 space-y-6">
                
                <!-- Budget Header -->
                <div class="pb-5 border-b border-gray-100">
                    <span class="text-xs text-gray-500 font-medium">{{ __('Axtarılan Büdcə') }}</span>
                    <div class="text-2xl sm:text-3xl font-extrabold text-[#f1913d] mt-0.5">
                        {{ $propertyRequest->formatted_budget }}
                    </div>
                </div>

                <!-- Contact Profile -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-lg">
                        {{ mb_strtoupper(mb_substr($propertyRequest->contact_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-sm sm:text-base text-gray-900">{{ $propertyRequest->contact_name }}</div>
                        <div class="text-xs text-gray-400">{{ __('Müştəri / Axtaran şəxs') }}</div>
                    </div>
                </div>

                <!-- Contact Action Buttons -->
                <div class="space-y-3 pt-2">
                    
                    @if($propertyRequest->contact_whatsapp)
                        @php
                            $wa = preg_replace('/[^0-9]/', '', $propertyRequest->contact_whatsapp);
                        @endphp
                        <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Salam, Metraj.az saytında yerləşdirdiyiniz tələb elanınızla bağlı sizə uyğun təklifim var: ' . $propertyRequest->title) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-2xl shadow-xs transition hover:shadow-md">
                            <i class="bi bi-whatsapp text-lg"></i>
                            <span>{{ __('WhatsApp ilə Təklif Göndər') }}</span>
                        </a>
                    @endif

                    <a href="tel:{{ $propertyRequest->contact_phone }}"
                       class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-gray-900 hover:bg-[#f1913d] text-white font-bold text-sm rounded-2xl shadow-xs transition hover:shadow-md">
                        <i class="bi bi-telephone-fill text-base"></i>
                        <span>{{ $propertyRequest->contact_phone }}</span>
                    </a>

                </div>

                <!-- Safety Note -->
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 text-[11px] text-gray-500 leading-relaxed space-y-1">
                    <div class="font-bold text-gray-700 flex items-center gap-1.5">
                        <i class="bi bi-info-circle text-orange-500"></i>
                        <span>{{ __('Agentlər və Ev Sahibləri üçün') }}</span>
                    </div>
                    <p>{{ __('Əgər bu müştərinin tələblərinə uyğun əmlakınız varsa, birbaşa zəng edərək və ya WhatsApp ilə əlaqə saxlayaraq təklifinizi təqdim edə bilərsiniz.') }}</p>
                </div>

            </div>

        </div>

    </div>

    <!-- SIMILAR REQUESTS -->
    @if($similarRequests->isNotEmpty())
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Oxşar Tələb Elanları') }}
                </h2>
                <a href="{{ route('requests.index') }}" class="text-xs sm:text-sm font-bold text-orange-600 hover:underline">
                    {{ __('Hamısına Bax') }} <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @include('pages.requests.partials.cards', ['requests' => $similarRequests])
            </div>
        </div>
    @endif

</div>
@endsection
