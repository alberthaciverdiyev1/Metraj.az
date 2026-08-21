@if(isset($similarProperties) && $similarProperties->count() > 0)
<div class="pt-8 relative">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('Oxşar Elanlar') }}</h3>
        <a href="/listing" class="text-sm font-bold text-orange-500 hover:text-orange-600 inline-flex items-center gap-1 transition">
            <span>{{ __('Hamısına bax') }}</span>
            <i class="bi bi-arrow-right text-xs"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        @foreach($similarProperties as $simProperty)
            @include('property::components.property-card', ['property' => $simProperty])
        @endforeach
    </div>
</div>
@endif
