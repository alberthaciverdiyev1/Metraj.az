@php
    $dealTypeOpt = $currentProperty->filterOptions->firstWhere('filter_id', 2);
    $addType = $dealTypeOpt ? ($dealTypeOpt->value === 'sale' ? 'sale' : 'rent') : 'sale';
@endphp

<div class="pt-8 relative min-h-[480px]">
    <h3 class="mb-4 text-2xl py-7 text-[var(--primary)] font-semibold">{{ __('Oxşar elanlara bax') }}</h3>
    <div class="similar-properties-grid grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-3 mb-4"
         id="similar-properties-container"
         data-add-type="{{ $addType }}"
         data-current-property-id="{{ $currentProperty->id ?? '' }}">
        <div class="absolute inset-0 z-50 flex justify-center items-center bg-white/50" id="similar-cards-loader">
            <div class="w-6 h-6 border-2 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>
</div>
