@props(['property'])

@php
    $firstImage = $property->images->sortBy('sort_order')->first()?->url
        ?? 'https://static.vecteezy.com/system/resources/previews/004/640/986/non_2x/tower-building-illustration-isolated-on-white-background-vector.jpg';
    $allImagePaths = $property->images->sortBy('sort_order')->pluck('url')->toArray();
    if (empty($allImagePaths)) {
        $allImagePaths = [$firstImage];
    }
    $hasMultiple = count($allImagePaths) > 1;

    $title = $property->title;
    $fullTitle = $property->title;

    $date = $property->created_at
        ? ($property->created_at->isToday() ? __('listing.today') . ' ' . $property->created_at->format('H:i') : $property->created_at->format('d.m.Y'))
        : '';

    $formattedPrice = number_format($property->price, 0, '', ' ');

    // Dinamik olaraq deal_type və property_type filtrlərini əldə edirik
    $selectedOptions = $property->filterOptions ?? collect();
    $dealTypeOpt = $selectedOptions->first(fn($o) => in_array($o->value, ['sale', 'rent_monthly', 'rent_daily']) || ($o->filter && $o->filter->key === 'deal_type'));
    $propertyTypeOpt = $selectedOptions->first(fn($o) => in_array($o->value, ['apartment', 'house', 'office', 'garage', 'land', 'commercial']) || ($o->filter && $o->filter->key === 'property_type'));

    $isSale = $dealTypeOpt ? ($dealTypeOpt->value === 'sale') : false;
    $isRent = $dealTypeOpt ? (str_contains($dealTypeOpt->value, 'rent')) : false;

    $buildingTypeLabel = $propertyTypeOpt ? $propertyTypeOpt->localized_name : '';

    // Təmir növü yoxlanışı (Əgər təmirli seçilibsə)
    $repairOpt = $selectedOptions->first(fn($o) => in_array($o->value, ['repaired', 'unrepaired', 'tadilatli']) || ($o->filter && $o->filter->key === 'repair'));
    $isRepaired = $repairOpt ? (str_contains(strtolower($repairOpt->name['az'] ?? $repairOpt->name['tr'] ?? ''), 'təmirli') || strtolower($repairOpt->value) === 'repaired' || strtolower($repairOpt->value) === 'tadilatli') : false;
@endphp

