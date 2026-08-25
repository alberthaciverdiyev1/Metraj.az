<section id="amenities" class="bg-white rounded-2xl shadow p-4 sm:p-6 space-y-4 sm:space-y-6">
    <h3 class="text-xl sm:text-2xl font-semibold text-black">{{ __('property.amenities_and_features') }}</h3>

    @php $features = collect($features); @endphp
    @if($features->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-3.5">
            @foreach($features as $feature)
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-lg text-orange-500 text-lg shrink-0"></i>
                    <span class="text-sm text-gray-700">{{ $feature->localized_name ?? ($feature->name ?? $feature) }}</span>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">{{ __('property.no_amenities_specified') }}</p>
    @endif
</section>
