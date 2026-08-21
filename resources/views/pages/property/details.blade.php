@extends('layouts.app')

@section('content')
@php
    $dealTypeOpt = $property->filterOptions->firstWhere('filter_id', 2);
    $isRent = $dealTypeOpt ? (str_contains($dealTypeOpt->value, 'rent')) : false;

    $agentName = $property->agent->user->name ?? ($property->user->name ?? 'Metraj Təmsilçisi');
    $agentPhone = $property->agent->phone ?? ($property->user->phone ?? '');
    $agentAvatar = $property->agent->avatar_url ?? ($property->agent->user->avatar ?? 'https://themesflat.co/html/proty/images/avatar/seller.jpg');
@endphp

<div class="max-w-[1400px] mx-auto pt-6 px-4 sm:px-6 lg:px-8">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
</div>

@include('components.scroll-top')

<style>
    .thumbnails-row::-webkit-scrollbar {
        display: none;
    }
    .thumbnails-row {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .thumbnails-row img {
        opacity: 0.6;
        transition: all 0.2s ease-in-out;
    }
    .thumbnails-row img.active {
        opacity: 1;
        border-color: #f97316;
    }
</style>

<!-- Gallery Section -->
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 mt-6">
@php
    $galleryImages = $property->images->sortBy('sort_order')->values();
    $totalImages = count($galleryImages);
@endphp

    <!-- Main Image -->
    <div class="relative w-full h-[50vh] md:h-[60vh] lg:h-[68vh] min-h-[350px] rounded-2xl overflow-hidden cursor-pointer shadow-sm" onclick="openModal(0)">
        @if($totalImages > 0)
            <img src="{{ $galleryImages->first()?->url }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
        @else
            <img src="https://static.vecteezy.com/system/resources/previews/004/640/986/non_2x/tower-building-illustration-isolated-on-white-background-vector.jpg" alt="Əsas Şəkil" class="w-full h-full object-cover">
        @endif
        <span class="absolute bottom-4 left-4 bg-black/60 text-white text-xs px-3.5 py-2 rounded-xl font-bold backdrop-blur-md flex items-center gap-1.5 shadow">
            <i class="bi bi-camera"></i>
            <span>{{ $totalImages }} {{ __('şəkil') }}</span>
        </span>
    </div>

    <!-- Thumbnail Gallery (horizontal, single row) -->
    @if($totalImages > 0)
    <div class="mt-4 flex gap-3 overflow-x-auto thumbnails-row">
        @foreach($galleryImages as $index => $image)
            <img src="{{ $image->url }}"
                 onclick="openModal({{ $index }})"
                 alt="{{ $property->title }}"
                 class="shrink-0 w-24 h-20 sm:w-28 sm:h-24 md:w-32 md:h-24 object-cover rounded-xl border-2 cursor-pointer {{ $index === 0 ? 'active border-orange-500' : 'border-transparent' }}">
        @endforeach
    </div>
    @endif
</div>

<!-- Modal Fullscreen Slider -->
<div id="modal" class="modal">
    <div class="modal-header">
        <span id="counter" class="text-sm font-semibold">1/{{ count($property->images ?? []) }}</span>
        <div class="modal-actions">
            <button onclick="toggleFullscreen()"><i class="bi bi-fullscreen"></i></button>
            <button onclick="closeModal()"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    <div class="modal-navigation">
        <button onclick="prevImage()"><i class="bi bi-arrow-left"></i></button>
        <img id="modal-image" src="" alt="Modal Image">
        <button onclick="nextImage()"><i class="bi bi-arrow-right"></i></button>
    </div>
    <div class="thumbnails mt-4 flex space-x-2 overflow-x-auto" id="thumbnails">
        @foreach($property->images ?? [] as $index => $image)
            <img src="{{ $image->url }}" onclick="openModal({{ $index }})" alt=""
                 class="w-20 h-20 object-cover rounded-xl border-2 border-transparent cursor-pointer hover:border-orange-500">
        @endforeach
    </div>
</div>

<!-- Main Details Layout -->
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 mt-8 pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Side: Property Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Title & Price Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-snug">{{ $property->title }}</h1>
                    <div class="text-2xl sm:text-3xl font-black text-orange-500 whitespace-nowrap">
                        {{ number_format($property->price, 0, '', ' ') }} AZN
                        @if($isRent)
                            <span class="text-sm font-medium text-gray-500">/ay</span>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-t border-gray-100 pt-4 gap-4">
                    <div class="flex items-center text-gray-500 text-sm">
                        <i class="bi bi-geo-alt-fill text-orange-500 mr-2 text-lg"></i>
                        <span class="font-medium text-gray-700">{{ $property->address }}</span>
                    </div>
                    <div class="flex items-center gap-4 text-gray-400 text-lg">
                        <button class="hover:text-red-500 transition duration-200"><i class="bi bi-heart"></i></button>
                        <button class="hover:text-orange-500 transition duration-200"><i class="bi bi-arrow-left-right"></i></button>
                        <button class="hover:text-gray-900 transition duration-200" id="printBtn"><i class="bi bi-printer"></i></button>
                        <button class="hover:text-blue-500 transition duration-200"><i class="bi bi-share"></i></button>
                    </div>
                </div>
            </div>

            <!-- Property Specs Component -->
            @include('components.property.specs', ['item' => $property])

            <!-- Features (Təchizatlar) Component -->
            @include('components.property.features', ['features' => $property->amenities ?? [], 'column' => 3])
            
            <!-- Nearby Objects (Yaxınlıqdakı Obyektlər) Component -->
            @include('components.property.nearby-objects', ['objects' => collect(), 'column' => 3])
            
            <!-- Map Component -->
            @include('components.property.map', ['location' => $property, 'zoom' => 15])

            <!-- Virtual Tour Component -->
            @include('components.property.virtual-tour', ['tour' => null])

            <!-- Loan Calculator Container -->
            <div class="loan-calculator-container w-full">
                @include('components.loan-calculator')
            </div>

            <!-- Similar Cards Section -->
            @include('components.similar-cards', ['currentProperty' => $property])

            <a href="/listing"
               class="w-full sm:w-[300px] mt-10 text-center px-6 py-3 border border-orange-500 text-orange-500 font-bold rounded-2xl shadow-sm bg-white hover:bg-orange-50 transition justify-center inline-flex items-center gap-2">
                <span>{{ __('Daha çox oxşar elan') }}</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- Right Side: Sidebar (Sticky) -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                <!-- Realtor Contact Card -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('Rieltor / Əlaqədar şəxs') }}</h3>
                    
                    <div class="flex items-center gap-4">
                        <img src="{{ $agentAvatar }}" alt="{{ $agentName }}" class="w-16 h-16 rounded-2xl object-cover border border-gray-100 shadow-sm">
                        <div class="space-y-0.5">
                            <h4 class="text-base font-extrabold text-gray-900 leading-tight">{{ $agentName }}</h4>
                            <p class="text-xs text-gray-500">{{ __('Satış Mütəxəssisi') }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">{{ __('Telefon') }}:</span>
                            <a href="tel:{{ $agentPhone }}" class="font-bold text-gray-900 hover:text-orange-500 transition duration-200">{{ $agentPhone }}</a>
                        </div>
                    </div>

                    <a href="tel:{{ $agentPhone }}"
                       class="w-full flex items-center justify-center gap-2 py-3.5 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold rounded-2xl shadow-md transition duration-200 transform active:scale-98">
                        <i class="bi bi-telephone-fill text-sm"></i>
                        <span>{{ __('Zəng et') }}</span>
                    </a>
                </div>

                @include('components.property.multiple-phone-modal')
                @include('components.property.premium-buttons')
                @include('components.property.premium-modal')
                @include('components.property.move-forward-modal')
            </div>
        </div>

    </div>
</div>

<script>
    const imagesData = [
        @foreach($property->images ?? [] as $image)
            "{{ $image->url }}",
        @endforeach
    ];
</script>
@endsection
