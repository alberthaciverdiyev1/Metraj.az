@forelse($requests as $req)
    @php
        $typeVal = $req->request_type->value;
        $isBuy = $typeVal === 'buy';
        $isRent = $typeVal === 'rent_monthly';
        $isDaily = $typeVal === 'rent_daily';
        $isRoommate = str_starts_with($typeVal, 'roommate');

        $typeLabel = $req->request_type->badgeLabel();

        $typeBadgeColor = match($typeVal) {
            'buy' => 'bg-emerald-600',
            'rent_monthly' => 'bg-[color:var(--primary)]',
            'rent_daily' => 'bg-amber-600',
            default => 'bg-purple-600',
        };

        $cityName = is_array($req->city?->name) ? ($req->city->name[app()->getLocale()] ?? $req->city->name['az'] ?? reset($req->city->name)) : ($req->city?->name ?? 'Bakı');
        $districtName = $req->district ? (is_array($req->district->name) ? ($req->district->name[app()->getLocale()] ?? $req->district->name['az'] ?? reset($req->district->name)) : $req->district->name) : null;
        $locationFull = $cityName . ($districtName ? ', ' . $districtName : '') . ($req->location_note ? ' (' . $req->location_note . ')' : '');

        $dateStr = $req->created_at
            ? ($req->created_at->diffInHours(now()) < 24 ? $req->created_at->diffForHumans() : $req->created_at->format('d.m.Y'))
            : '';

        $hasRealImage = !empty($req->first_image_url);
    @endphp

    <div onclick="window.location.href='{{ route('requests.show', $req->slug) }}'"
         class="cursor-pointer border border-[color:var(--border-color)] rounded-2xl overflow-hidden flex flex-col h-full group transition-all duration-300 relative bg-white hover:shadow-md">

        <!-- Top Image Banner (Real Image or KibrisKare Logo) -->
        <div class="relative overflow-hidden aspect-[4/3] sm:aspect-[5/3] md:aspect-[3/2] lg:aspect-[16/10] bg-gray-50 flex items-center justify-center border-b border-gray-100">
            @if($hasRealImage)
                <img src="{{ $req->first_image_url }}" loading="lazy" decoding="async"
                     alt="{{ $req->title }}"
                     class="card-image w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                     loading="lazy" />
            @else
                <div class="flex flex-col items-center justify-center select-none py-6 px-4">
                    <img src="{{ asset('images/kibriskarelogo1.png') }}" alt="KibrisKare.com" class="h-10 sm:h-12 w-auto object-contain transition-transform duration-500 group-hover:scale-105" />
                    <span class="text-[11px] font-semibold text-gray-500 tracking-wider mt-1.5">KibrisKare.com</span>
                </div>
            @endif

            <!-- Type Badge (Top Left) -->
            <span class="absolute top-2.5 left-2.5 {{ $typeBadgeColor }} text-white text-xs font-semibold px-2.5 py-1 rounded-full shadow-xs flex items-center gap-1 z-10">
                @if($isBuy)
                    <i class="fa-solid fa-house-circle-check text-[11px]"></i>
                @elseif($isRent)
                    <i class="fa-solid fa-house-circle-xmark text-[11px]"></i>
                @elseif($isDaily)
                    <i class="fa-solid fa-calendar-day text-[11px]"></i>
                @else
                    <i class="fa-solid fa-people-roof text-[11px]"></i>
                @endif
                <span>{{ $typeLabel }}</span>
            </span>

            <!-- Property Type / Gender Badge (Top Right) -->
            @if($req->property_type)
                <span class="absolute top-2.5 right-2.5 bg-white/95 text-gray-800 text-xs font-semibold px-2.5 py-1 rounded-full shadow-xs z-10">
                    {{ $req->property_type }}
                </span>
            @elseif($req->gender_preference && $req->gender_preference !== 'any')
                <span class="absolute top-2.5 right-2.5 bg-white/95 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full shadow-xs z-10">
                    {{ $req->gender_preference === 'female' ? __('requests.female_only') : __('requests.male_only') }}
                </span>
            @endif
        </div>

        <!-- Card Body -->
        <div class="p-3 sm:p-4 flex flex-col flex-1">
            <div class="flex flex-col gap-2 min-h-[100px] sm:min-h-[120px]">

                <!-- Title -->
                <h3 class="font-semibold text-[color:var(--text-color)] text-sm sm:text-base md:text-md hover:text-[color:var(--primary)] line-clamp-1 group-hover:line-clamp-none min-h-[20px] sm:min-h-[28px] overflow-hidden text-ellipsis">
                    <span>{{ $req->title }}</span>
                </h3>

                <!-- Chips Row -->
                <div class="min-h-[24px] sm:min-h-[28px] flex flex-wrap items-center gap-1">
                    @if($req->rooms)
                        <span class="bg-[#80807F] text-white flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-semibold">
                            <i class="fa-solid fa-door-open text-[11px] mr-1"></i>
                            <span>{{ $req->rooms }} {{ __('requests.rooms_suffix') }}</span>
                        </span>
                    @endif

                    @if($req->has_deed)
                        <span class="bg-emerald-600 text-white flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-semibold">
                            <i class="fa-solid fa-certificate text-[11px] mr-1"></i>
                            <span>{{ __('requests.deed_badge') }}</span>
                        </span>
                    @endif

                    @if($req->mortgage_eligible)
                        <span class="bg-blue-600 text-white flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-semibold">
                            <i class="fa-solid fa-building-columns text-[11px] mr-1"></i>
                            <span>{{ __('requests.mortgage_badge') }}</span>
                        </span>
                    @endif

                    @if($req->occupancy_type)
                        <span class="bg-gray-200 text-gray-700 flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-semibold">
                            {{ $req->occupancy_type }}
                        </span>
                    @endif

                    @if($req->bills_included)
                        <span class="bg-emerald-100 text-emerald-800 flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-semibold">
                            {{ __('requests.bills_badge') }}
                        </span>
                    @endif
                </div>

                <!-- Location Line -->
                <div class="flex items-center max-w-full text-xs sm:text-sm text-[color:var(--grey-text)] mt-auto">
                    <img class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 shrink-0" src="{{ asset('images/map-pin.svg') }}" alt="map" />
                    <span class="truncate group-hover:overflow-visible group-hover:whitespace-normal">
                        {{ $locationFull }}
                    </span>
                </div>

                <!-- Author & Date Line -->
                <div class="flex justify-between items-center text-xs sm:text-sm text-[color:var(--grey-text)] mt-auto mb-2">
                    <div class="flex items-center max-w-[70%] text-xs sm:text-sm text-[color:var(--grey-text)] truncate">
                        <i class="fa-regular fa-user mr-1 text-xs shrink-0"></i>
                        <span class="truncate group-hover:overflow-visible group-hover:whitespace-normal">
                            {{ $req->contact_name }}
                        </span>
                    </div>
                    <span class="ml-1 flex-shrink-0 text-xs text-gray-400">{{ $dateStr }}</span>
                </div>
            </div>

            <!-- Bottom Price & Details Indicator -->
            <div class="flex justify-between items-center mt-auto border-t border-[color:var(--border-color)] pt-3">
                <span class="text-[color:var(--primary)] font-semibold text-sm sm:text-base md:text-lg truncate">
                    {{ $req->formatted_budget }}
                </span>

                <span class="flex items-center gap-1 text-xs text-[color:var(--grey-text)] font-medium group-hover:text-[color:var(--primary)] transition-colors">
                    <span>{{ __('requests.details') }}</span>
                    <i class="bi bi-chevron-right text-[11px]"></i>
                </span>
            </div>
        </div>

    </div>
@empty
    <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-[color:var(--border-color)] p-8 max-w-md mx-auto">
        <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
            <i class="fa-solid fa-bullhorn"></i>
        </div>
        <h3 class="text-base font-semibold text-gray-900 mb-1">{{ __('requests.no_requests_found') }}</h3>
        <p class="text-xs text-gray-500 mb-5">
            {{ __('requests.no_requests_desc') }}
        </p>
        <a href="{{ route('requests.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs rounded-lg transition">
            <i class="bi bi-plus-circle"></i>
            <span>{{ __('requests.post_request_btn') }}</span>
        </a>
    </div>
@endforelse
