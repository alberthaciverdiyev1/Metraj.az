@forelse($requests as $req)
    @php
        $typeVal = $req->request_type->value;
        $typeLabel = match($typeVal) {
            'buy' => 'Almaq istəyir',
            'rent_monthly' => 'Kirayə axtarır',
            'rent_daily' => 'Günlük axtarır',
            'roommate_have' => 'Otaq verir',
            'roommate_need' => 'Otaq axtarır',
            default => 'Axtarır',
        };
        $typeBadgeBg = match($typeVal) {
            'buy' => 'bg-emerald-600 text-white',
            'rent_monthly' => 'bg-blue-600 text-white',
            'rent_daily' => 'bg-amber-600 text-white',
            default => 'bg-purple-600 text-white',
        };

        $cityName = is_array($req->city?->name) ? ($req->city->name[app()->getLocale()] ?? $req->city->name['az'] ?? reset($req->city->name)) : ($req->city?->name ?? 'Bakı');
        $districtName = $req->district ? (is_array($req->district->name) ? ($req->district->name[app()->getLocale()] ?? $req->district->name['az'] ?? reset($req->district->name)) : $req->district->name) : null;
        $locationFull = $cityName . ($districtName ? ', ' . $districtName : '') . ($req->location_note ? ' (' . $req->location_note . ')' : '');

        $dateStr = $req->created_at ? ($req->created_at->isToday() ? 'Bugün ' . $req->created_at->format('H:i') : $req->created_at->format('d.m.Y')) : '';
    @endphp

    <div class="bg-white rounded-2xl border border-gray-200 hover:border-gray-300 p-5 flex flex-col justify-between h-full transition shadow-xs hover:shadow-sm">
        
        <div>
            <!-- Top meta row -->
            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $typeBadgeBg }}">
                        {{ $typeLabel }}
                    </span>
                    @if($req->property_type)
                        <span class="text-xs font-medium px-2 py-0.5 rounded-md bg-gray-100 text-gray-700">
                            {{ $req->property_type }}
                        </span>
                    @endif
                </div>
                <span class="text-xs text-gray-400 font-medium">{{ $dateStr }}</span>
            </div>

            <!-- Price & Title -->
            <div class="mb-3">
                <div class="text-xl font-bold text-gray-900 mb-1">
                    <span class="text-[#f1913d]">{{ $req->formatted_budget }}</span>
                    @if($req->bills_included)
                        <span class="text-xs font-normal text-emerald-600 ml-1.5 font-medium">({{ __('Kommunal daxil') }})</span>
                    @endif
                </div>
                <a href="{{ route('requests.show', $req->slug) }}" class="block">
                    <h3 class="font-semibold text-gray-900 hover:text-[#f1913d] text-base leading-snug line-clamp-2 transition">
                        {{ $req->title }}
                    </h3>
                </a>
            </div>

            <!-- Specs / Parameters line -->
            <div class="flex flex-wrap items-center gap-1.5 text-xs text-gray-600 mb-3">
                @if($req->rooms)
                    <span class="bg-gray-50 border border-gray-200 px-2 py-0.5 rounded-md font-medium">{{ $req->rooms }} {{ __('otaqlı') }}</span>
                @endif
                @if($req->has_deed)
                    <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-2 py-0.5 rounded-md font-medium">{{ __('Kupçalı') }}</span>
                @endif
                @if($req->mortgage_eligible)
                    <span class="bg-blue-50 border border-blue-200 text-blue-700 px-2 py-0.5 rounded-md font-medium">{{ __('İpotekaya yararlı') }}</span>
                @endif
                @if($req->occupancy_type)
                    <span class="bg-gray-50 border border-gray-200 px-2 py-0.5 rounded-md font-medium">{{ $req->occupancy_type }}</span>
                @endif
                @if($req->gender_preference && $req->gender_preference !== 'any')
                    <span class="bg-purple-50 border border-purple-200 text-purple-700 px-2 py-0.5 rounded-md font-medium">{{ $req->gender_preference === 'female' ? __('Yalnız Xanım') : __('Yalnız Bəy') }}</span>
                @endif
            </div>

            <!-- Description -->
            <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed mb-4">
                {{ $req->description }}
            </p>

            <!-- Location -->
            <div class="flex items-center text-xs text-gray-500 gap-1.5 mb-4">
                <i class="fa-solid fa-location-dot text-gray-400"></i>
                <span class="truncate">{{ $locationFull }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-7 h-7 rounded-full bg-gray-100 text-gray-700 flex items-center justify-center font-bold text-xs shrink-0">
                    {{ mb_strtoupper(mb_substr($req->contact_name, 0, 1)) }}
                </div>
                <span class="text-xs font-medium text-gray-800 truncate">{{ $req->contact_name }}</span>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if($req->contact_whatsapp)
                    @php
                        $wa = preg_replace('/[^0-9]/', '', $req->contact_whatsapp);
                    @endphp
                    <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Salam, Metraj.az saytında yerləşdirdiyiniz tələb elanınızla bağlı yazıram: ' . $req->title) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 hover:border-emerald-600 text-xs font-semibold transition"
                       title="WhatsApp ilə təklif göndər">
                        <i class="bi bi-whatsapp"></i>
                        <span>{{ __('Təklif et') }}</span>
                    </a>
                @endif

                <a href="tel:{{ $req->contact_phone }}"
                   class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 flex items-center justify-center transition"
                   title="Zəng et">
                    <i class="bi bi-telephone text-xs"></i>
                </a>

                <a href="{{ route('requests.show', $req->slug) }}"
                   class="w-8 h-8 rounded-lg bg-gray-900 hover:bg-[#f1913d] text-white flex items-center justify-center transition"
                   title="Ətraflı bax">
                    <i class="bi bi-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

    </div>
@empty
    <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-gray-200 p-8 max-w-md mx-auto">
        <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
            <i class="fa-solid fa-bullhorn"></i>
        </div>
        <h3 class="text-base font-semibold text-gray-900 mb-1">{{ __('Heç bir tələb elanı tapılmadı') }}</h3>
        <p class="text-xs text-gray-500 mb-5">
            {{ __('Axtarış parametrlərini dəyişdirə və ya ilk tələb elanını siz yerləşdirə bilərsiniz.') }}
        </p>
        <a href="{{ route('requests.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#f1913d] hover:bg-[#e07f2c] text-white font-semibold text-xs rounded-lg transition">
            <i class="bi bi-plus-circle"></i>
            <span>{{ __('Tələb Elanı Yerləşdir') }}</span>
        </a>
    </div>
@endforelse