<div onclick="window.location.href='/elan/{{ $property->slug }}'"
     data-property-id="{{ $property->id }}"
     class="cursor-pointer border border-[color:var(--border-color)] rounded-2xl overflow-hidden flex flex-col h-full group transition-all duration-300 relative">

  <div class="relative overflow-hidden aspect-[4/3] sm:aspect-[5/3] md:aspect-[3/2] lg:aspect-[16/10]"
       data-images='@json($allImagePaths)'
       data-current="0">
    <img src="{{ $allImagePaths[0] }}"
         alt="{{ $title }}"
         class="card-image w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />

    @if($hasMultiple)
        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1 z-10">
            @foreach($allImagePaths as $i => $img)
                <span class="block w-1.5 h-1.5 rounded-full {{ $i === 0 ? 'bg-white' : 'bg-white/50' }}"></span>
            @endforeach
        </div>

        <button onclick="event.stopPropagation(); prevImage(this)" class="absolute left-1 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white w-7 h-7 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10">
            <i class="fas fa-chevron-left text-xs"></i>
        </button>
        <button onclick="event.stopPropagation(); nextImage(this)" class="absolute right-1 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white w-7 h-7 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10">
            <i class="fas fa-chevron-right text-xs"></i>
        </button>
    @endif

    @if($property->is_vip || $property->is_featured)
        <span class="absolute top-1 sm:top-3 right-2 sm:right-4 text-[color:var(--primary)] font-semibold text-sm sm:text-md bg-white px-2 py-1 rounded-full">
            <i class="fa-solid fa-crown"></i>
        </span>
    @endif

    <span onclick="event.stopPropagation(); toggleFavorite(this, {{ $property->id }})"
          data-fav-btn="{{ $property->id }}"
          class="favorite-btn absolute bottom-1 sm:bottom-3 right-2 sm:right-4 font-semibold text-md bg-white shadow-sm hover:scale-110 transition-transform px-2 py-1 rounded-full cursor-pointer">
        <i class="fa-regular fa-heart text-red-500"></i>
    </span>

  </div>

  <div class="p-3 sm:p-4 flex flex-col flex-1">
    <div class="flex flex-col gap-2 min-h-[100px] sm:min-h-[120px]">

      <h3 class="font-semibold sm:font-semibold text-[color:var(--text-color)] text-sm sm:text-base md:text-md
          hover:text-[color:var(--primary)]
          line-clamp-1 group-hover:line-clamp-none
          min-h-[20px] sm:min-h-[28px] overflow-hidden text-ellipsis">
          <span>{{ $fullTitle }}</span>
      </h3>

      <div class="min-h-[24px] sm:min-h-[28px] flex flex-wrap items-center gap-1">
        @if($isRent)
            <span class="bg-[color:var(--primary)] text-white flex items-center justify-center rounded-full w-7 h-7 sm:w-auto sm:h-auto px-2 py-1 sm:flex-row sm:rounded-full">
                <i class="fa-solid fa-house-circle-xmark text-[12px]"></i>
                <span class="hidden sm:block text-xs font-semibold ml-1">{{ __('listing.rent_badge') }}</span>
            </span>
        @else
            <span class="bg-[#80807F] text-white flex items-center justify-center rounded-full w-7 h-7 sm:w-auto sm:h-auto px-2 py-1 sm:flex-row sm:rounded-full">
                <i class="fa-solid fa-house-circle-check text-[12px]"></i>
                <span class="hidden sm:block text-xs font-semibold ml-1">{{ __('listing.sale_badge') }}</span>
            </span>
        @endif

        @if($isRepaired)
            <span class="bg-[#80807F] text-white flex items-center justify-center rounded-full w-7 h-7 sm:w-auto sm:h-auto px-2 py-1 sm:flex-row sm:rounded-full">
                <i class="fa-solid fa-hammer text-[12px]"></i>
                <span class="hidden sm:block text-xs font-semibold ml-1">{{ __('listing.repaired') }}</span>
            </span>
        @endif
      </div>

      <div class="flex items-center max-w-full text-xs sm:text-sm text-[color:var(--grey-text)] mt-auto">
        <img class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1" src="{{ asset('images/map-pin.svg') }}" alt="map" />
        <span class="truncate group-hover:overflow-visible group-hover:whitespace-normal">
          {{ $property->address }}
        </span>
      </div>

      <div class="flex justify-between items-center text-xs sm:text-sm text-[color:var(--grey-text)] mt-auto mb-3">
        <div class="flex items-center max-w-[70%] text-xs sm:text-sm text-[color:var(--grey-text)]">
          <i class="fa-solid fa-city"></i>
          <span class="truncate ml-1 group-hover:overflow-visible group-hover:whitespace-normal">
            {{ $buildingTypeLabel }}
          </span>
        </div>
        <span class="ml-1 flex-shrink-0">{{ $date }}</span>
      </div>
    </div>

    <div class="flex justify-between items-center mt-auto border-t border-[color:var(--border-color)] pt-3">
      @php
          $displayPrice = app(\App\Modules\Property\Services\PropertyPricePresenter::class)->display($property);
      @endphp
      <span class="text-orange-500 font-semibold text-sm sm:text-base md:text-lg">
        {{ $displayPrice['symbol'] }} {{ $displayPrice['formatted'] }}
      </span>
      <button type="button" onclick="event.stopPropagation(); toggleCompare(this, {{ $property->id }})"
              data-compare-btn="{{ $property->id }}"
              class="compare-btn flex items-center gap-1.5 text-xs sm:text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors py-1 px-2 rounded-lg hover:bg-orange-50 cursor-pointer">
        <i class="bi bi-arrow-left-right text-sm sm:text-base text-orange-500"></i>
        <span class="compare-btn-text hidden xs:inline">{{ __('listing.compare') }}</span>
      </button>
    </div>
  </div>
</div>
