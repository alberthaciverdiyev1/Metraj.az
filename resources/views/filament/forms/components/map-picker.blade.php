@php
    $citiesData = \App\Modules\Location\Models\City::with('districts')
        ->where('is_active', true)
        ->get()
        ->mapWithKeys(function ($city) {
            return [
                $city->id => [
                    'id' => $city->id,
                    'name' => $city->name[app()->getLocale()] ?? ($city->name['tr'] ?? ($city->name['az'] ?? $city->slug)),
                    'slug' => $city->slug,
                    'districts' => $city->districts->mapWithKeys(function ($d) {
                        return [
                            $d->id => [
                                'id' => $d->id,
                                'name' => $d->name[app()->getLocale()] ?? ($d->name['tr'] ?? ($d->name['az'] ?? $d->slug)),
                                'slug' => $d->slug,
                            ]
                        ];
                    })->toArray(),
                ]
            ];
        })->toArray();
    $citiesJson = json_encode($citiesData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
@endphp

<div
    x-data="osmMapPicker($wire, {{ $citiesJson }})"
    wire:ignore
    class="modern-map-wrapper"
>
    <!-- Map Container with Glassmorphism Overlays -->
    <div class="map-box">
        <!-- The Leaflet Map Instance Canvas -->
        <div x-ref="mapDiv" class="map-instance"></div>

        <!-- Top Right: Layer Switcher & Tools -->
        <div class="map-controls-top">
            <div class="layer-pill-group">
                <button
                    type="button"
                    @click="setLayer('voyager')"
                    :class="activeLayer === 'voyager' ? 'layer-btn active' : 'layer-btn'"
                    title="Müasir Xəritə"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    <span>Standart</span>
                </button>

                <button
                    type="button"
                    @click="setLayer('satellite')"
                    :class="activeLayer === 'satellite' ? 'layer-btn active' : 'layer-btn'"
                    title="Peyk Görüntüsü"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Peyk</span>
                </button>

                <button
                    type="button"
                    @click="setLayer('light')"
                    :class="activeLayer === 'light' ? 'layer-btn active' : 'layer-btn'"
                    title="Minimal Açıq Xəritə"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Minimal</span>
                </button>
            </div>

            <!-- GPS / My location button -->
            <button
                type="button"
                @click="locateMe()"
                class="tool-btn"
                title="Cari Məkanımı Tap"
            >
                <svg class="w-4 h-4 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </button>
        </div>

        <!-- Bottom Floating Coordinates & Info Bar -->
        <div class="map-badge-bottom">
            <div class="badge-item">
                <span class="badge-dot"></span>
                <span class="badge-label">Koordinatlar:</span>
                <span class="badge-val" x-text="(latitude ? parseFloat(latitude).toFixed(6) : '40.409264') + ', ' + (longitude ? parseFloat(longitude).toFixed(6) : '49.867092')"></span>
            </div>
            <div class="badge-hint">
                <svg class="w-3.5 h-3.5 text-orange-500 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Xəritədə klik edərək və ya pini sürükləyərək dəqiq ünvanı seçin
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/filament/forms/map-picker.css') }}">

<script src="{{ asset('js/filament/forms/map-picker.js') }}"></script>
