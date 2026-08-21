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
        ? ($property->created_at->isToday() ? 'Bugün ' . $property->created_at->format('H:i') : $property->created_at->format('d.m.Y'))
        : '';
        
    $formattedPrice = number_format($property->price, 0, '', ' ');

    // Dinamik olaraq deal_type və property_type filtrlərini əldə edirik
    $selectedOptions = $property->filterOptions;
    $dealTypeOpt = $selectedOptions->firstWhere('filter_id', 2);
    $propertyTypeOpt = $selectedOptions->firstWhere('filter_id', 3);

    $isSale = $dealTypeOpt ? ($dealTypeOpt->value === 'sale') : false;
    $isRent = $dealTypeOpt ? (str_contains($dealTypeOpt->value, 'rent')) : false;

    $buildingTypeLabel = $propertyTypeOpt ? ($propertyTypeOpt->name['az'] ?? $propertyTypeOpt->value) : '';
    
    // Təmir növü yoxlanışı (Əgər təmirli seçilibsə)
    $repairOpt = $selectedOptions->firstWhere('filter_id', 5);
    $isRepaired = $repairOpt ? (str_contains(strtolower($repairOpt->name['az'] ?? ''), 'təmirli') || strtolower($repairOpt->value) === 'repaired') : false;
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
          class="absolute bottom-1 sm:bottom-3 right-2 sm:right-4 text-white font-semibold text-md bg-white px-2 py-1 rounded-full cursor-pointer">
        <i class="fa-regular fa-heart text-red-500"></i>
    </span>

    <button onclick="event.stopPropagation()"
            class="absolute right-2 top-2 bg-[#494949] bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
      <img src="/images/icon.svg" class="w-4 h-4 sm:w-5 sm:h-5" />
    </button>
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
                <span class="hidden sm:block text-xs font-semibold ml-1">{{ __('Kirayə') }}</span>
            </span>
        @elseif($isSale)
            <span class="bg-[#80807F] text-white flex items-center justify-center rounded-full w-7 h-7 sm:w-auto sm:h-auto px-2 py-1 sm:flex-row sm:rounded-full">
                <i class="fa-solid fa-house-circle-check text-[12px]"></i>
                <span class="hidden sm:block text-xs font-semibold ml-1">{{ __('Satış') }}</span>
            </span>
        @endif

        @if($isRepaired)
            <span class="bg-green-600 text-white flex items-center justify-center rounded-full w-7 h-7 sm:w-auto sm:h-auto px-2 py-1 sm:flex-row sm:rounded-full">
                <i class="fa-solid fa-hammer text-[12px]"></i>
                <span class="hidden sm:block text-xs font-semibold ml-1">{{ __('Təmirli') }}</span>
            </span>
        @endif
      </div>

      <div class="flex items-center max-w-full text-xs sm:text-sm text-[color:var(--grey-text)] mt-auto">
        <img class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1" src="/images/map-pin.svg" alt="map" />
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
      <span class="text-[color:var(--primary)] font-bold text-sm sm:text-base md:text-lg">
        {{ $formattedPrice }} AZN
      </span>
      <button onclick="event.stopPropagation(); toggleCompare(this, {{ $property->id }})"
              class="flex items-center gap-1 text-xs sm:text-sm text-[#2C2E33] hover:text-[color:var(--primary)] transition-colors">
        <img class="w-4 h-4 sm:w-5 sm:h-5" src="/images/compare.svg" />
        <span class="hidden xs:inline">{{ __('Müqayisə') }}</span>
      </button>
    </div>
  </div>
</div>
