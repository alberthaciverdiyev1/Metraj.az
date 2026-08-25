@php
    $address = $location->address ?? '';
    $city = '';
    $region = '';
    $lat = $location->latitude ?? 40.409264;
    $lng = $location->longitude ?? 49.867092;
    $title = $location->title ?? __('property.property_specs');
    $price = isset($location->price) ? '£ ' . number_format($location->price, 0, '.', ' ') : '';

    if ($location instanceof \App\Modules\Property\Models\Property) {
        $city = $location->city?->localized_name ?? ($location->filterOptions->firstWhere('filter_id', 1)?->localized_name ?? 'Girne');
        $region = $location->district?->localized_name ?? '';
    } else {
        $address = $location->address ?? $location['address'] ?? '-';
        $city = $location->city?->localized_name ?? ($location->city->name ?? $location['city']['name'] ?? 'Girne');
        $region = $location->district?->localized_name ?? ($location->district->name ?? $location['district']['name'] ?? '');
    }

    $mapId = 'property_detail_map_' . ($location->id ?? uniqid());

    $mapConfig = [
        'id' => $mapId,
        'lat' => (float) $lat,
        'lng' => (float) $lng,
        'title' => $title,
        'price' => $price,
        'address' => $address ?: $city,
    ];
@endphp

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-4">
        <div>
            <h3 class="text-xl font-semibold text-gray-900">{{ __('property.location_on_map') }}</h3>
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
        <div id="{{ $mapId }}" class="w-full h-full z-0" data-map-config='@json($mapConfig)'></div>

        <!-- Layer Switcher Floating Button -->
        <div class="absolute top-3 right-3 z-10 bg-white/95 backdrop-blur-md rounded-xl p-1 shadow-lg border border-gray-200 flex gap-1 text-xs font-semibold">
            <button type="button" onclick="window['switchLayer_{{ $mapId }}']('carto')" id="btn_carto_{{ $mapId }}"
                class="px-3 py-1.5 rounded-lg bg-orange-500 text-white shadow-sm transition">{{ __('property.map') }}</button>
            <button type="button" onclick="window['switchLayer_{{ $mapId }}']('satellite')" id="btn_sat_{{ $mapId }}"
                class="px-3 py-1.5 rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 transition">{{ __('property.satellite') }}</button>
        </div>
    </div>

    <!-- Address info footer -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
            <p class="text-xs text-gray-500">{{ __('property.city') }}</p>
            <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $city ?: 'Girne' }}</p>
        </div>
        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
            <p class="text-xs text-gray-500">{{ __('property.district') }}</p>
            <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $region ?: '—' }}</p>
        </div>
        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
            <p class="text-xs text-gray-500">{{ __('property.exact_address') }}</p>
            <p class="text-sm font-semibold text-gray-800 mt-0.5 truncate" title="{{ $address }}">{{ $address ?: '—' }}</p>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<link rel="stylesheet" href="{{ asset('css/components/property-map.css') }}" />
<script src="{{ asset('js/components/property-map.js') }}"></script>
