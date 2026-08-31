<!-- Main Info Card -->
<div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-gray-100/90 flex flex-col justify-between space-y-5">

    <!-- Price Header -->
    <div class="space-y-1">
        <div class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight flex items-baseline gap-1.5">
            <span>{{ $displayPrice['formatted'] }} {{ $displayPrice['currency'] }}</span>
            @if($isRent)
                <span class="text-sm font-medium text-gray-500">{{ __('property.per_month') }}</span>
            @endif
        </div>
        @if($pricePerM2)
            <div class="text-sm font-medium text-gray-500">
                {{ number_format($pricePerM2, 0, '.', ' ') }} {{ $displayPrice['currency'] }}/m²
            </div>
        @endif
    </div>

    <!-- Divider -->
    <div class="border-t border-gray-100 my-1"></div>

    <!-- Contact Info -->
    @if($isAgentOrAgency)
        <!-- Realtor / Agency Layout -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest">{{ $agentRole }}</h3>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-orange-50 text-orange-600 border border-orange-200">
                    <i class="bi bi-patch-check-fill text-orange-500"></i>
                    {{ __('property.verified') }}
                </span>
            </div>

            <div class="flex items-center gap-4">
                @if(!empty($agentAvatar))
                    <img src="{{ $agentAvatar }}" alt="{{ $agentName }}"
                         class="w-16 h-16 rounded-2xl object-cover border border-gray-100 shadow-sm">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl border border-orange-100 shadow-sm">
                        <i class="bi bi-person-fill"></i>
                    </div>
                @endif
                <div class="space-y-0.5">
                    <h4 class="text-base font-bold text-gray-900 leading-tight">{{ $agentName }}</h4>
                    <p class="text-xs text-gray-500">{{ $agentRole }}</p>
                </div>
            </div>

            @if($hasContact)
                <div class="pt-3 border-t border-gray-100 space-y-2.5">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">{{ __('property.phone') }}:</span>
                        <span class="js-phone-display font-semibold text-gray-900 filter blur-xs select-none tracking-wider">+90 533 ··· ·· ··</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2.5 pt-1">
                    <button type="button"
                        onclick="revealPropertyPhone(event, this, {{ $property->id }}, 'call')"
                        class="js-btn-show-phone w-full flex items-center justify-center gap-2 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl shadow-md transition duration-200 transform active:scale-98 text-sm cursor-pointer">
                        <i class="bi bi-eye text-xs"></i>
                        <span class="js-phone-btn-text">{{ __('property.show_phone') }}</span>
                    </button>

                    <a href="javascript:void(0)"
                       onclick="revealPropertyPhone(event, this, {{ $property->id }}, 'whatsapp')"
                       class="js-btn-whatsapp w-full flex items-center justify-center gap-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl shadow-md transition duration-200 transform active:scale-98 text-sm cursor-pointer">
                        <i class="bi bi-whatsapp text-sm"></i>
                        <span>WhatsApp</span>
                    </a>
                </div>
            @endif

            <!-- Müraciət Et Formu (Rieltor və ya Agentlik elanlarında) -->
            <div class="pt-5 border-t border-gray-100 space-y-3.5">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-chat-left-dots-fill text-orange-500"></i>
                        <span>{{ __('property.send_inquiry') }}</span>
                    </h4>
                    <span class="text-[11px] text-gray-400 font-medium">{{ __('property.online_inquiry') }}</span>
                </div>

                <form method="POST" action="{{ route('inquiries.store') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('property.full_name') }}
                            <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="{{ auth()->user()?->name }}"
                               placeholder="{{ __('property.full_name_placeholder') }}"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('property.phone_number') }}
                            <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" required
                               value="{{ auth()->user()?->phone }}"
                               placeholder="Məs: +994 50 123 45 67"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('property.message_note') }}</label>
                        <textarea name="message" rows="3"
                                  placeholder="{{ __('property.message_placeholder') }}"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition"></textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-gray-900 hover:bg-black text-white font-semibold text-xs rounded-xl shadow transition duration-200 flex items-center justify-center gap-2 transform active:scale-98 cursor-pointer">
                        <i class="bi bi-send-fill text-xs text-orange-400"></i>
                        <span>{{ __('property.submit_inquiry') }}</span>
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- Owner Layout (Sahibinden — Yalnız Telefon və WhatsApp) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest">{{ __('property.contact_person') }}</h3>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="bi bi-person-check-fill text-emerald-600"></i>
                    {{ __('property.owner') }}
                </span>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="w-13 h-13 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl border border-orange-100 shadow-2xs shrink-0">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="space-y-0.5 min-w-0">
                    <h4 class="text-base font-bold text-gray-900 leading-tight truncate">
                        {{ $agentName }}
                    </h4>
                    <p class="text-xs text-gray-500 font-medium">{{ __('property.owner') }}</p>
                </div>
            </div>

            @if($hasContact)
                <div class="pt-3 border-t border-gray-100 space-y-2.5">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">{{ __('property.phone') }}:</span>
                        <span class="js-phone-display font-bold text-gray-900 filter blur-xs select-none tracking-wider">+90 533 ··· ·· ··</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2.5 pt-1">
                    <button type="button"
                        onclick="revealPropertyPhone(event, this, {{ $property->id }}, 'call')"
                        class="js-btn-show-phone w-full flex items-center justify-center gap-2 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-2xl shadow-md transition duration-200 transform active:scale-98 text-sm cursor-pointer">
                        <i class="bi bi-eye text-xs"></i>
                        <span class="js-phone-btn-text">{{ __('property.show_phone') }}</span>
                    </button>

                    <a href="javascript:void(0)"
                       onclick="revealPropertyPhone(event, this, {{ $property->id }}, 'whatsapp')"
                       class="js-btn-whatsapp w-full flex items-center justify-center gap-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl shadow-md transition duration-200 transform active:scale-98 text-sm cursor-pointer">
                        <i class="bi bi-whatsapp text-sm"></i>
                        <span>WhatsApp</span>
                    </a>
                </div>
            @endif
        </div>
    @endif

</div>

<div class="grid grid-cols-2 gap-2">
    <!-- İrəli çək -->
    <div onclick="document.getElementById('modal-advance') && (document.getElementById('modal-advance').style.display = 'flex')"
         class="js-btn-advance bg-white hover:bg-gray-50 border border-gray-200/90 rounded-2xl p-3 flex flex-col justify-between cursor-pointer transition shadow-2xs">
        <div class="flex items-center justify-between">
            <span class="text-xs sm:text-sm font-bold text-gray-800">{{ __('property.advance_ad') }}</span>
            <span class="text-emerald-500 font-bold text-base"><i class="fa-solid fa-arrow-up"></i></span>
        </div>
        <span class="text-[11px] font-semibold text-blue-600 mt-1">{{ __('property.from_azn', ['amount' => 3]) }}</span>
    </div>

    <!-- Premium -->
    <div onclick="document.getElementById('modal-premium') && (document.getElementById('modal-premium').style.display = 'flex')"
         class="js-btn-premium bg-white hover:bg-gray-50 border border-gray-200/90 rounded-2xl p-3 flex flex-col justify-between cursor-pointer transition shadow-2xs">
        <div class="flex items-center justify-between">
            <span class="text-xs sm:text-sm font-bold text-gray-800">{{ __('property.premium_ad') }}</span>
            <span class="text-amber-500 text-base"><i class="fa-solid fa-crown"></i></span>
        </div>
        <span class="text-[11px] font-semibold text-blue-600 mt-1">{{ __('property.from_azn', ['amount' => 7]) }}</span>
    </div>
</div>
