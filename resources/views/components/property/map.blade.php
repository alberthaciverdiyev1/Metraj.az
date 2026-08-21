@php
    $address = $location->address ?? '';
    $city = '';
    $region = '';
    if ($location instanceof \App\Core\Infrastructure\Persistence\Eloquent\Models\Property) {
        $selectedOptions = $location->filterOptions;
        $cityOpt = $selectedOptions->firstWhere('filter_id', 1);
        $city = $cityOpt ? ($cityOpt->name['az'] ?? $cityOpt->value) : '';
        // Find district/rayon from location filter options
        $districtOpt = $selectedOptions->where('filter_id', 1)->first(fn($opt) => $opt->id !== $cityOpt?->id);
        $region = $districtOpt ? ($districtOpt->name['az'] ?? $districtOpt->value) : '';
    } else {
        $address = $location->address ?? $location['address'] ?? '-';
        $city = $location->city->name ?? $location['city']['name'] ?? '-';
        $region = $location->district->name ?? $location['district']['name'] ?? '-';
    }
@endphp

<div class="map-detail">
    <div class="map">
        <iframe class="w-full h-[450px] rounded-2xl"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d194473.18588939894!2d49.8549596!3d40.394592499999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d6bd6211cf9%3A0x343f6b5e7ae56c6b!2sBaku!5e0!3m2!1sen!2saz!4v1759252822258!5m2!1sen!2saz"
            style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="map-info px-4">
        <ul class="map-info-list">
            <li class="flex map-flex">
                <p>{{ __('Address') }}</p>
                <p>{{ $address }}</p>
            </li>
            <li class="flex map-flex">
                <p>{{ __('City') }}</p>
                <p>{{ $city }}</p>
            </li>
            <li class="flex map-flex">
                <p>{{ __('State/Region') }}</p>
                <p>{{ $region }}</p>
            </li>
        </ul>
    </div>
</div>
