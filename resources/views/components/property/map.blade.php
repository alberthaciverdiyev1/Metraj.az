@php
    $address = $location->address ?? '';
    $city = '';
    $region = '';
    $lat = $location->latitude ?? 40.409264;
    $lng = $location->longitude ?? 49.867092;
    $title = $location->title ?? 'Əmlak Məkanı';
    $price = isset($location->price) ? '£ ' . number_format($location->price, 0, '.', ' ') : '';

    if ($location instanceof \App\Modules\Property\Models\Property) {
        $city = $location->city?->name['az'] ?? ($location->filterOptions->firstWhere('filter_id', 1)?->name['az'] ?? 'Bakı');
        $region = $location->district?->name['az'] ?? '';
    } else {
        $address = $location->address ?? $location['address'] ?? '-';
        $city = $location->city->name ?? $location['city']['name'] ?? 'Bakı';
        $region = $location->district->name ?? $location['district']['name'] ?? '';
    }

    $mapId = 'property_detail_map_' . ($location->id ?? uniqid());
@endphp

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-4">
        <div>
            <h3 class="text-xl font-bold text-gray-900">{{ __('Xəritədə Məkan') }}</h3>
            <p class="text-xs text-gray-500 mt-0.5">{{ $address ?: ($region ? $region . ', ' . $city : $city) }}</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-50 text-orange-700 text-xs font-semibold rounded-full border border-orange-200">
                <i class="bi bi-geo-alt-fill text-orange-500"></i>
                <span>{{ $city }} @if($region) • {{ $region }} @endif</span>
            </span>
        </div>
    </div>

    <!-- Map Container -->
    <div class="relative w-full h-[400px] sm:h-[450px] rounded-2xl overflow-hidden shadow-inner border border-gray-200">
        <div id="{{ $mapId }}" class="w-full h-full z-0" data-map-config='@json([
            'id' => $mapId,
            'lat' => (float) $lat,
            'lng' => (float) $lng,
            'title' => $title,
            'price' => $price,
            'address' => $address ?: $city,
        ])'></div>

        <!-- Layer Switcher Floating Button -->
        <div class="absolute top-3 right-3 z-10 bg-white/95 backdrop-blur-md rounded-xl p-1 shadow-lg border border-gray-200 flex gap-1 text-xs font-semibold">
            <button type="button" onclick="window['switchLayer_{{ $mapId }}']('carto')" id="btn_carto_{{ $mapId }}"
                class="px-3 py-1.5 rounded-lg bg-orange-500 text-white shadow-sm transition">Xəritə</button>
            <button type="button" onclick="window['switchLayer_{{ $mapId }}']('satellite')" id="btn_sat_{{ $mapId }}"
                class="px-3 py-1.5 rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 transition">Peyk</button>
        </div>
    </div>

    <!-- Address info footer -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
            <p class="text-xs text-gray-500">{{ __('Şəhər') }}</p>
            <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $city ?: 'Bakı' }}</p>
        </div>
        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
            <p class="text-xs text-gray-500">{{ __('Rayon / Qəsəbə') }}</p>
            <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $region ?: '—' }}</p>
        </div>
        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
            <p class="text-xs text-gray-500">{{ __('Dəqiq Ünvan') }}</p>
            <p class="text-sm font-bold text-gray-800 mt-0.5 truncate" title="{{ $address }}">{{ $address ?: '—' }}</p>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="{{ asset('js/components/property-map.js') }}"></script>

<style>
@keyframes leaflet-pulse {
    0% { transform: scale(0.6); opacity: 0.8; }
    50% { transform: scale(1.3); opacity: 0; }
    100% { transform: scale(0.6); opacity: 0; }
}
</style>
