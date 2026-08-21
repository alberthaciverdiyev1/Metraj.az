@forelse($premiumProperties as $property)
    @include('property::components.property-card', ['property' => $property])
@empty
    <p class="col-span-full text-center text-gray-500">{{ __('Axtarışınıza uyğun premium elan tapılmadı.') }}</p>
@endforelse
