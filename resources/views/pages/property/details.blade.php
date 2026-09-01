@extends('layouts.app')

@section('content')
    @php
        $selectedOptions = $property->filterOptions ?? collect();
        $dealTypeOpt = $selectedOptions->first(fn($o) => in_array($o->value, ['sale', 'rent_monthly', 'rent_daily']) || ($o->filter && $o->filter->key === 'deal_type'));
        $propertyTypeOpt = $selectedOptions->first(fn($o) => in_array($o->value, ['apartment', 'house', 'office', 'garage', 'land', 'commercial']) || ($o->filter && $o->filter->key === 'property_type'));
        $buildingTypeOpt = $selectedOptions->first(fn($o) => in_array($o->value, ['new_building', 'old_building']) || ($o->filter && $o->filter->key === 'building_type'));
        $repairTypeOpt = $selectedOptions->first(fn($o) => in_array($o->value, ['repaired', 'unrepaired', 'tadilatli']) || ($o->filter && $o->filter->key === 'repair'));

        $isRent = $dealTypeOpt ? (str_contains(mb_strtolower($dealTypeOpt->name['az'] ?? $dealTypeOpt->value), 'rent') || str_contains(mb_strtolower($dealTypeOpt->name['az'] ?? $dealTypeOpt->value), 'kirayə') || str_contains(mb_strtolower($dealTypeOpt->name['az'] ?? $dealTypeOpt->value), 'kira')) : false;
        $isLand = $propertyTypeOpt ? (str_contains(mb_strtolower($propertyTypeOpt->name['az'] ?? $propertyTypeOpt->value), 'torpaq') || $propertyTypeOpt->value === 'land' || str_contains(mb_strtolower($propertyTypeOpt->name['tr'] ?? ''), 'arsa')) : false;

        $isAgentOrAgency = !empty($property->agent_id)
            || !empty($property->agency_id)
            || in_array($property->seller_type, ['agent', 'agency'])
            || !empty($property->agent)
            || !empty($property->agency);
        $agentName = $property->agent->user->name
            ?? ($property->agency->name
            ?? ($property->contact_name
            ?? ($property->user->name
            ?? __('property.owner'))));
        $agentAvatar = $property->agent->avatar_url ?? ($property->agency->logo_url ?? ($property->agent->user->avatar ?? ''));
        $agentRole = $property->agency ? __('property.official_agency') : ($property->agent ? __('property.agent') : __('property.owner'));
        $hasContact = !empty($property->agent_id)
            || !empty($property->agency_id)
            || !empty($property->phone)
            || !empty($property->user_id);

        $galleryImages = $property->images->sortBy('sort_order')->values();
        $hasVideo = !empty($property->video_url);

        $mediaItems = collect();
        if ($hasVideo) {
            $mediaItems->push([
                'type' => 'video',
                'url' => $property->video_url,
                'thumb' => $galleryImages->first()?->url ?? null,
            ]);
        }
        foreach ($galleryImages as $img) {
            $mediaItems->push([
                'type' => 'image',
                'url' => $img->url,
                'thumb' => $img->url,
            ]);
        }
        $totalMedia = $mediaItems->count();
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
                    <!-- Main Hero Media Container -->
                    <div
                        class="relative w-full h-[320px] sm:h-[420px] md:h-[480px] lg:h-[500px] rounded-2xl md:rounded-3xl overflow-hidden bg-black shadow-sm select-none group">
                        
                        @if($totalMedia > 0)
                            <!-- Hero Image -->
                            <img id="main-hero-image"
                                 src="{{ $mediaItems->firstWhere('type', 'image')['url'] ?? $galleryImages->first()?->url ?? asset('images/box-house.jpg') }}"
                                 alt="{{ $property->title }}"
                                 fetchpriority="high"
                                 class="w-full h-full object-cover cursor-pointer transition-transform duration-300 group-hover:scale-[1.01]"
                                 style="{{ $hasVideo ? 'display: none;' : 'display: block;' }}"
                                 onclick="openModal(currentHeroIndex)">

                            <!-- Hero Video (if present, starts visible as item 0) -->
                            @if($hasVideo)
                                <div id="main-hero-video-wrapper" class="w-full h-full flex items-center justify-center bg-black" style="display: flex;">
                                    <video id="main-hero-video"
                                           src="{{ $property->video_url }}"
                                           controls
                                           playsinline
                                           preload="metadata"
                                           class="w-full h-full object-contain">
                                    </video>
                                </div>
                            @endif
                        @else
                            <img id="main-hero-image"
                                 src="{{ asset('images/box-house.jpg') }}"
                                 alt="{{ $property->title }}"
                                 class="w-full h-full object-cover">
                        @endif

                        <!-- Fullscreen / Expand Button (Top-Right) -->
                        <button type="button"
                                onclick="openModal(currentHeroIndex)"
                                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-black/45 hover:bg-black/65 text-white flex items-center justify-center backdrop-blur-md transition z-10 cursor-pointer shadow-sm"
                                title="{{ __('property.expand') }}">
                            <i class="bi bi-arrows-fullscreen text-sm"></i>
                        </button>

                        <!-- Navigation Chevrons (Left / Right) -->
                        @if($totalMedia > 1)
                            <button type="button"
                                    onclick="prevHeroImage(event)"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-black/45 hover:bg-black/65 text-white flex items-center justify-center backdrop-blur-md transition z-10 cursor-pointer shadow-sm"
                                    aria-label="{{ __('property.previous') }}">
                                <i class="bi bi-chevron-left text-base sm:text-lg"></i>
                            </button>

                            <button type="button"
                                    onclick="nextHeroImage(event)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-black/45 hover:bg-black/65 text-white flex items-center justify-center backdrop-blur-md transition z-10 cursor-pointer shadow-sm"
                                    aria-label="{{ __('property.next') }}">
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

                        <!-- Bottom-Center Media Counter -->
                        <div id="hero-counter"
                             class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/60 backdrop-blur-md text-white text-xs font-semibold px-3 py-1.5 rounded-full z-10 shadow-sm">
                            1/{{ max(1, $totalMedia) }}
                        </div>

                        <!-- Bottom-Right "Bütün şəkillər" Button -->
                        <button type="button"
                                onclick="openModal(0)"
                                class="absolute bottom-4 right-4 bg-white/85 hover:bg-white text-gray-900 text-xs font-semibold px-3.5 py-1.5 rounded-xl backdrop-blur-md transition shadow-sm flex items-center gap-1.5 z-10 cursor-pointer">
                            <span>{{ __('property.all_media') }}</span>
                        </button>
                    </div>

                    <!-- Thumbnails Row -->
                    @if($totalMedia > 0)
                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                            @php
                                $maxThumbs = 8;
                                $visibleMedia = $mediaItems->take($maxThumbs);
                                $hasMore = $totalMedia > $maxThumbs;
                            @endphp

                            @foreach($visibleMedia as $index => $item)
                                @if($hasMore && $index === ($maxThumbs - 1))
                                    <!-- Last visible thumbnail with +X overlay -->
                                    <div
                                        class="h-16 sm:h-20 rounded-xl overflow-hidden cursor-pointer relative border-2 border-transparent hover:opacity-95 transition"
                                        onclick="openModal({{ $index }})">
                                        @if($item['type'] === 'video')
                                            <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                                <i class="bi bi-play-circle-fill text-orange-500 text-2xl"></i>
                                            </div>
                                        @else
                                            <img src="{{ $item['thumb'] }}" alt="{{ $property->title }}" loading="lazy" decoding="async"
                                                 class="w-full h-full object-cover">
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-black/65 hover:bg-black/55 backdrop-blur-[1px] flex items-center justify-center text-white text-xs sm:text-sm font-bold transition">
                                            +{{ $totalMedia - $maxThumbs + 1 }}
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="h-16 sm:h-20 rounded-xl overflow-hidden cursor-pointer relative border-2 hero-thumbnail {{ $index === 0 ? 'border-orange-500 ring-2 ring-orange-500/20' : 'border-transparent' }} hover:border-orange-300 transition"
                                        onclick="selectHeroImage({{ $index }})">
                                        @if($item['type'] === 'video')
                                            <div class="w-full h-full bg-gray-900 flex items-center justify-center relative overflow-hidden">
                                                @if(!empty($item['thumb']))
                                                    <img src="{{ $item['thumb'] }}" alt="" loading="lazy" decoding="async" class="w-full h-full object-cover opacity-60">
                                                @endif
                                                <div class="absolute inset-0 flex flex-col items-center justify-center gap-0.5 bg-black/30">
                                                    <i class="bi bi-play-circle-fill text-orange-500 text-xl sm:text-2xl drop-shadow"></i>
                                                    <span class="text-[9px] font-bold text-white uppercase tracking-wider">Video</span>
                                                </div>
                                            </div>
                                        @else
                                            <img src="{{ $item['thumb'] }}" alt="{{ $property->title }}" loading="lazy" decoding="async"
                                                 class="w-full h-full object-cover">
                                        @endif
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
                            <button type="button"
                                onclick="event.stopPropagation(); toggleFavorite(this, {{ $property->id }})"
                                data-fav-btn="{{ $property->id }}"
                                class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition cursor-pointer"
                                title="{{ __('property.add_to_favorites') }}"><i class="fa-regular fa-heart"></i></button>
                            <button type="button"
                                onclick="event.stopPropagation(); toggleCompare(this, {{ $property->id }})"
                                data-compare-btn="{{ $property->id }}"
                                class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-orange-50 hover:text-orange-500 flex items-center justify-center transition cursor-pointer"
                                title="{{ __('property.compare') }}"><i class="bi bi-arrow-left-right"></i></button>
                            <button type="button"
                                class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-gray-100 hover:text-gray-900 flex items-center justify-center transition cursor-pointer"
                                id="printBtn" onclick="window.print()" title="{{ __('property.print') }}"><i class="bi bi-printer"></i></button>
                            <button type="button"
                                class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-blue-50 hover:text-blue-500 flex items-center justify-center transition cursor-pointer"
                                onclick="shareProperty(this)" title="{{ __('property.share') }}"><i class="bi bi-share"></i></button>
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

                <!-- Features (Təchizatlar / Donanım ve Özellikler) Component -->
                @include('components.property.features', ['features' => $property->amenities ?? [], 'column' => 3])

                <!-- Mobile Only: Agency / Contact Info (Donanım ve Özelliklerden sonra, Benzer İlanlardan önce) -->
                <div class="block lg:hidden space-y-3.5 pt-2">
                    @include('pages.property.partials.details-sidebar')
                </div>

                <!-- Similar Cards Section (Benzer İlanlar) -->
                @include('components.similar-cards', ['similarProperties' => $similarProperties, 'currentProperty' => $property])
            </div>

            <!-- Right Column: Sticky Sidebar (4 cols, Desktop Only) -->
            <div class="hidden lg:block lg:col-span-4 self-stretch">
                <div class="sticky top-20 lg:top-[88px] z-20 space-y-3.5">
                    @include('pages.property.partials.details-sidebar')
                </div>
            </div>

        </div>
    </div>

    <!-- Modals for promotion & calls -->
    @include('components.property.multiple-phone-modal')
    @include('components.property.premium-modal')
    @include('components.property.move-forward-modal')

    <!-- Modal Fullscreen Slider -->
    <div id="modal" class="fixed inset-0 bg-black/95 z-[99999] flex flex-col justify-center items-center select-none"
         style="display: none;">
        <div
            class="absolute top-4 left-1/2 -translate-x-1/2 w-11/12 max-w-6xl flex justify-between items-center text-white z-[100002] px-2">
            <span id="counter"
                  class="text-sm font-semibold bg-black/50 px-3.5 py-1.5 rounded-full backdrop-blur-md">1/{{ max(1, $totalMedia) }}</span>
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleFullscreen()"
                        class="w-10 h-10 rounded-full bg-white/15 hover:bg-white/30 text-white flex items-center justify-center transition cursor-pointer"
                        title="{{ __('property.fullscreen') }}"><i class="bi bi-fullscreen text-sm"></i></button>
                <button type="button" onclick="closeModal()"
                        class="w-10 h-10 rounded-full bg-white/15 hover:bg-white/30 text-white flex items-center justify-center transition cursor-pointer"
                        title="{{ __('property.close') }}"><i class="bi bi-x-lg text-sm"></i></button>
            </div>
        </div>
        <div class="absolute inset-0 flex items-center justify-between pointer-events-none z-[100000] px-2 sm:px-6">
            <button type="button" onclick="prevModalImage(event)"
                    class="pointer-events-auto w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-black/50 hover:bg-orange-500 text-white flex items-center justify-center text-xl sm:text-2xl transition cursor-pointer backdrop-blur-sm shadow-md"
                    aria-label="{{ __('property.previous') }}"><i class="bi bi-chevron-left"></i></button>

            <div class="pointer-events-auto max-w-[90vw] max-h-[75vh] flex items-center justify-center">
                <img id="modal-image" src="" alt="Modal Image"
                     class="max-w-[90vw] max-h-[75vh] object-contain rounded-2xl shadow-2xl"
                     style="display: block;">
                @if($hasVideo)
                    <div id="modal-video-wrapper" class="max-w-[90vw] max-h-[75vh] w-[900px] aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl flex items-center justify-center" style="display: none;">
                        <video id="modal-video" src="" controls playsinline class="w-full h-full object-contain"></video>
                    </div>
                @endif
            </div>

            <button type="button" onclick="nextModalImage(event)"
                    class="pointer-events-auto w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-black/50 hover:bg-orange-500 text-white flex items-center justify-center text-xl sm:text-2xl transition cursor-pointer backdrop-blur-sm shadow-md"
                    aria-label="{{ __('property.next') }}"><i class="bi bi-chevron-right"></i></button>
        </div>
        <div
            class="absolute bottom-4 left-1/2 -translate-x-1/2 w-11/12 max-w-4xl flex space-x-2 overflow-x-auto p-2 bg-black/60 backdrop-blur-md rounded-2xl z-[100001] justify-center"
            id="thumbnails">
            @foreach($mediaItems as $index => $item)
                @if($item['type'] === 'video')
                    <div onclick="openModal({{ $index }})"
                         class="w-14 h-14 sm:w-18 sm:h-18 shrink-0 rounded-xl border-2 border-transparent cursor-pointer hover:border-orange-500 transition modal-thumb bg-gray-900 flex flex-col items-center justify-center relative overflow-hidden">
                        @if(!empty($item['thumb']))
                            <img src="{{ $item['thumb'] }}" alt="" loading="lazy" decoding="async" class="w-full h-full object-cover opacity-50">
                        @endif
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="bi bi-play-fill text-orange-500 text-2xl"></i>
                        </div>
                    </div>
                @else
                    <img src="{{ $item['thumb'] }}" onclick="openModal({{ $index }})" alt="" loading="lazy" decoding="async"
                         class="w-14 h-14 sm:w-18 sm:h-18 shrink-0 object-cover rounded-xl border-2 border-transparent cursor-pointer hover:border-orange-500 transition modal-thumb">
                @endif
            @endforeach
        </div>
    </div>

    <script>
        window.mediaList = @json($mediaItems->values());
        let currentHeroIndex = 0;

        function selectHeroImage(index) {
            if (!window.mediaList || window.mediaList.length === 0) return;
            currentHeroIndex = index;
            const item = window.mediaList[index];

            const mainImg = document.getElementById('main-hero-image');
            const videoWrapper = document.getElementById('main-hero-video-wrapper');
            const heroVideo = document.getElementById('main-hero-video');

            if (item.type === 'video') {
                if (mainImg) mainImg.style.display = 'none';
                if (videoWrapper) videoWrapper.style.display = 'flex';
            } else {
                if (videoWrapper) {
                    videoWrapper.style.display = 'none';
                    if (heroVideo) heroVideo.pause();
                }
                if (mainImg) {
                    mainImg.style.display = 'block';
                    mainImg.src = item.url;
                }
            }

            const counter = document.getElementById('hero-counter');
            if (counter) {
                counter.textContent = (index + 1) + '/' + window.mediaList.length;
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
            if (!window.mediaList || window.mediaList.length === 0) return;
            currentHeroIndex = (currentHeroIndex - 1 + window.mediaList.length) % window.mediaList.length;
            selectHeroImage(currentHeroIndex);
        }

        function nextHeroImage(e) {
            if (e) e.stopPropagation();
            if (!window.mediaList || window.mediaList.length === 0) return;
            currentHeroIndex = (currentHeroIndex + 1) % window.mediaList.length;
            selectHeroImage(currentHeroIndex);
        }

        window.revealedPhones = window.revealedPhones || {};

        async function revealPropertyPhone(e, btn, propertyId, intent = 'call') {
            if (e) e.preventDefault();

            if (window.revealedPhones[propertyId]) {
                const data = window.revealedPhones[propertyId];
                if (intent === 'whatsapp' && data.whatsapp_url) {
                    window.open(data.whatsapp_url, '_blank');
                } else if (data.call_url) {
                    window.location.href = data.call_url;
                }
                return;
            }

            const btnTexts = document.querySelectorAll('.js-phone-btn-text');
            btnTexts.forEach(t => t.textContent = '...');

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    || '{{ csrf_token() }}';

                const currentLocale = document.documentElement.lang || '{{ app()->getLocale() }}';
                const revealUrl = '/' + currentLocale + '/listings/' + propertyId + '/reveal-phone';

                const res = await fetch(revealUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();

                if (data.success && data.phone) {
                    window.revealedPhones[propertyId] = data;

                    // Reveal phone number text
                    document.querySelectorAll('.js-phone-display').forEach(el => {
                        el.textContent = data.phone;
                        el.classList.remove('blur-xs', 'filter', 'select-none', 'tracking-wider');
                        el.innerHTML = `<a href="${data.call_url || ('tel:' + data.clean_phone)}" class="hover:text-orange-500 transition">${data.phone}</a>`;
                    });

                    // Update call buttons
                    document.querySelectorAll('.js-btn-show-phone').forEach(b => {
                        const icon = b.querySelector('i');
                        if (icon) icon.className = 'bi bi-telephone-fill text-xs';
                        const txt = b.querySelector('.js-phone-btn-text');
                        if (txt) txt.textContent = "{{ __('property.call') }}";
                    });

                    // Update WhatsApp links
                    document.querySelectorAll('.js-btn-whatsapp').forEach(w => {
                        if (data.whatsapp_url) {
                            w.href = data.whatsapp_url;
                            w.target = '_blank';
                            w.rel = 'noopener noreferrer';
                        }
                    });

                    if (intent === 'whatsapp' && data.whatsapp_url) {
                        window.open(data.whatsapp_url, '_blank');
                    }
                }
            } catch (err) {
                console.error('reveal-phone error:', err);
                btnTexts.forEach(t => t.textContent = "{{ __('property.show_phone') }}");
            }
        }

        function shareProperty(btn) {
            const url = window.location.href;
            const text = @json($property->title);
            const done = function () {
                if (typeof window.KibrisKare !== 'undefined' && window.KibrisKare.toast) {
                    window.KibrisKare.toast("{{ __('property.share_copied') }}", 'success');
                }
            };
            if (navigator.share) {
                navigator.share({ title: text, url: url }).catch(function () {});
            } else if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(done).catch(function () {});
            } else {
                const input = document.createElement('input');
                input.value = url;
                document.body.appendChild(input);
                input.select();
                try { document.execCommand('copy'); } catch (e) {}
                document.body.removeChild(input);
                done();
            }
        }

        // Modal Lightbox Functions
        let currentModalIndex = 0;
        const modal = document.getElementById('modal');
        const modalImage = document.getElementById('modal-image');
        const modalVideoWrapper = document.getElementById('modal-video-wrapper');
        const modalVideo = document.getElementById('modal-video');
        const modalCounter = document.getElementById('counter');

        function openModal(index) {
            if (!window.mediaList || window.mediaList.length === 0) return;
            currentModalIndex = index;
            updateModal();
            if (modal) modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            if (modal) modal.style.display = 'none';
            if (modalVideo) modalVideo.pause();
            document.body.style.overflow = 'auto';
        }

        function prevModalImage(e) {
            if (e) e.stopPropagation();
            if (!window.mediaList || window.mediaList.length === 0) return;
            currentModalIndex = (currentModalIndex - 1 + window.mediaList.length) % window.mediaList.length;
            updateModal();
        }

        function nextModalImage(e) {
            if (e) e.stopPropagation();
            if (!window.mediaList || window.mediaList.length === 0) return;
            currentModalIndex = (currentModalIndex + 1) % window.mediaList.length;
            updateModal();
        }

        function updateModal() {
            if (!window.mediaList || !window.mediaList[currentModalIndex]) return;
            const item = window.mediaList[currentModalIndex];

            if (item.type === 'video') {
                if (modalImage) modalImage.style.display = 'none';
                if (modalVideoWrapper) {
                    modalVideoWrapper.style.display = 'flex';
                    if (modalVideo) modalVideo.src = item.url;
                }
            } else {
                if (modalVideoWrapper) {
                    modalVideoWrapper.style.display = 'none';
                    if (modalVideo) {
                        modalVideo.pause();
                        modalVideo.src = '';
                    }
                }
                if (modalImage) {
                    modalImage.style.display = 'block';
                    modalImage.src = item.url;
                }
            }

            if (modalCounter) {
                modalCounter.textContent = (currentModalIndex + 1) + '/' + window.mediaList.length;
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

            document.querySelectorAll('.js-btn-advance, #btn-advance').forEach(b => {
                b.addEventListener('click', () => {
                    if (modalAdvance) modalAdvance.style.display = 'flex';
                });
            });
            document.querySelectorAll('.js-btn-premium, #btn-premium, #btn-vip').forEach(b => {
                b.addEventListener('click', () => {
                    if (modalPremium) modalPremium.style.display = 'flex';
                });
            });

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

