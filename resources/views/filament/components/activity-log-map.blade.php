@php
    $log = $record ?? (isset($getRecord) && is_callable($getRecord) ? $getRecord() : null);
    $lat = $log?->latitude ? (float) $log->latitude : null;
    $lon = $log?->longitude ? (float) $log->longitude : null;
    $hasCoords = $lat !== null && $lon !== null && (abs($lat) > 0.001 || abs($lon) > 0.001);
@endphp

<div class="space-y-4 font-sans select-none" x-data="{ mapType: 'google' }">
    @if($log && $hasCoords)
        {{-- Header Controls / Map Provider Switcher --}}
        <div class="flex items-center justify-between pb-1">
            <div class="flex items-center gap-2 text-xs font-semibold text-gray-700">
                <span class="text-base">{{ $log->flag_emoji ?? '📍' }}</span>
                <span>{{ $log->city ?? 'Naməlum Şəhər' }}, {{ $log->country_name ?? $log->country_code }}</span>
                <span class="text-gray-400 font-mono text-[11px]">({{ number_format($lat, 4) }}, {{ number_format($lon, 4) }})</span>
            </div>

            <div class="inline-flex rounded-xl p-1 bg-gray-100 border border-gray-200 text-xs">
                <button type="button" 
                        @click="mapType = 'google'" 
                        :class="mapType === 'google' ? 'bg-white text-blue-600 font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900'" 
                        class="px-2.5 py-1 rounded-lg transition text-[11px] flex items-center gap-1 cursor-pointer">
                    <i class="fa-brands fa-google text-[10px]"></i> Google Maps
                </button>
                <button type="button" 
                        @click="mapType = 'osm'" 
                        :class="mapType === 'osm' ? 'bg-white text-green-600 font-bold shadow-xs' : 'text-gray-600 hover:text-gray-900'" 
                        class="px-2.5 py-1 rounded-lg transition text-[11px] flex items-center gap-1 cursor-pointer">
                    <i class="fa-solid fa-map-location-dot text-[10px]"></i> OpenStreetMap
                </button>
            </div>
        </div>

        {{-- Map Container with Iframe (100% Reliable across all browser and modal lifecycles) --}}
        <div class="relative w-full h-[400px] rounded-2xl overflow-hidden border border-gray-200 bg-gray-100 shadow-inner">
            {{-- Google Maps Embed --}}
            <iframe x-show="mapType === 'google'"
                    class="w-full h-full border-0"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://maps.google.com/maps?q={{ $lat }},{{ $lon }}&hl=tr&z=13&output=embed">
            </iframe>

            {{-- OpenStreetMap Embed --}}
            <iframe x-show="mapType === 'osm'"
                    class="w-full h-full border-0"
                    loading="lazy"
                    src="https://www.openstreetmap.org/export/embed.html?bbox={{ $lon - 0.035 }}%2C{{ $lat - 0.02 }}%2C{{ $lon + 0.035 }}%2C{{ $lat + 0.02 }}&amp;layer=mapnik&amp;marker={{ $lat }}%2C{{ $lon }}">
            </iframe>
        </div>

        {{-- Bottom Details & External Links --}}
        <div class="bg-gray-50 border border-gray-200/80 rounded-2xl p-3.5 space-y-3">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-2xs">
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">IP Ünvanı</span>
                    <span class="font-mono font-semibold text-gray-800">{{ $log->ip_address }}</span>
                </div>
                <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-2xs">
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Provayder (ISP)</span>
                    <span class="font-semibold text-gray-800 truncate block" title="{{ $log->isp }}">{{ $log->isp ?? 'Naməlum' }}</span>
                </div>
                <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-2xs">
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Cihaz & Sistem</span>
                    <span class="font-semibold text-gray-800">{{ $log->device_type }} / {{ $log->os }}</span>
                </div>
                <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-2xs">
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Brauzer</span>
                    <span class="font-semibold text-gray-800 truncate block">{{ $log->browser ?? 'Naməlum' }}</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                <div class="text-[11px] text-gray-500">
                    ⏱️ Qeydiyyat Vaxtı: <span class="font-medium text-gray-700">{{ $log->created_at?->format('d.m.Y H:i:s') }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <a href="https://www.google.com/maps?q={{ $lat }},{{ $lon }}" 
                       target="_blank" 
                       rel="noopener" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition shadow-2xs">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        <span>Google Maps</span>
                    </a>

                    <a href="https://www.openstreetmap.org/?mlat={{ $lat }}&mlon={{ $lon }}#map=14/{{ $lat }}/{{ $lon }}" 
                       target="_blank" 
                       rel="noopener" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs font-semibold rounded-xl transition">
                        <i class="fa-solid fa-map-location-dot text-[10px]"></i>
                        <span>OpenStreetMap</span>
                    </a>

                    <a href="https://ipinfo.io/{{ $log->ip_address }}" 
                       target="_blank" 
                       rel="noopener" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                        <i class="fa-solid fa-globe text-[10px]"></i>
                        <span>IP Info</span>
                    </a>
                </div>
            </div>
        </div>

    @elseif($log)
        <div class="text-center py-10 px-4 bg-gray-50 rounded-2xl border border-gray-200">
            <div class="text-4xl mb-2">{{ $log->flag_emoji ?? '📍' }}</div>
            <div class="text-sm font-semibold text-gray-800">Dəqiq GPS Koordinatları Tapılmadı</div>
            <div class="text-xs text-gray-500 mt-1">
                IP Ünvanı: <span class="font-mono font-bold">{{ $log->ip_address }}</span> | Məkan: {{ $log->location_text }}
            </div>
            @if($log->ip_address && $log->ip_address !== '127.0.0.1')
                <div class="mt-4 flex items-center justify-center gap-2">
                    <a href="https://ipinfo.io/{{ $log->ip_address }}" target="_blank" rel="noopener" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-medium hover:bg-blue-100 transition">
                        IP Məlumatını Yoxla (IPInfo) &rarr;
                    </a>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-6 px-4 text-xs text-gray-400">
            Məkan məlumatı mövcud deyil.
        </div>
    @endif
</div>
