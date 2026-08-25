@extends('layouts.app')

@section('content')
    @php
        $selectedOptions = $property->filterOptions ?? collect();
        $propertyTypeOpt = $selectedOptions->firstWhere('filter_id', 3);
        $buildingTypeOpt = $selectedOptions->firstWhere('filter_id', 4);
        $repairTypeOpt = $selectedOptions->firstWhere('filter_id', 5);
        $dealTypeOpt = $selectedOptions->firstWhere('filter_id', 2);
        $isRent = $dealTypeOpt ? (str_contains(mb_strtolower($dealTypeOpt->name['az'] ?? $dealTypeOpt->value), 'rent') || str_contains(mb_strtolower($dealTypeOpt->name['az'] ?? $dealTypeOpt->value), 'kirayə') || str_contains(mb_strtolower($dealTypeOpt->name['az'] ?? $dealTypeOpt->value), 'kira')) : false;
        $isLand = $propertyTypeOpt ? str_contains(mb_strtolower($propertyTypeOpt->name['az'] ?? $propertyTypeOpt->value), 'torpaq') : false;

        $isAgentOrAgency = !empty($property->agent_id)
            || !empty($property->agency_id)
            || in_array($property->seller_type, ['agent', 'agency'])
            || !empty($property->agent)
            || !empty($property->agency);

        $agentName = $property->agent->user->name ?? ($property->agency->name ?? ($property->user->name ?? 'Mülkiyyətçi'));
        $agentPhone = $property->agent->phone ?? ($property->agency->phone ?? ($property->user->phone ?? ($property->phone ?? '+994 50 123 45 67')));
        $agentWhatsapp = $property->agent->whatsapp ?? ($property->agency->whatsapp ?? null);
        $cleanWhatsapp = $agentWhatsapp ? preg_replace('/[^0-9]/', '', $agentWhatsapp) : null;
        $agentAvatar = $property->agent->avatar_url ?? ($property->agency->logo_url ?? ($property->agent->user->avatar ?? ''));
        $agentRole = $property->agency ? 'Rəsmi Agentlik' : ($property->agent ? 'Vasitəçi (agent)' : 'Mülkiyyətçi');

        $galleryImages = $property->images->sortBy('sort_order')->values();
        $totalImages = count($galleryImages);

        $displayPrice = app(\App\Modules\Property\Services\PropertyPricePresenter::class)->display($property);
        $pricePerM2 = (!empty($property->area) && (float)$property->area > 0) ? ($displayPrice['amount'] / (float)$property->area) : null;
    @endphp

    <div class="w-full pt-4">
        @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    </div>

    @include('components.scroll-top')

    <!-- Main Layout: 2 Columns (8 cols left content, 4 cols sticky sidebar) -->
    <div class="w-full mt-4 sm:mt-6 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Gallery & Details (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Gallery (Hero & Thumbnails) -->
                <div class="space-y-3">
                    <!-- Main Hero Image Container -->
                    <div
                        class="relative w-full h-[320px] sm:h-[420px] md:h-[480px] lg:h-[500px] rounded-2xl md:rounded-3xl overflow-hidden bg-gray-900 shadow-sm select-none group">
                        @if($totalImages > 0)
                            <img id="main-hero-image"
                                 src="{{ $galleryImages->first()?->url }}"
                                 alt="{{ $property->title }}"
                                 class="w-full h-full object-cover cursor-pointer transition-transform duration-300 group-hover:scale-[1.01]"
                                 onclick="openModal(currentHeroIndex)">
                        @else
                            <img id="main-hero-image"
                                 src="https://static.vecteezy.com/system/resources/previews/004/640/986/non_2x/tower-building-illustration-isolated-on-white-background-vector.jpg"
                                 alt="{{ $property->title }}"
                                 class="w-full h-full object-cover">
                        @endif

                        <!-- Fullscreen / Expand Button (Top-Right) -->
                        <button type="button"
                                onclick="openModal(currentHeroIndex)"
                                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-black/45 hover:bg-black/65 text-white flex items-center justify-center backdrop-blur-md transition z-10 cursor-pointer shadow-sm"
                                title="{{ __('Böyüt') }}">
                            <i class="bi bi-arrows-fullscreen text-sm"></i>
                        </button>

                        <!-- Navigation Chevrons (Left / Right) -->
                        @if($totalImages > 1)
                            <button type="button"
                                    onclick="prevHeroImage(event)"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-black/45 hover:bg-black/65 text-white flex items-center justify-center backdrop-blur-md transition z-10 cursor-pointer shadow-sm"
                                    aria-label="Əvvəlki">
                                <i class="bi bi-chevron-left text-base sm:text-lg"></i>
                            </button>

                            <button type="button"
                                    onclick="nextHeroImage(event)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-black/45 hover:bg-black/65 text-white flex items-center justify-center backdrop-blur-md transition z-10 cursor-pointer shadow-sm"
                                    aria-label="Növbəti">
                                <i class="bi bi-chevron-right text-base sm:text-lg"></i>
                            </button>
                        @endif

                        <!-- Bottom-Left Badges (VIP / Crown) -->
                        @if($property->is_vip)

                            <div class="absolute bottom-4 left-4 flex items-center gap-1.5 z-10">
                                <div class="flex items-center gap-1.5 bg-white px-2.5 py-1 rounded-lg shadow-sm">
                                    <i class="fa-solid fa-crown text-amber-500 text-xs"></i>
                                </div>
                            </div>

                        @endif

                        <!-- Bottom-Center Image Counter -->
                        <div id="hero-counter"
                             class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/60 backdrop-blur-md text-white text-xs font-semibold px-3 py-1.5 rounded-full z-10 shadow-sm">
                            1/{{ max(1, $totalImages) }}
                        </div>

                        <!-- Bottom-Right "Bütün şəkillər" Button -->
                        <button type="button"
                                onclick="openModal(0)"
                                class="absolute bottom-4 right-4 bg-white/85 hover:bg-white text-gray-900 text-xs font-semibold px-3.5 py-1.5 rounded-xl backdrop-blur-md transition shadow-sm flex items-center gap-1.5 z-10 cursor-pointer">
                            <span>{{ __('Bütün şəkillər') }}</span>
                        </button>
                    </div>

                    <!-- Thumbnails Row -->
                    @if($totalImages > 0)
                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                            @php
                                $maxThumbs = 8;
                                $visibleImages = $galleryImages->take($maxThumbs);
                                $hasMore = $totalImages > $maxThumbs;
                            @endphp

                            @foreach($visibleImages as $index => $image)
                                @if($hasMore && $index === ($maxThumbs - 1))
                                    <!-- Last visible thumbnail with +X overlay -->
                                    <div
                                        class="h-16 sm:h-20 rounded-xl overflow-hidden cursor-pointer relative border-2 border-transparent hover:opacity-95 transition"
                                        onclick="openModal({{ $index }})">
                                        <img src="{{ $image->url }}" alt="{{ $property->title }}"
                                             class="w-full h-full object-cover">
                                        <div
                                            class="absolute inset-0 bg-black/65 hover:bg-black/55 backdrop-blur-[1px] flex items-center justify-center text-white text-xs sm:text-sm font-bold transition">
                                            +{{ $totalImages - $maxThumbs + 1 }} {{ __('şəkil') }}
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="h-16 sm:h-20 rounded-xl overflow-hidden cursor-pointer relative border-2 hero-thumbnail {{ $index === 0 ? 'border-orange-500 ring-2 ring-orange-500/20' : 'border-transparent' }} hover:border-orange-300 transition"
                                        onclick="selectHeroImage({{ $index }})">
                                        <img src="{{ $image->url }}" alt="{{ $property->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Title & Address Card -->
                <div class="bg-white p-6 sm:p-7 rounded-3xl shadow-sm border border-gray-100 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-snug">{{ $property->title }}</h1>
                        <div class="flex items-center gap-3 text-gray-400 text-lg shrink-0">
                            <button
                                class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition"
                                title="{{ __('Seçilmişlərə əlavə et') }}"><i class="bi bi-heart"></i></button>
                            <button
                                class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-orange-50 hover:text-orange-500 flex items-center justify-center transition"
                                title="{{ __('Müqayisə et') }}"><i class="bi bi-arrow-left-right"></i></button>
                            <button
                                class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-gray-100 hover:text-gray-900 flex items-center justify-center transition"
                                id="printBtn" title="{{ __('Çap et') }}"><i class="bi bi-printer"></i></button>
                            <button
                                class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-blue-50 hover:text-blue-500 flex items-center justify-center transition"
                                title="{{ __('Paylaş') }}"><i class="bi bi-share"></i></button>
                        </div>
                    </div>

                    <div class="flex items-center text-gray-500 text-sm border-t border-gray-100 pt-3">
                        <i class="bi bi-geo-alt-fill text-orange-500 mr-2 text-base"></i>
                        <span class="font-medium text-gray-700">{{ $property->address }}</span>
                    </div>
                </div>

                <!-- Property Specs & Description Component -->
                @include('components.property.specs', ['item' => $property])

                <!-- Map Component -->
                @include('components.property.map', ['location' => $property, 'zoom' => 15])

                @if(!empty($property->video_url))
                    <!-- Video Component -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl shadow-sm border border-gray-100 space-y-4">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-camera-video-fill text-orange-500 text-lg"></i>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900">{{ __('Əmlakın Video Görüntüsü') }}</h3>
                        </div>
                        <div class="relative w-full rounded-2xl overflow-hidden bg-black shadow-inner aspect-video">
                            <video src="{{ $property->video_url }}" controls preload="metadata" class="w-full h-full object-contain rounded-2xl"></video>
                        </div>
                    </div>
                @endif

                <!-- Features (Təchizatlar) Component -->
                @include('components.property.features', ['features' => $property->amenities ?? [], 'column' => 3])

                <!-- Similar Cards Section -->
                @include('components.similar-cards', ['similarProperties' => $similarProperties, 'currentProperty' => $property])
            </div>

            <!-- Right Column: Sticky Sidebar (4 cols) -->
            <div class="lg:col-span-4 self-stretch">
                <div class="sticky top-20 lg:top-[88px] z-20 space-y-3.5">
                    <!-- Main Info Card -->
                    <div
                        class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-gray-100/90 flex flex-col justify-between space-y-5">

                        <!-- Price Header -->
                        <div class="space-y-1">
                            <div
                                class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight flex items-baseline gap-1.5">
                                <span>{{ $displayPrice['formatted'] }} {{ $displayPrice['currency'] }}</span>
                                @if($isRent)
                                    <span class="text-sm font-medium text-gray-500">/ay</span>
                                @endif
                            </div>
                            @if($pricePerM2)
                                <div class="text-sm font-medium text-gray-500">
                                    {{ number_format($pricePerM2, 0, '.', ' ') }} {{ $displayPrice['currency'] }}/m²
                                </div>
                            @endif
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-100 my-1"></div>

                        <!-- Previous Agent / Contact Design -->
                        @if($isAgentOrAgency)
                            <!-- Realtor / Agency Layout -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest">{{ $agentRole }}</h3>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-orange-50 text-orange-600 border border-orange-200">
                                    <i class="bi bi-patch-check-fill text-orange-500"></i>
                                    {{ __('Təsdiqlənmiş') }}
                                </span>
                                </div>

                                <div class="flex items-center gap-4">
                                    @if(!empty($agentAvatar))
                                        <img src="{{ $agentAvatar }}" alt="{{ $agentName }}"
                                             class="w-16 h-16 rounded-2xl object-cover border border-gray-100 shadow-sm">
                                    @else
                                        <div
                                            class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl border border-orange-100 shadow-sm">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    @endif
                                    <div class="space-y-0.5">
                                        <h4 class="text-base font-bold text-gray-900 leading-tight">{{ $agentName }}</h4>
                                        <p class="text-xs text-gray-500">{{ $agentRole }}</p>
                                    </div>
                                </div>

                                @if(!empty($agentPhone) || !empty($cleanWhatsapp))
                                    <div class="pt-3 border-t border-gray-100 space-y-2.5">
                                        @if(!empty($agentPhone))
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-500">{{ __('Telefon') }}:</span>
                                                <a href="tel:{{ $agentPhone }}"
                                                   class="font-semibold text-gray-900 hover:text-orange-500 transition duration-200">{{ $agentPhone }}</a>
                                            </div>
                                        @endif
                                        @if(!empty($agentWhatsapp))
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-500">WhatsApp:</span>
                                                <a href="https://wa.me/{{ $cleanWhatsapp }}" target="_blank"
                                                   rel="noopener noreferrer"
                                                   class="font-semibold text-emerald-600 hover:text-emerald-700 transition duration-200">{{ $agentWhatsapp }}</a>
                                            </div>
                                        @endif
                                    </div>

                                    <div
                                        class="grid {{ (!empty($agentPhone) && !empty($cleanWhatsapp)) ? 'grid-cols-2' : 'grid-cols-1' }} gap-2.5 pt-1">
                                        @if(!empty($agentPhone))
                                            <a href="tel:{{ $agentPhone }}"
                                               class="w-full flex items-center justify-center gap-2 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl shadow-md transition duration-200 transform active:scale-98 text-sm">
                                                <i class="bi bi-telephone-fill text-xs"></i>
                                                <span>{{ __('Zəng et') }}</span>
                                            </a>
                                        @endif

                                        @if(!empty($cleanWhatsapp))
                                            <a href="https://wa.me/{{ $cleanWhatsapp }}" target="_blank"
                                               rel="noopener noreferrer"
                                               class="w-full flex items-center justify-center gap-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl shadow-md transition duration-200 transform active:scale-98 text-sm">
                                                <i class="bi bi-whatsapp text-sm"></i>
                                                <span>WhatsApp</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <!-- Müraciət Et Formu (Rieltor və ya Agentlik elanlarında) -->
                                <div class="pt-5 border-t border-gray-100 space-y-3.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                            <i class="bi bi-chat-left-dots-fill text-orange-500"></i>
                                            <span>{{ __('Müraciət Göndər') }}</span>
                                        </h4>
                                        <span
                                            class="text-[11px] text-gray-400 font-medium">{{ __('Onlayn sorğu') }}</span>
                                    </div>

                                    <form method="POST" action="{{ route('inquiries.store') }}" id="inquiry-form"
                                          class="space-y-3">
                                        @csrf
                                        <input type="hidden" name="property_id" value="{{ $property->id }}">

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-600 mb-1">{{ __('Adınız və Soyadınız') }}
                                                <span class="text-rose-500">*</span></label>
                                            <input type="text" name="name" required value="{{ auth()->user()?->name }}"
                                                   placeholder="Məs: Əli Əliyev"
                                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-600 mb-1">{{ __('Əlaqə Nömrəniz') }}
                                                <span class="text-rose-500">*</span></label>
                                            <input type="text" name="phone" required
                                                   value="{{ auth()->user()?->phone }}"
                                                   placeholder="Məs: +994 50 123 45 67"
                                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-600 mb-1">{{ __('Mesajınız / Qeyd') }}</label>
                                            <textarea name="message" rows="3"
                                                      placeholder="Salam, bu əmlakla bağlı ətraflı məlumat almaq istərdim..."
                                                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition"></textarea>
                                        </div>

                                        <button type="submit"
                                                class="w-full py-3 bg-gray-900 hover:bg-black text-white font-semibold text-xs rounded-xl shadow transition duration-200 flex items-center justify-center gap-2 transform active:scale-98">
                                            <i class="bi bi-send-fill text-xs text-orange-400"></i>
                                            <span>{{ __('Müraciəti Göndər') }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Guest / Owner Layout (Sadəcə Telefon və Zəng et) -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest">{{ __('Əlaqədar Şəxs') }}</h3>
                                    <span class="text-xs font-semibold text-gray-500">{{ __('Mülkiyyətçi') }}</span>
                                </div>

                                @if(!empty($agentName) && $agentName !== 'Metraj Təmsilçisi')
                                    <div class="text-base font-bold text-gray-900">
                                        {{ $agentName }}
                                    </div>
                                @endif

                                @if(!empty($agentPhone))
                                    <div
                                        class="p-4 bg-orange-50/60 border border-orange-100 rounded-2xl flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center text-lg shadow-sm">
                                                <i class="bi bi-telephone-fill"></i>
                                            </div>
                                            <div>
                                                <span
                                                    class="block text-[11px] text-gray-500 font-medium">{{ __('Əlaqə nömrəsi') }}</span>
                                                <a href="tel:{{ $agentPhone }}"
                                                   class="text-base font-black text-gray-900 hover:text-orange-600 transition">{{ $agentPhone }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($agentPhone) || !empty($cleanWhatsapp))
                                    <div
                                        class="grid {{ (!empty($agentPhone) && !empty($cleanWhatsapp)) ? 'grid-cols-2' : 'grid-cols-1' }} gap-2.5">
                                        @if(!empty($agentPhone))
                                            <a href="tel:{{ $agentPhone }}"
                                               class="w-full flex items-center justify-center gap-2 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl shadow-md transition duration-200 transform active:scale-98 text-sm">
                                                <i class="bi bi-telephone-fill text-sm"></i>
                                                <span>{{ __('Zəng et') }}</span>
                                            </a>
                                        @endif

                                        @if(!empty($cleanWhatsapp))
                                            <a href="https://wa.me/{{ $cleanWhatsapp }}" target="_blank"
                                               rel="noopener noreferrer"
                                               class="w-full flex items-center justify-center gap-2 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl shadow-md transition duration-200 transform active:scale-98 text-sm">
                                                <i class="bi bi-whatsapp text-sm"></i>
                                                <span>WhatsApp</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <!-- İrəli çək -->
                        <div id="btn-advance"
                             class="bg-white hover:bg-gray-50 border border-gray-200/90 rounded-2xl p-3 flex flex-col justify-between cursor-pointer transition shadow-2xs">
                            <div class="flex items-center justify-between">
                                <span class="text-xs sm:text-sm font-bold text-gray-800">{{ __('İrəli çək') }}</span>
                                <span class="text-emerald-500 font-bold text-base"><i class="fa-solid fa-arrow-up"></i></span>
                            </div>
                            <span class="text-[11px] font-semibold text-blue-600 mt-1">3 AZN-dən</span>
                        </div>


                        <!-- Premium -->
                        <div id="btn-premium"
                             class="bg-white hover:bg-gray-50 border border-gray-200/90 rounded-2xl p-3 flex flex-col justify-between cursor-pointer transition shadow-2xs">
                            <div class="flex items-center justify-between">
                                <span class="text-xs sm:text-sm font-bold text-gray-800">{{ __('Premium') }}</span>
                                <span class="text-amber-500 text-base"><i class="fa-solid fa-crown"></i></span>
                            </div>
                            <span class="text-[11px] font-semibold text-blue-600 mt-1">7 AZN-dən</span>
                        </div>
                    </div>

                    <!-- Modals for promotion & calls -->
                    @include('components.property.multiple-phone-modal')
                    @include('components.property.premium-modal')
                    @include('components.property.move-forward-modal')
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Fullscreen Slider -->
    <div id="modal" class="fixed inset-0 bg-black/95 z-[99999] flex flex-col justify-center items-center select-none"
         style="display: none;">
        <div
            class="absolute top-4 left-1/2 -translate-x-1/2 w-11/12 max-w-6xl flex justify-between items-center text-white z-[100002] px-2">
            <span id="counter"
                  class="text-sm font-semibold bg-black/50 px-3.5 py-1.5 rounded-full backdrop-blur-md">1/{{ max(1, count($galleryImages)) }}</span>
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleFullscreen()"
                        class="w-10 h-10 rounded-full bg-white/15 hover:bg-white/30 text-white flex items-center justify-center transition cursor-pointer"
                        title="{{ __('Tam ekran') }}"><i class="bi bi-fullscreen text-sm"></i></button>
                <button type="button" onclick="closeModal()"
                        class="w-10 h-10 rounded-full bg-white/15 hover:bg-white/30 text-white flex items-center justify-center transition cursor-pointer"
                        title="{{ __('Bağla') }}"><i class="bi bi-x-lg text-sm"></i></button>
            </div>
        </div>
        <div class="absolute inset-0 flex items-center justify-between pointer-events-none z-[100000] px-2 sm:px-6">
            <button type="button" onclick="prevModalImage(event)"
                    class="pointer-events-auto w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-black/50 hover:bg-orange-500 text-white flex items-center justify-center text-xl sm:text-2xl transition cursor-pointer backdrop-blur-sm shadow-md"
                    aria-label="Əvvəlki"><i class="bi bi-chevron-left"></i></button>
            <img id="modal-image" src="" alt="Modal Image"
                 class="pointer-events-auto max-w-[90vw] max-h-[75vh] object-contain rounded-2xl shadow-2xl">
            <button type="button" onclick="nextModalImage(event)"
                    class="pointer-events-auto w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-black/50 hover:bg-orange-500 text-white flex items-center justify-center text-xl sm:text-2xl transition cursor-pointer backdrop-blur-sm shadow-md"
                    aria-label="Növbəti"><i class="bi bi-chevron-right"></i></button>
        </div>
        <div
            class="absolute bottom-4 left-1/2 -translate-x-1/2 w-11/12 max-w-4xl flex space-x-2 overflow-x-auto p-2 bg-black/60 backdrop-blur-md rounded-2xl z-[100001] justify-center"
            id="thumbnails">
            @foreach($galleryImages as $index => $image)
                <img src="{{ $image->url }}" onclick="openModal({{ $index }})" alt=""
                     class="w-14 h-14 sm:w-18 sm:h-18 shrink-0 object-cover rounded-xl border-2 border-transparent cursor-pointer hover:border-orange-500 transition modal-thumb">
            @endforeach
        </div>
    </div>

    <script>
        window.galleryImages = @json($galleryImages->map(fn($img) => $img->url)->values());
        window.fullPhoneNumber = @json($agentPhone);
        let currentHeroIndex = 0;

        function selectHeroImage(index) {
            if (!window.galleryImages || window.galleryImages.length === 0) return;
            currentHeroIndex = index;
            const mainImg = document.getElementById('main-hero-image');
            if (mainImg) {
                mainImg.src = window.galleryImages[index];
            }
            const counter = document.getElementById('hero-counter');
            if (counter) {
                counter.textContent = (index + 1) + '/' + window.galleryImages.length;
            }
            const thumbs = document.querySelectorAll('.hero-thumbnail');
            thumbs.forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.add('border-orange-500', 'ring-2', 'ring-orange-500/20');
                    thumb.classList.remove('border-transparent');
                } else {
                    thumb.classList.remove('border-orange-500', 'ring-2', 'ring-orange-500/20');
                    thumb.classList.add('border-transparent');
                }
            });
        }

        function prevHeroImage(e) {
            if (e) e.stopPropagation();
            if (!window.galleryImages || window.galleryImages.length === 0) return;
            currentHeroIndex = (currentHeroIndex - 1 + window.galleryImages.length) % window.galleryImages.length;
            selectHeroImage(currentHeroIndex);
        }

        function nextHeroImage(e) {
            if (e) e.stopPropagation();
            if (!window.galleryImages || window.galleryImages.length === 0) return;
            currentHeroIndex = (currentHeroIndex + 1) % window.galleryImages.length;
            selectHeroImage(currentHeroIndex);
        }

        function revealPhoneNumber() {
            const numElem = document.getElementById('phone-btn-number');
            const titleElem = document.getElementById('phone-btn-title');
            const btn = document.getElementById('btn-show-phone');
            if (window.fullPhoneNumber) {
                if (numElem) numElem.textContent = window.fullPhoneNumber;
                if (titleElem) titleElem.textContent = "{{ __('Zəng edin') }}";
                if (btn && !btn.dataset.revealed) {
                    btn.dataset.revealed = "true";
                } else if (btn && btn.dataset.revealed === "true") {
                    window.location.href = "tel:" + window.fullPhoneNumber;
                }
            }
        }

        // Modal Lightbox Functions
        let currentModalIndex = 0;
        const modal = document.getElementById('modal');
        const modalImage = document.getElementById('modal-image');
        const modalCounter = document.getElementById('counter');

        function openModal(index) {
            if (!window.galleryImages || window.galleryImages.length === 0) return;
            currentModalIndex = index;
            updateModal();
            if (modal) modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            if (modal) modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function prevModalImage(e) {
            if (e) e.stopPropagation();
            if (!window.galleryImages || window.galleryImages.length === 0) return;
            currentModalIndex = (currentModalIndex - 1 + window.galleryImages.length) % window.galleryImages.length;
            updateModal();
        }

        function nextModalImage(e) {
            if (e) e.stopPropagation();
            if (!window.galleryImages || window.galleryImages.length === 0) return;
            currentModalIndex = (currentModalIndex + 1) % window.galleryImages.length;
            updateModal();
        }

        function updateModal() {
            if (modalImage && window.galleryImages[currentModalIndex]) {
                modalImage.src = window.galleryImages[currentModalIndex];
            }
            if (modalCounter) {
                modalCounter.textContent = (currentModalIndex + 1) + '/' + window.galleryImages.length;
            }
            const modalThumbs = document.querySelectorAll('.modal-thumb');
            modalThumbs.forEach((thumb, i) => {
                if (i === currentModalIndex) {
                    thumb.classList.add('border-orange-500', 'scale-105');
                    thumb.classList.remove('border-transparent');
                    thumb.scrollIntoView({behavior: 'smooth', block: 'nearest', inline: 'center'});
                } else {
                    thumb.classList.remove('border-orange-500', 'scale-105');
                    thumb.classList.add('border-transparent');
                }
            });
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.error(err));
            } else {
                document.exitFullscreen();
            }
        }

        document.addEventListener('keydown', function (e) {
            if (modal && modal.style.display === 'flex') {
                if (e.key === 'Escape') closeModal();
                if (e.key === 'ArrowLeft') prevModalImage(e);
                if (e.key === 'ArrowRight') nextModalImage(e);
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const btnAdvance = document.getElementById('btn-advance');
            const btnVip = document.getElementById('btn-vip');
            const btnPremium = document.getElementById('btn-premium');
            const modalAdvance = document.getElementById('modal-advance');
            const modalPremium = document.getElementById('modal-premium');

            if (btnAdvance && modalAdvance) {
                btnAdvance.addEventListener('click', () => {
                    modalAdvance.style.display = 'flex';
                });
            }
            if (btnVip && modalPremium) {
                btnVip.addEventListener('click', () => {
                    modalPremium.style.display = 'flex';
                });
            }
            if (btnPremium && modalPremium) {
                btnPremium.addEventListener('click', () => {
                    modalPremium.style.display = 'flex';
                });
            }

            const closeBtns = document.querySelectorAll('[data-close]');
            closeBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-close');
                    const targetModal = document.getElementById(targetId);
                    if (targetModal) targetModal.style.display = 'none';
                });
            });

            window.addEventListener('click', function (e) {
                if (e.target === modalAdvance) modalAdvance.style.display = 'none';
                if (e.target === modalPremium) modalPremium.style.display = 'none';
                if (e.target === modal) closeModal();
            });
        });
    </script>
    <script src="{{ asset('js/pages/property/details.js') }}"></script>
@endsection

