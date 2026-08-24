@forelse($requests as $req)
    <div class="bg-white rounded-2xl border border-gray-200/90 overflow-hidden shadow-xs hover:shadow-md transition-all duration-300 flex flex-col h-full group">
        
        <!-- Top Badges & Budget Header -->
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col justify-between bg-gray-50/50">
            <div class="flex items-center justify-between gap-2 mb-3">
                
                <!-- Request Type Badge -->
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $req->request_type->badgeClass() }} shadow-xs">
                    @if($req->request_type->value === 'buy')
                        <i class="fa-solid fa-cart-shopping text-[10px]"></i>
                    @elseif($req->request_type->value === 'rent_monthly')
                        <i class="fa-solid fa-key text-[10px]"></i>
                    @elseif($req->request_type->value === 'rent_daily')
                        <i class="fa-solid fa-calendar-day text-[10px]"></i>
                    @else
                        <i class="fa-solid fa-people-roof text-[10px]"></i>
                    @endif
                    <span>{{ $req->request_type->badgeLabel() }}</span>
                </span>

                <!-- Property Type or Gender Badge -->
                @if($req->property_type)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-white border border-gray-200 text-gray-700 shadow-2xs">
                        {{ $req->property_type }}
                    </span>
                @elseif($req->gender_preference)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-pink-50 border border-pink-200 text-pink-700 shadow-2xs">
                        {{ $req->gender_preference === 'female' ? 'Yalnız Xanım' : ($req->gender_preference === 'male' ? 'Yalnız Bəy' : 'Hamı') }}
                    </span>
                @endif
            </div>

            <!-- Budget Box -->
            <div class="flex items-baseline justify-between gap-2">
                <div>
                    <span class="text-[11px] font-medium text-gray-500 block">{{ __('Büdcə') }}</span>
                    <span class="text-lg sm:text-xl font-extrabold text-[#f1913d]">{{ $req->formatted_budget }}</span>
                </div>

                @if($req->bills_included)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i class="bi bi-check2"></i> {{ __('Kommunal daxil') }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-4 sm:p-5 flex flex-col flex-1">
            
            <!-- Location -->
            <div class="flex items-center text-xs text-gray-500 mb-2 gap-1.5">
                <i class="fa-solid fa-location-dot text-orange-500 text-xs"></i>
                <span class="font-medium text-gray-800">
                    @php
                        $cityName = is_array($req->city?->name) ? ($req->city->name[app()->getLocale()] ?? $req->city->name['az'] ?? reset($req->city->name)) : ($req->city?->name ?? 'Bakı');
                        $districtName = $req->district ? (is_array($req->district->name) ? ($req->district->name[app()->getLocale()] ?? $req->district->name['az'] ?? reset($req->district->name)) : $req->district->name) : null;
                    @endphp
                    {{ $cityName }} @if($districtName) , {{ $districtName }} @endif
                </span>
                @if($req->location_note)
                    <span class="text-gray-300">•</span>
                    <span class="truncate text-gray-500 max-w-[130px]">{{ $req->location_note }}</span>
                @endif
            </div>

            <!-- Title -->
            <a href="{{ route('requests.show', $req->slug) }}" class="block mb-2">
                <h3 class="font-bold text-sm sm:text-base text-gray-900 hover:text-orange-500 transition line-clamp-2 leading-snug">
                    {{ $req->title }}
                </h3>
            </a>

            <!-- Description excerpt -->
            <p class="text-xs text-gray-500 line-clamp-2 mb-4">
                {{ $req->description }}
            </p>

            <!-- Tags / Meta Details Grid -->
            <div class="flex flex-wrap gap-1.5 mb-4 mt-auto">
                @if($req->rooms)
                    <span class="px-2 py-1 bg-gray-100 rounded-lg text-[11px] font-medium text-gray-700">
                        {{ $req->rooms }} {{ __('otaqlı') }}
                    </span>
                @endif

                @if($req->has_deed)
                    <span class="px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[11px] font-bold">
                        <i class="bi bi-file-earmark-check"></i> {{ __('Kupçalı') }}
                    </span>
                @endif

                @if($req->mortgage_eligible)
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[11px] font-bold">
                        <i class="bi bi-bank"></i> {{ __('İpoteka') }}
                    </span>
                @endif

                @if($req->occupancy_type)
                    <span class="px-2 py-1 bg-orange-50 text-orange-700 border border-orange-200 rounded-lg text-[11px] font-medium">
                        {{ $req->occupancy_type }}
                    </span>
                @endif
            </div>

            <!-- Contact & View Footer -->
            <div class="flex items-center justify-between pt-3 border-t border-gray-100 mt-auto gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xs">
                        {{ mb_strtoupper(mb_substr($req->contact_name, 0, 1)) }}
                    </div>
                    <div class="text-xs font-semibold text-gray-800 truncate max-w-[95px]">
                        {{ $req->contact_name }}
                    </div>
                </div>

                <div class="flex items-center gap-1.5">
                    @if($req->contact_whatsapp)
                        @php
                            $wa = preg_replace('/[^0-9]/', '', $req->contact_whatsapp);
                        @endphp
                        <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Salam, Metraj.az saytındakı tələb elanınızla bağlı yazıram: ' . $req->title) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="w-8 h-8 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition"
                           title="WhatsApp ilə təklif göndər">
                            <i class="bi bi-whatsapp text-sm"></i>
                        </a>
                    @endif

                    <a href="tel:{{ $req->contact_phone }}"
                       class="w-8 h-8 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-600 flex items-center justify-center transition"
                       title="Zəng et">
                        <i class="bi bi-telephone text-sm"></i>
                    </a>

                    <a href="{{ route('requests.show', $req->slug) }}"
                       class="px-3 py-1.5 rounded-xl bg-gray-900 hover:bg-orange-500 text-white text-xs font-semibold transition flex items-center gap-1">
                        <span>{{ __('Bax') }}</span>
                        <i class="bi bi-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
@empty
    <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-gray-200/90 shadow-xs max-w-lg mx-auto px-6">
        <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="fa-solid fa-handshake-angle"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1.5">{{ __('Heç bir tələb elanı tapılmadı') }}</h3>
        <p class="text-xs sm:text-sm text-gray-500 mb-6 max-w-sm mx-auto">
            {{ __('Axtarış parametrlərini dəyişdirərək yenidən yoxlaya və ya ilk tələb elanını siz yerləşdirə bilərsiniz.') }}
        </p>
        <a href="{{ route('requests.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#f1913d] hover:bg-[#e07f2c] text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs transition">
            <i class="bi bi-plus-circle"></i>
            <span>{{ __('Tələb Elanı Yerləşdir') }}</span>
        </a>
    </div>
@endforelse
