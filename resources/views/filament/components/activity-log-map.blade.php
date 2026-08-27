@php
    $log = $record ?? (isset($getRecord) && is_callable($getRecord) ? $getRecord() : null);
@endphp

<div class="space-y-4">
    @if($log && !empty($log->latitude) && !empty($log->longitude) && abs($log->latitude) > 0.001)
        {{-- Interactive Leaflet Map --}}
        <div class="relative w-full h-[380px] rounded-2xl overflow-hidden border border-gray-200 shadow-inner" id="log-map-container-{{ $log->id }}">
            <div id="log-map-{{ $log->id }}" class="w-full h-full z-0"></div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 text-xs bg-gray-50 p-3 rounded-xl border border-gray-200">
            <div class="flex items-center gap-2 text-gray-700">
                <span class="text-base">{{ $log->flag_emoji }}</span>
                <span class="font-semibold">{{ $log->city ?? 'Naməlum Şəhər' }}, {{ $log->country_name ?? $log->country_code }}</span>
                <span class="text-gray-400">({{ number_format($log->latitude, 4) }}, {{ number_format($log->longitude, 4) }})</span>
            </div>

            <a href="https://www.google.com/maps?q={{ $log->latitude }},{{ $log->longitude }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition shadow-2xs">
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                <span>Google Maps-də Aç</span>
            </a>
        </div>

        {{-- Leaflet Assets & Initialization --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <script>
            (function() {
                var mapId = "log-map-{{ $log->id }}";
                var lat = {{ $log->latitude }};
                var lon = {{ $log->longitude }};
                var label = "{{ addslashes($log->location_text) }} (IP: {{ $log->ip_address }})";

                function initMap() {
                    var el = document.getElementById(mapId);
                    if (!el) return;
                    
                    // Prevent re-initialization
                    if (el._leaflet_id) {
                        return;
                    }

                    var map = L.map(mapId, {
                        center: [lat, lon],
                        zoom: 12,
                        zoomControl: true
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(map);

                    var marker = L.marker([lat, lon]).addTo(map);
                    marker.bindPopup("<b>" + label + "</b><br>ISP: {{ addslashes($log->isp ?? 'Naməlum') }}").openPopup();

                    setTimeout(function() {
                        map.invalidateSize();
                    }, 300);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initMap);
                } else {
                    setTimeout(initMap, 200);
                }
            })();
        </script>
    @elseif($log)
        <div class="text-center py-10 px-4 bg-gray-50 rounded-2xl border border-gray-200">
            <div class="text-4xl mb-2">{{ $log->flag_emoji }}</div>
            <div class="text-sm font-semibold text-gray-800">Dəqiq GPS Koordinatları Tapılmadı</div>
            <div class="text-xs text-gray-500 mt-1">
                IP Ünvanı: <span class="font-mono">{{ $log->ip_address }}</span> | Məkan: {{ $log->location_text }}
            </div>
            @if($log->ip_address && $log->ip_address !== '127.0.0.1')
                <div class="mt-4">
                    <a href="https://whatismyipaddress.com/ip/{{ $log->ip_address }}" target="_blank" rel="noopener" class="text-xs text-orange-600 hover:underline">
                        IP Məlumatını Xarici Xidmətdə Yoxla &rarr;
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
