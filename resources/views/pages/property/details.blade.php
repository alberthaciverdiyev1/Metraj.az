@extends('layouts.app')

@section('content')
@php
    $dealTypeOpt = $property->filterOptions->firstWhere('filter_id', 2);
    $isRent = $dealTypeOpt ? (str_contains($dealTypeOpt->value, 'rent')) : false;

    $isAgentOrAgency = !empty($property->agent_id) 
        || !empty($property->agency_id) 
        || in_array($property->seller_type, ['agent', 'agency']) 
        || !empty($property->agent) 
        || !empty($property->agency);

    $agentName = $property->agent->user->name ?? ($property->agency->name ?? ($property->user->name ?? 'Mülkiyyətçi'));
    $agentPhone = $property->agent->phone ?? ($property->agency->phone ?? ($property->user->phone ?? ($property->phone ?? '+994 50 123 45 67')));
    $agentAvatar = $property->agent->avatar_url ?? ($property->agency->logo_url ?? ($property->agent->user->avatar ?? 'https://themesflat.co/html/proty/images/avatar/seller.jpg'));
    $agentRole = $property->agency ? 'Rəsmi Agentlik' : ($property->agent ? 'Rieltor / Satış Mütəxəssisi' : 'Mülkiyyətçi');
@endphp

<div class="w-full pt-4">
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
<div class="w-full mt-6">
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
<div class="w-full mt-8 pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Side: Property Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Title & Price Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100 space-y-5">
                @php
                    $displayPrice = app(\App\Modules\Property\Services\PropertyPricePresenter::class)->display($property);
                    $activeCur = $displayPrice['currency'];
                    $prices = $property->prices ?? [];
                    if (empty($prices) && $property->price > 0) {
                        $prices = app(\App\Modules\Shared\Services\CurrencyService::class)->convertFromGbp((float) $property->price);
                    }
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-snug">{{ $property->title }}</h1>
                    <div class="text-right shrink-0">
                        <div class="text-2xl sm:text-3xl font-black text-orange-500 whitespace-nowrap">
                            {{ $displayPrice['symbol'] }} {{ $displayPrice['formatted'] }}
                            @if($isRent)
                                <span class="text-sm font-medium text-gray-500">/ay</span>
                            @endif
                        </div>

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

            @include('components.property.map', ['location' => $property, 'zoom' => 15])
            <!-- Features (Təchizatlar) Component -->
            @include('components.property.features', ['features' => $property->amenities ?? [], 'column' => 3])

            <!-- Similar Cards Section -->
            @include('components.similar-cards', ['similarProperties' => $similarProperties, 'currentProperty' => $property])
        </div>

        <!-- Right Side: Sidebar (Sticky) -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 z-10 space-y-6">
                <!-- Contact Card -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
                    @if($isAgentOrAgency)
                        <!-- Realtor / Agency Layout -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $agentRole }}</h3>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-600 border border-orange-200">
                                <i class="bi bi-patch-check-fill text-orange-500"></i>
                                {{ __('Təsdiqlənmiş') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-4">
                            <img src="{{ $agentAvatar }}" alt="{{ $agentName }}" class="w-16 h-16 rounded-2xl object-cover border border-gray-100 shadow-sm">
                            <div class="space-y-0.5">
                                <h4 class="text-base font-extrabold text-gray-900 leading-tight">{{ $agentName }}</h4>
                                <p class="text-xs text-gray-500">{{ $agentRole }}</p>
                            </div>
                        </div>

                        @if(!empty($agentPhone))
                        <div class="pt-4 border-t border-gray-100 space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">{{ __('Telefon') }}:</span>
                                <a href="tel:{{ $agentPhone }}" class="font-bold text-gray-900 hover:text-orange-500 transition duration-200">{{ $agentPhone }}</a>
                            </div>
                        </div>

                        <a href="tel:{{ $agentPhone }}"
                           class="w-full flex items-center justify-center gap-2 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-md transition duration-200 transform active:scale-98">
                            <i class="bi bi-telephone-fill text-sm"></i>
                            <span>{{ __('Zəng et') }}</span>
                        </a>
                        @endif

                        <!-- Müraciət Et Formu (Rieltor və ya Agentlik elanlarında) -->
                        <div class="pt-6 border-t border-gray-100 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                    <i class="bi bi-chat-left-dots-fill text-orange-500"></i>
                                    <span>{{ __('Müraciət Göndər') }}</span>
                                </h4>
                                <span class="text-[11px] text-gray-400 font-medium">{{ __('Onlayn sorğu') }}</span>
                            </div>

                            <form method="POST" action="{{ route('inquiries.store') }}" id="inquiry-form" class="space-y-3">
                                @csrf
                                <input type="hidden" name="property_id" value="{{ $property->id }}">

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('Adınız və Soyadınız') }} <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" required value="{{ auth()->user()?->name }}" placeholder="Məs: Əli Əliyev"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('Əlaqə Nömrəniz') }} <span class="text-rose-500">*</span></label>
                                    <input type="text" name="phone" required value="{{ auth()->user()?->phone }}" placeholder="Məs: +994 50 123 45 67"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('Mesajınız / Qeyd') }}</label>
                                    <textarea name="message" rows="3" placeholder="Salam, bu əmlakla bağlı ətraflı məlumat almaq istərdim..."
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition"></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full py-3 bg-gray-900 hover:bg-black text-white font-bold text-xs rounded-xl shadow transition duration-200 flex items-center justify-center gap-2 transform active:scale-98">
                                    <i class="bi bi-send-fill text-xs text-orange-400"></i>
                                    <span>{{ __('Müraciəti Göndər') }}</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Guest / Owner Layout (Sadəcə Telefon və Zəng et) -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('Əlaqədar Şəxs') }}</h3>
                                <span class="text-xs font-semibold text-gray-500">{{ __('Mülkiyyətçi') }}</span>
                            </div>

                            @if(!empty($agentName) && $agentName !== 'Metraj Təmsilçisi')
                            <div class="text-base font-extrabold text-gray-900">
                                {{ $agentName }}
                            </div>
                            @endif

                            @if(!empty($agentPhone))
                            <div class="p-4 bg-orange-50/60 border border-orange-100 rounded-2xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center text-lg shadow-sm">
                                        <i class="bi bi-telephone-fill"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[11px] text-gray-500 font-medium">{{ __('Əlaqə nömrəsi') }}</span>
                                        <a href="tel:{{ $agentPhone }}" class="text-base font-black text-gray-900 hover:text-orange-600 transition">{{ $agentPhone }}</a>
                                    </div>
                                </div>
                            </div>

                            <a href="tel:{{ $agentPhone }}"
                               class="w-full flex items-center justify-center gap-2 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-md transition duration-200 transform active:scale-98">
                                <i class="bi bi-telephone-fill text-sm"></i>
                                <span>{{ __('Zəng et') }}</span>
                            </a>
                            @endif
                        </div>
                    @endif
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

    // Müraciət (inquiry) formu — JS fetch ilə göndərilir
    document.addEventListener('DOMContentLoaded', function () {
        const inquiryForm = document.getElementById('inquiry-form');
        if (!inquiryForm) return;

        inquiryForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = inquiryForm.querySelector('button[type="submit"]');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>';

            const { ok, data } = await window.Metraj.post(
                inquiryForm.action,
                new FormData(inquiryForm)
            );

            btn.disabled = false;
            btn.innerHTML = originalHtml;

            if (ok) {
                window.Metraj.toast(data.message || 'Müraciətiniz qəbul edildi ✅');
                inquiryForm.reset();
            } else {
                window.Metraj.toast(data.message || 'Xəta baş verdi, yenidən cəhd edin', 'error');
            }
        });
    });
</script>
@endsection
