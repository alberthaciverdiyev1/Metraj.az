@forelse($listings as $listing)
    <div class="bg-white rounded-2xl border border-gray-200/90 overflow-hidden shadow-xs hover:shadow-md transition-all duration-300 flex flex-col h-full group">
        
        <!-- Image & Top Badges -->
        <div class="relative aspect-[4/3] w-full bg-gray-100 overflow-hidden">
            <a href="{{ route('roommates.show', $listing->slug) }}" class="block w-full h-full">
                <img src="{{ $listing->first_image_url }}" alt="{{ $listing->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                     loading="lazy" />
            </a>

            <!-- Listing Type Badge (Top Left) -->
            <div class="absolute top-3 left-3 z-10 flex flex-col gap-1.5">
                @if($listing->listing_type->value === 'have_room')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500 text-white shadow-xs">
                        <i class="fa-solid fa-door-open text-[10px]"></i>
                        <span>{{ __('Otaq verilir') }}</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-xs">
                        <i class="fa-solid fa-user-group text-[10px]"></i>
                        <span>{{ __('Otaq axtarır') }}</span>
                    </span>
                @endif

                <!-- Gender Badge -->
                @if($listing->gender_preference->value === 'female')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-pink-500 text-white shadow-xs">
                        <i class="fa-solid fa-venus text-[10px]"></i>
                        <span>{{ __('Yalnız Xanım') }}</span>
                    </span>
                @elseif($listing->gender_preference->value === 'male')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-600 text-white shadow-xs">
                        <i class="fa-solid fa-mars text-[10px]"></i>
                        <span>{{ __('Yalnız Bəy') }}</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-800/80 text-white shadow-xs">
                        <i class="fa-solid fa-users text-[10px]"></i>
                        <span>{{ __('Fərqi yoxdur') }}</span>
                    </span>
                @endif
            </div>

            <!-- Price Tag (Bottom Left) -->
            <div class="absolute bottom-3 left-3 z-10">
                <div class="bg-gray-900/85 backdrop-blur-xs text-white px-3 py-1.5 rounded-xl flex items-baseline gap-1 shadow-sm">
                    <span class="text-base font-bold text-[#f1913d]">{{ $listing->formatted_price }}</span>
                    <span class="text-[11px] text-gray-300 font-normal">/ {{ __('ay') }}</span>
                </div>
            </div>

            <!-- Bills Included badge (Bottom Right) -->
            @if($listing->bills_included)
                <div class="absolute bottom-3 right-3 z-10">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-xs">
                        <i class="bi bi-check2-circle"></i>
                        <span>{{ __('Kommunal daxil') }}</span>
                    </span>
                </div>
            @endif
        </div>

        <!-- Content -->
        <div class="p-4 flex flex-col flex-1">
            
            <!-- Location -->
            <div class="flex items-center text-xs text-gray-500 mb-1.5 gap-1.5">
                <i class="fa-solid fa-location-dot text-orange-500 text-xs"></i>
                <span class="font-medium text-gray-700">
                    @php
                        $cityName = is_array($listing->city?->name) ? ($listing->city->name[app()->getLocale()] ?? $listing->city->name['az'] ?? reset($listing->city->name)) : ($listing->city?->name ?? 'Bakı');
                        $districtName = $listing->district ? (is_array($listing->district->name) ? ($listing->district->name[app()->getLocale()] ?? $listing->district->name['az'] ?? reset($listing->district->name)) : $listing->district->name) : null;
                    @endphp
                    {{ $cityName }} @if($districtName) , {{ $districtName }} @endif
                </span>
                @if($listing->location_note)
                    <span class="text-gray-400">•</span>
                    <span class="truncate text-gray-500 max-w-[120px]">{{ $listing->location_note }}</span>
                @endif
            </div>

            <!-- Title -->
            <a href="{{ route('roommates.show', $listing->slug) }}" class="block mb-2">
                <h3 class="font-semibold text-sm sm:text-base text-gray-900 hover:text-orange-500 transition line-clamp-2 leading-snug">
                    {{ $listing->title }}
                </h3>
            </a>

            <!-- Quick Meta Attributes -->
            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 bg-gray-50 p-2.5 rounded-xl mb-3 mt-auto">
                <div class="flex items-center gap-1.5 truncate">
                    <i class="fa-solid fa-briefcase text-gray-400 text-xs"></i>
                    <span class="truncate">{{ $listing->occupation_preference?->label() ?? 'Fərqi yoxdur' }}</span>
                </div>
                <div class="flex items-center gap-1.5 truncate">
                    <i class="fa-solid fa-ban-smoking {{ $listing->smoker_allowed ? 'text-gray-400' : 'text-rose-400' }} text-xs"></i>
                    <span class="truncate">{{ $listing->smoker_allowed ? __('Siqaret olar') : __('Siqaret olmaz') }}</span>
                </div>
            </div>

            <!-- Contact & View Footer -->
            <div class="flex items-center justify-between pt-3 border-t border-gray-100 mt-auto gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-semibold text-xs">
                        {{ mb_strtoupper(mb_substr($listing->contact_name, 0, 1)) }}
                    </div>
                    <div class="text-xs font-semibold text-gray-800 truncate max-w-[90px]">
                        {{ $listing->contact_name }}
                    </div>
                </div>

                <div class="flex items-center gap-1.5">
                    @if($listing->contact_whatsapp)
                        @php
                            $wa = preg_replace('/[^0-9]/', '', $listing->contact_whatsapp);
                        @endphp
                        <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Salam, Metraj.az saytındakı otaq yoldaşı elanınızla bağlı yazıram: ' . $listing->title) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="w-8 h-8 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition"
                           title="WhatsApp ilə yaz">
                            <i class="bi bi-whatsapp text-sm"></i>
                        </a>
                    @endif

                    <a href="tel:{{ $listing->contact_phone }}"
                       class="w-8 h-8 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-600 flex items-center justify-center transition"
                       title="Zəng et">
                        <i class="bi bi-telephone text-sm"></i>
                    </a>

                    <a href="{{ route('roommates.show', $listing->slug) }}"
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
            <i class="fa-solid fa-people-roof"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1.5">{{ __('Heç bir otaq yoldaşı elanı tapılmadı') }}</h3>
        <p class="text-xs sm:text-sm text-gray-500 mb-6 max-w-sm mx-auto">
            {{ __('Axtarış parametrlərini dəyişdirərək yenidən yoxlaya və ya ilk elanı siz yerləşdirə bilərsiniz.') }}
        </p>
        <a href="{{ route('roommates.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#f1913d] hover:bg-[#e07f2c] text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs transition">
            <i class="bi bi-plus-circle"></i>
            <span>{{ __('Otaq Yoldaşı Elanı Ver') }}</span>
        </a>
    </div>
@endforelse
