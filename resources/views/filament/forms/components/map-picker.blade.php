@php
    $citiesData = \App\Modules\Location\Models\City::with('districts')
        ->where('is_active', true)
        ->get()
        ->mapWithKeys(function ($city) {
            return [
                $city->id => [
                    'id' => $city->id,
                    'name' => $city->name['az'] ?? $city->slug,
                    'slug' => $city->slug,
                    'districts' => $city->districts->mapWithKeys(function ($d) {
                        return [
                            $d->id => [
                                'id' => $d->id,
                                'name' => $d->name['az'] ?? $d->slug,
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

<style>
.modern-map-wrapper {
    width: 100%;
    margin-top: 4px;
    margin-bottom: 8px;
}
.map-box {
    position: relative;
    width: 100%;
    height: 440px;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08), 0 4px 12px -2px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(226, 232, 240, 0.8);
    background: #f8fafc;
}
.map-instance {
    width: 100%;
    height: 100%;
    z-index: 1;
}

/* Floating controls */
.map-controls-top {
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 1000;
    display: flex;
    align-items: center;
    gap: 8px;
}
.layer-pill-group {
    display: flex;
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    padding: 3px;
    border-radius: 30px;
    gap: 2px;
}
.layer-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    font-size: 11.5px;
    font-weight: 600;
    border-radius: 20px;
    border: none;
    background: transparent;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
}
.layer-btn:hover {
    color: #0f172a;
    background: rgba(0, 0, 0, 0.04);
}
.layer-btn.active {
    background: #ea580c;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(234, 88, 12, 0.35);
}
.tool-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 50%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    cursor: pointer;
    transition: all 0.2s ease;
}
.tool-btn:hover {
    transform: scale(1.06);
    background: #ffffff;
}

/* Bottom status card */
.map-badge-bottom {
    position: absolute;
    bottom: 14px;
    left: 14px;
    right: 14px;
    z-index: 1000;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.7);
    border-radius: 12px;
    padding: 8px 14px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    gap: 8px;
    pointer-events: auto;
}
.badge-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
}
.badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 8px #22c55e;
    animation: mapPulse 2s infinite;
}
.badge-label {
    font-weight: 600;
    color: #475569;
}
.badge-val {
    font-family: monospace;
    font-weight: 700;
    color: #0f172a;
    background: rgba(0, 0, 0, 0.05);
    padding: 2px 6px;
    border-radius: 4px;
}
.badge-hint {
    font-size: 11.5px;
    color: #64748b;
    font-weight: 500;
}

@keyframes mapPulse {
    0% { transform: scale(0.95); opacity: 0.8; }
    50% { transform: scale(1.3); opacity: 1; }
    100% { transform: scale(0.95); opacity: 0.8; }
}

/* Custom Modern Animated Metraj Map Pin */
.metraj-pin-container {
    position: relative;
    width: 44px;
    height: 44px;
    margin-left: -22px;
    margin-top: -44px;
    cursor: grab;
    pointer-events: auto;
}
.metraj-pin-container:active {
    cursor: grabbing;
}
.metraj-pin-ripple {
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 22px;
    height: 8px;
    border-radius: 50%;
    background: rgba(234, 88, 12, 0.35);
    box-shadow: 0 0 10px rgba(234, 88, 12, 0.5);
    animation: pinRipple 2.2s infinite ease-out;
}
.metraj-pin-bubble {
    position: relative;
    width: 38px;
    height: 38px;
    margin: 0 auto;
    background: #f97316;
    border-radius: 50% 50% 50% 0;
    transform: rotate(-45deg);
    box-shadow: 0 8px 18px rgba(234, 88, 12, 0.42), 0 2px 6px rgba(0, 0, 0, 0.15);
    border: 2.5px solid #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.metraj-pin-container:hover .metraj-pin-bubble {
    transform: rotate(-45deg) scale(1.1);
}
.metraj-pin-icon {
    transform: rotate(45deg);
    color: #ffffff;
    width: 17px;
    height: 17px;
}

@keyframes pinRipple {
    0% { transform: translateX(-50%) scale(0.6); opacity: 0.9; }
    100% { transform: translateX(-50%) scale(2.2); opacity: 0; }
}

/* Modern Leaflet Popup */
.leaflet-popup-content-wrapper {
    border-radius: 14px !important;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15) !important;
    padding: 4px 6px !important;
    font-family: inherit !important;
}
.leaflet-popup-tip {
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1) !important;
}
.popup-bubble-inner {
    padding: 6px 8px;
    font-size: 12.5px;
    color: #1e293b;
    line-height: 1.4;
}
.popup-bubble-title {
    font-weight: 700;
    color: #ea580c;
    margin-bottom: 2px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

<script src="{{ asset('js/filament/forms/map-picker.js') }}"></script>
