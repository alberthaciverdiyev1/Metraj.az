@forelse($requests as $req)
    @php
        $typeVal = $req->request_type->value;
        $headerBg = match($typeVal) {
            'buy' => 'bg-emerald-50/40 border-emerald-100/80',
            'rent_monthly' => 'bg-blue-50/40 border-blue-100/80',
            'rent_daily' => 'bg-amber-50/40 border-amber-100/80',
            default => 'bg-purple-50/40 border-purple-100/80',
        };
        $accentColor = match($typeVal) {
            'buy' => 'text-emerald-700',
            'rent_monthly' => 'text-blue-700',
            'rent_daily' => 'text-amber-700',
            default => 'text-purple-700',
        };
    @endphp

    <div class="bg-white rounded-3xl border border-gray-200/90 overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col h-full group hover:-translate-y-1">
        
        <!-- Top Section with Type & Budget -->
        <div class="p-5 border-b {{ $headerBg }} flex flex-col justify-between transition-colors">
            
            <!-- Type Badge + Meta -->
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold {{ $req->request_type->badgeClass() }} shadow-xs">
                    @if($typeVal === 'buy')
                        <i class="fa-solid fa-cart-shopping text-[10px]"></i>
                    @elseif($typeVal === 'rent_monthly')
                        <i class="fa-solid fa-key text-[10px]"></i>
                    @elseif($typeVal === 'rent_daily')
                        <i class="fa-solid fa-calendar-day text-[10px]"></i>
                    @else
                        <i class="fa-solid fa-people-roof text-[10px]"></i>
                    @endif
                    <span>{{ $req->request_type->badgeLabel() }}</span>
                </span>

                @if($req->property_type)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-white border border-gray-200 text-gray-800 shadow-2xs">
                        {{ $req->property_type }}
                    </span>
                @elseif($req->gender_preference)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-white border border-pink-200 text-pink-700 shadow-2xs">
                        {{ $req->gender_preference === 'female' ? 'Yalnız Xanım' : ($req->gender_preference === 'male' ? 'Yalnız Bəy' : 'Hamı') }}
                    </span>
                @else
                    <span class="text-[11px] font-medium text-gray-400 flex items-center gap-1">
                        <i class="bi bi-clock"></i> {{ $req->created_at ? $req->created_at->diffForHumans() : '' }}
                    </span>
                @endif
            </div>

            <!-- Budget Box -->
            <div class="flex items-end justify-between gap-2 mt-1">
                <div>
                    <span class="text-[11px] uppercase tracking-wider font-bold text-gray-400 block mb-0.5">{{ __('Maks. Büdcə') }}</span>
                    <div class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">
                        <span class="text-[#f1913d]">{{ $req->formatted_budget }}</span>
                    </div>
                </div>

                @if($req->bills_included)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100/70 text-emerald-800 border border-emerald-200">
                        <i class="bi bi-check2-circle"></i> {{ __('Kommunal daxil') }}
                    </span>
                @endif
            </div>

        </div>

        <!-- Main Body -->
        <div class="p-5 flex flex-col flex-1">
            
            <!-- Location -->
            <div class="flex items-center text-xs text-gray-500 mb-2.5 gap-1.5">
                <span class="w-5 h-5 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 text-[11px]">
                    <i class="fa-solid fa-location-dot"></i>
                </span>
                <span class="font-bold text-gray-800 truncate">
                    @php
                        $cityName = is_array($req->city?->name) ? ($req->city->name[app()->getLocale()] ?? $req->city->name['az'] ?? reset($req->city->name)) : ($req->city?->name ?? 'Bakı');
                        $districtName = $req->district ? (is_array($req->district->name) ? ($req->district->name[app()->getLocale()] ?? $req->district->name['az'] ?? reset($req->district->name)) : $req->district->name) : null;
                    @endphp
                    {{ $cityName }}@if($districtName), {{ $districtName }}@endif
                </span>
                @if($req->location_note)
                    <span class="text-gray-300">•</span>
                    <span class="truncate text-gray-500 font-medium max-w-[130px]">{{ $req->location_note }}</span>
                @endif
            </div>

            <!-- Title -->
            <a href="{{ route('requests.show', $req->slug) }}" class="block mb-2.5 group-hover:text-orange-500 transition">
                <h3 class="font-extrabold text-sm sm:text-base text-gray-900 line-clamp-2 leading-snug">
                    {{ $req->title }}
                </h3>
            </a>

            <!-- Description -->
            <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed mb-4">
                {{ $req->description }}
            </p>

            <!-- Feature Tags Chips -->
            <div class="flex flex-wrap gap-1.5 mb-5 mt-auto">
                @if($req->rooms)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 rounded-xl text-xs font-semibold text-gray-700">
                        <i class="fa-solid fa-door-open text-[10px] text-gray-400"></i>
                        <span>{{ $req->rooms }} {{ __('otaqlı') }}</span>
                    </span>
                @endif

                @if($req->has_deed)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-xl text-xs font-bold">
                        <i class="fa-solid fa-certificate text-[10px]"></i>
                        <span>{{ __('Kupçalı') }}</span>
                    </span>
                @endif

                @if($req->mortgage_eligible)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200/80 rounded-xl text-xs font-bold">
                        <i class="fa-solid fa-building-columns text-[10px]"></i>
                        <span>{{ __('İpoteka') }}</span>
                    </span>
                @endif

                @if($req->occupancy_type)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-50 text-orange-700 border border-orange-200/80 rounded-xl text-xs font-semibold">
                        <i class="fa-solid fa-users text-[10px]"></i>
                        <span>{{ $req->occupancy_type }}</span>
                    </span>
                @endif
            </div>

            <!-- Footer: Author & Fast Contact Actions -->
            <div class="flex items-center justify-between pt-3.5 border-t border-gray-100 mt-auto gap-2">
                
                <!-- User Profile Pill -->
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-extrabold text-xs shrink-0 shadow-2xs">
                        {{ mb_strtoupper(mb_substr($req->contact_name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-gray-900 truncate">{{ $req->contact_name }}</div>
                        <div class="text-[10px] text-gray-400 truncate">{{ __('Axtaran şəxs') }}</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-1.5 shrink-0">
                    @if($req->contact_whatsapp)
                        @php
                            $wa = preg_replace('/[^0-9]/', '', $req->contact_whatsapp);
                        @endphp
                        <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Salam, Metraj.az saytındakı tələb elanınızla bağlı yazıram: ' . $req->title) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="h-8 px-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white border border-emerald-200 hover:border-emerald-500 flex items-center gap-1 text-xs font-bold transition shadow-2xs"
                           title="WhatsApp ilə təklif göndər">
                            <i class="bi bi-whatsapp text-sm"></i>
                            <span class="hidden sm:inline text-[11px]">{{ __('Təklif et') }}</span>
                        </a>
                    @endif

                    <a href="tel:{{ $req->contact_phone }}"
                       class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition shadow-2xs"
                       title="Zəng et">
                        <i class="bi bi-telephone text-xs"></i>
                    </a>

                    <a href="{{ route('requests.show', $req->slug) }}"
                       class="h-8 px-3 rounded-xl bg-gray-900 hover:bg-[#f1913d] text-white text-xs font-bold transition flex items-center gap-1 shadow-2xs">
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
