@forelse($properties as $property)
    @include('components.property-card', ['property' => $property])
@empty
    <p class="col-span-full text-center text-gray-500 py-10">{{ __('listing.no_properties_found') }}</p>
@endforelse
