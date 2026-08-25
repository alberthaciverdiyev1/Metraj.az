@php
    $selectedOptions = $item->filterOptions ?? collect();
    $propertyTypeOpt = $selectedOptions->firstWhere('filter_id', 3);
    $buildingTypeOpt = $selectedOptions->firstWhere('filter_id', 4);
    $repairTypeOpt = $selectedOptions->firstWhere('filter_id', 5);
    $heatingOpt = $selectedOptions->firstWhere('filter_id', 6);
    $viewOpt = $selectedOptions->firstWhere('filter_id', 7);

    $dealTypeOpt = $selectedOptions->firstWhere('filter_id', 2);
    $isRent = false;
    if ($dealTypeOpt && (str_contains(mb_strtolower($dealTypeOpt->name['az'] ?? $dealTypeOpt->value), 'kirayə') || str_contains(mb_strtolower($dealTypeOpt->name['az'] ?? $dealTypeOpt->value), 'kira') || str_contains($dealTypeOpt->value, 'rent'))) {
        $isRent = true;
    }

    $isLand = false;
    if ($propertyTypeOpt && str_contains(mb_strtolower($propertyTypeOpt->name['az'] ?? $propertyTypeOpt->value), 'torpaq')) {
        $isLand = true;
    }
@endphp

<section id="property-detail" class="bg-white rounded-3xl border border-gray-200/80 p-6 sm:p-8 space-y-7 shadow-sm">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-gray-100 pb-5">
        <h3 class="text-xl font-semibold text-gray-900 tracking-tight">{{ __('property.property_specs') }}</h3>
        <span class="text-xs font-semibold text-gray-400">
            {{ __('property.ad_code') }}: <span class="text-gray-700 font-mono font-semibold">#{{ $item->code ?? $item->id }}</span>
        </span>
    </div>

    <!-- Key Quick Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @if($isLand || !empty($item->land_area))
        <div class="flex flex-col p-4 rounded-2xl bg-gray-50 border border-gray-100">
            <span class="text-xs text-gray-500 font-medium">{{ __('property.land_area') }}</span>
            <span class="text-lg font-semibold text-gray-900 mt-1">{{ $item->land_area ?? '—' }} {{ __('property.land_unit') }}</span>
        </div>
        @endif

        @if(!$isLand && !empty($item->area))
        <div class="flex flex-col p-4 rounded-2xl bg-gray-50 border border-gray-100">
            <span class="text-xs text-gray-500 font-medium">{{ __('property.area') }}</span>
            <span class="text-lg font-semibold text-gray-900 mt-1">{{ $item->area }} m²</span>
        </div>
        @endif

        @if(!$isLand && !empty($item->rooms))
        <div class="flex flex-col p-4 rounded-2xl bg-gray-50 border border-gray-100">
            <span class="text-xs text-gray-500 font-medium">{{ __('property.room_count') }}</span>
            <span class="text-lg font-semibold text-gray-900 mt-1">{{ $item->rooms }}</span>
        </div>
        @endif

        @if(!$isLand && (!empty($item->floor) || !empty($item->total_floors)))
        <div class="flex flex-col p-4 rounded-2xl bg-gray-50 border border-gray-100">
            <span class="text-xs text-gray-500 font-medium">{{ __('property.floor') }}</span>
            <span class="text-lg font-semibold text-gray-900 mt-1">{{ $item->floor ?? '—' }} / {{ $item->total_floors ?? '—' }}</span>
        </div>
        @endif

        <div class="flex flex-col p-4 rounded-2xl bg-gray-50 border border-gray-100">
            <span class="text-xs text-gray-500 font-medium">{{ __('property.views_count') }}</span>
            <span class="text-lg font-semibold text-gray-900 mt-1">{{ number_format($item->views_count ?? 0) }}</span>
        </div>
    </div>
    <!-- Description -->
    @if(!empty($item->description))
        <div class="border-t border-gray-100 pt-6">
            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">{{ __('property.description') }}</h4>
            <div class="text-gray-700 text-sm leading-relaxed space-y-2 font-normal prose prose-sm max-w-none">
                {!! $item->description !!}
            </div>
        </div>
    @endif
    <!-- Structured Specifications Table -->
    <div class="border-t border-gray-100 pt-6">
        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">{{ __('property.technical_specs') }}</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3.5 text-sm">
            @if($propertyTypeOpt)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">{{ __('property.property_type') }}</span>
                <span class="font-semibold text-gray-900">{{ $propertyTypeOpt->localized_name }}</span>
            </div>
            @endif

            @if($buildingTypeOpt)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">{{ __('property.building_type') }}</span>
                <span class="font-semibold text-gray-900">{{ $buildingTypeOpt->localized_name }}</span>
            </div>
            @endif

            @if($repairTypeOpt && !$isLand)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">{{ __('property.repair_status') }}</span>
                <span class="font-semibold text-gray-900">{{ $repairTypeOpt->localized_name }}</span>
            </div>
            @endif

            @if($heatingOpt && !$isLand)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">{{ __('property.heating_system') }}</span>
                <span class="font-semibold text-gray-900">{{ $heatingOpt->localized_name }}</span>
            </div>
            @endif

            @if($viewOpt && !$isLand)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">{{ __('property.window_view') }}</span>
                <span class="font-semibold text-gray-900">{{ $viewOpt->localized_name }}</span>
            </div>
            @endif

            @if(!$isRent)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">{{ __('property.deed_title') }}</span>
                <span class="font-semibold {{ $item->has_document ? 'text-gray-900' : 'text-gray-400' }}">
                    {{ $item->has_document ? __('property.has_document_yes') : __('property.has_document_no') }}
                </span>
            </div>

            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">{{ __('property.mortgage_eligible') }}</span>
                <span class="font-semibold {{ $item->has_mortgage ? 'text-gray-900' : 'text-gray-400' }}">
                    {{ $item->has_mortgage ? __('property.eligible_yes') : __('property.eligible_no') }}
                </span>
            </div>

            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">{{ __('property.internal_credit') }}</span>
                <span class="font-semibold {{ $item->has_internal_credit ? 'text-gray-900' : 'text-gray-400' }}">
                    {{ $item->has_internal_credit ? __('property.available_yes') : __('property.eligible_no') }}
                </span>
            </div>
            @endif
        </div>
    </div>


</section>
