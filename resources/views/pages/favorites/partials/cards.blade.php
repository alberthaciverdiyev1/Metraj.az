@forelse($properties as $property)
    <div class="favorite-card-wrapper transition-all duration-300" data-fav-id="{{ $property->id }}">
        @include('components.property-card', ['property' => $property])
    </div>
@empty
    <div class="col-span-full text-center py-12">
        <p class="text-gray-500 font-medium">{{ __('Seçilmiş elan tapılmadı.') }}</p>
    </div>
@endforelse
