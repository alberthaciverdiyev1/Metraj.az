<!-- BÖLMƏ 1: Əsas Göstəricilər və Qiymət -->
<div class="bg-white rounded-2xl border border-gray-200/80 p-6 sm:p-8 shadow-sm space-y-7">
    <div class="border-b border-gray-100 pb-4">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('add_property.section_1_title') }}</h2>
        <p class="text-xs text-gray-500 mt-0.5">{{ __('add_property.section_1_desc') }}</p>
    </div>

    <!-- Əmlak Növü & Alqı-satqı -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">{{ __('add_property.property_type') }} <span class="text-rose-500">*</span></label>
            @if($propertyTypes->count() <= 2)
                <div class="grid grid-cols-{{ $propertyTypes->count() }} gap-2">
                    @foreach($propertyTypes as $type)
                        @php
                            $typeName = is_array($type->name) ? ($type->name[app()->getLocale()] ?? $type->name['az'] ?? reset($type->name)) : $type->name;
                        @endphp
                        <label class="relative flex items-center justify-center p-3 text-center rounded-xl border cursor-pointer select-none transition-all
                            has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-semibold
                            border-gray-200 bg-gray-50/60 text-gray-700 font-medium hover:border-gray-300 text-xs sm:text-sm">
                            <input type="radio" name="property_type_id" value="{{ $type->id }}" {{ old('property_type_id') == $type->id ? 'checked' : '' }} required class="sr-only">
                            <span>{{ $typeName ?? $type->value }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <select name="property_type_id" id="property_type_id" required
                    class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                    <option value="">{{ __('add_property.select') }}</option>
                    @foreach($propertyTypes as $type)
                        @php
                            $typeName = is_array($type->name) ? ($type->name[app()->getLocale()] ?? $type->name['az'] ?? reset($type->name)) : $type->name;
                        @endphp
                        <option value="{{ $type->id }}" {{ old('property_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $typeName ?? $type->value }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">{{ __('add_property.deal_type') }} <span class="text-rose-500">*</span></label>
            @if($dealTypes->count() <= 2)
                <div class="grid grid-cols-{{ $dealTypes->count() }} gap-2">
                    @foreach($dealTypes as $deal)
                        @php
                            $dealName = is_array($deal->name) ? ($deal->name[app()->getLocale()] ?? $deal->name['az'] ?? reset($deal->name)) : $deal->name;
                        @endphp
                        <label class="relative flex items-center justify-center p-3 text-center rounded-xl border cursor-pointer select-none transition-all
                            has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-semibold
                            border-gray-200 bg-gray-50/60 text-gray-700 font-medium hover:border-gray-300 text-xs sm:text-sm">
                            <input type="radio" name="deal_type_id" value="{{ $deal->id }}" {{ old('deal_type_id', $loop->first ? $deal->id : null) == $deal->id ? 'checked' : '' }} required class="sr-only">
                            <span>{{ $dealName ?? $deal->value }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <select name="deal_type_id" id="deal_type_id" required
                    class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                    <option value="">{{ __('add_property.select') }}</option>
                    @foreach($dealTypes as $deal)
                        @php
                            $dealName = is_array($deal->name) ? ($deal->name[app()->getLocale()] ?? $deal->name['az'] ?? reset($deal->name)) : $deal->name;
                        @endphp
                        <option value="{{ $deal->id }}" {{ old('deal_type_id') == $deal->id ? 'selected' : '' }}>
                            {{ $dealName ?? $deal->value }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <!-- Qiymət və Əsas Valyuta Bölməsi (Main Currency + Auto Convert) -->
    <div class="p-5 bg-gray-50/80 rounded-2xl border border-gray-200/80 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="text-sm font-semibold text-gray-900">{{ __('add_property.price_and_currency') }}</span>
                <p class="text-xs text-gray-500">{{ __('add_property.price_and_currency_desc') }}</p>
            </div>

            <!-- Auto-convert toggle -->
            <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                <input type="checkbox" id="auto_convert_toggle" checked class="sr-only peer">
                <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500 relative"></div>
                <span class="text-xs font-semibold text-gray-700">{{ __('add_property.auto_convert') }}</span>
            </label>
        </div>

        <!-- Primary Price & Currency Select Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-1">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.main_currency') }}</label>
                <select name="currency" id="main_currency" class="w-full bg-white border border-gray-300 rounded-xl px-3.5 py-3 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                    <option value="GBP" {{ old('currency', 'GBP') === 'GBP' ? 'selected' : '' }}>GBP (£ - Funt Sterlinq)</option>
                    <option value="AZN" {{ old('currency') === 'AZN' ? 'selected' : '' }}>AZN (₼ - Manat)</option>
                    <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD ($ - Dollar)</option>
                    <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR (€ - Avro)</option>
                    <option value="TRY" {{ old('currency') === 'TRY' ? 'selected' : '' }}>TRY (₺ - Türk Lirəsi)</option>
                    <option value="RUB" {{ old('currency') === 'RUB' ? 'selected' : '' }}>RUB (₽ - Rubl)</option>
                    <option value="AED" {{ old('currency') === 'AED' ? 'selected' : '' }}>AED (د.إ - Dirhəm)</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.main_price') }} <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span id="main_currency_symbol" class="absolute left-4 top-1/2 -translate-y-1/2 text-orange-600 font-bold text-base">£</span>
                    <input type="number" step="any" name="price" id="main_price_input" value="{{ old('price', old('price_gbp')) }}" required min="1" placeholder="Məs: 150000"
                        class="w-full bg-white border border-gray-300 rounded-xl pl-9 pr-4 py-3 text-base font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 transition shadow-inner">
                    <input type="hidden" name="price_gbp" id="price_gbp" value="{{ old('price_gbp') }}">
                </div>
            </div>
        </div>

        <!-- All Target Currencies Grid -->
        <div id="other_currencies_grid" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2.5 pt-3 border-t border-gray-200">
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">GBP (£)</label>
                <input type="number" step="any" name="prices[GBP]" id="price_gbp_val" value="{{ old('prices.GBP') }}" min="1"
                    class="currency-converted-input w-full bg-gray-100/90 text-gray-500 cursor-not-allowed border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:ring-2 focus:ring-orange-500 transition">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">AZN (₼)</label>
                <input type="number" step="any" name="prices[AZN]" id="price_azn" value="{{ old('prices.AZN') }}" min="1"
                    class="currency-converted-input w-full bg-gray-100/90 text-gray-500 cursor-not-allowed border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:ring-2 focus:ring-orange-500 transition">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">USD ($)</label>
                <input type="number" step="any" name="prices[USD]" id="price_usd" value="{{ old('prices.USD') }}" min="1"
                    class="currency-converted-input w-full bg-gray-100/90 text-gray-500 cursor-not-allowed border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:ring-2 focus:ring-orange-500 transition">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">EUR (€)</label>
                <input type="number" step="any" name="prices[EUR]" id="price_eur" value="{{ old('prices.EUR') }}" min="1"
                    class="currency-converted-input w-full bg-gray-100/90 text-gray-500 cursor-not-allowed border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:ring-2 focus:ring-orange-500 transition">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">TRY (₺)</label>
                <input type="number" step="any" name="prices[TRY]" id="price_try" value="{{ old('prices.TRY') }}" min="1"
                    class="currency-converted-input w-full bg-gray-100/90 text-gray-500 cursor-not-allowed border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:ring-2 focus:ring-orange-500 transition">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">RUB (₽)</label>
                <input type="number" step="any" name="prices[RUB]" id="price_rub" value="{{ old('prices.RUB') }}" min="1"
                    class="currency-converted-input w-full bg-gray-100/90 text-gray-500 cursor-not-allowed border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:ring-2 focus:ring-orange-500 transition">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">AED (د.إ)</label>
                <input type="number" step="any" name="prices[AED]" id="price_aed" value="{{ old('prices.AED') }}" min="1"
                    class="currency-converted-input w-full bg-gray-100/90 text-gray-500 cursor-not-allowed border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:ring-2 focus:ring-orange-500 transition">
            </div>
        </div>
    </div>

    <!-- Ölçülər və Mərtəbə (Torpaq üçün dinamik gizlənir) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div id="wrapper_area">
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.area') }}</label>
            <input type="number" step="any" name="area" id="area" value="{{ old('area') }}" placeholder="120"
                class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>

        <div id="wrapper_land_area" class="hidden">
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.land_area') }} <span class="text-rose-500">*</span></label>
            <input type="number" step="any" name="land_area" id="land_area" value="{{ old('land_area') }}" placeholder="6"
                class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>

        <div id="wrapper_rooms">
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.room_count') }}</label>
            <select name="rooms" id="rooms"
                class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">{{ __('add_property.select') }}</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ old('rooms') == $i ? 'selected' : '' }}>{{ __('add_property.rooms_count', ['count' => $i]) }}</option>
                @endfor
            </select>
        </div>

        <div id="wrapper_floor">
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.floor') }}</label>
            <input type="number" name="floor" id="floor" value="{{ old('floor') }}" placeholder="5" min="1" max="100"
                class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>

        <div id="wrapper_total_floors">
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.total_floors') }}</label>
            <input type="number" name="total_floors" id="total_floors" value="{{ old('total_floors') }}" placeholder="16" min="1" max="100"
                class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
    </div>

    <!-- Əlavə Xüsusiyyətlər (Tikili, Təmir, İstilik, Mənzərə) -->
    <div id="section_features" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2 border-t border-gray-100">
        <!-- Tikili Növü -->
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.building_type') }}</label>
            @if($buildingTypes->count() <= 2)
                <div class="grid grid-cols-{{ $buildingTypes->count() }} gap-2">
                    @foreach($buildingTypes as $bt)
                        @php
                            $btName = is_array($bt->name) ? ($bt->name[app()->getLocale()] ?? $bt->name['az'] ?? reset($bt->name)) : $bt->name;
                        @endphp
                        <label class="relative flex items-center justify-center p-2.5 text-center rounded-xl border cursor-pointer select-none transition-all
                            has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-semibold
                            border-gray-200 bg-gray-50 text-gray-700 font-medium hover:border-gray-300 text-xs">
                            <input type="radio" name="building_type_id" value="{{ $bt->id }}" {{ old('building_type_id') == $bt->id ? 'checked' : '' }} class="sr-only">
                            <span>{{ $btName ?? $bt->value }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <select name="building_type_id" id="building_type_id"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-900 focus:ring-2 focus:ring-orange-500">
                    <option value="">{{ __('add_property.select') }}</option>
                    @foreach($buildingTypes as $bt)
                        @php
                            $btName = is_array($bt->name) ? ($bt->name[app()->getLocale()] ?? $bt->name['az'] ?? reset($bt->name)) : $bt->name;
                        @endphp
                        <option value="{{ $bt->id }}" {{ old('building_type_id') == $bt->id ? 'selected' : '' }}>{{ $btName ?? $bt->value }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <!-- Təmir Vəziyyəti -->
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.repair_type') }}</label>
            @if($repairTypes->count() <= 2)
                <div class="grid grid-cols-{{ $repairTypes->count() }} gap-2">
                    @foreach($repairTypes as $rt)
                        @php
                            $rtName = is_array($rt->name) ? ($rt->name[app()->getLocale()] ?? $rt->name['az'] ?? reset($rt->name)) : $rt->name;
                        @endphp
                        <label class="relative flex items-center justify-center p-2.5 text-center rounded-xl border cursor-pointer select-none transition-all
                            has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-semibold
                            border-gray-200 bg-gray-50 text-gray-700 font-medium hover:border-gray-300 text-xs">
                            <input type="radio" name="repair_type_id" value="{{ $rt->id }}" {{ old('repair_type_id') == $rt->id ? 'checked' : '' }} class="sr-only">
                            <span>{{ $rtName ?? $rt->value }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <select name="repair_type_id" id="repair_type_id"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-900 focus:ring-2 focus:ring-orange-500">
                    <option value="">{{ __('add_property.select') }}</option>
                    @foreach($repairTypes as $rt)
                        @php
                            $rtName = is_array($rt->name) ? ($rt->name[app()->getLocale()] ?? $rt->name['az'] ?? reset($rt->name)) : $rt->name;
                        @endphp
                        <option value="{{ $rt->id }}" {{ old('repair_type_id') == $rt->id ? 'selected' : '' }}>{{ $rtName ?? $rt->value }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <!-- İstilik Sistemi -->
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.heating_system') }}</label>
            @if($heatingSystems->count() <= 2)
                <div class="grid grid-cols-{{ $heatingSystems->count() }} gap-2">
                    @foreach($heatingSystems as $hs)
                        @php
                            $hsName = is_array($hs->name) ? ($hs->name[app()->getLocale()] ?? $hs->name['az'] ?? reset($hs->name)) : $hs->name;
                        @endphp
                        <label class="relative flex items-center justify-center p-2.5 text-center rounded-xl border cursor-pointer select-none transition-all
                            has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-semibold
                            border-gray-200 bg-gray-50 text-gray-700 font-medium hover:border-gray-300 text-xs">
                            <input type="radio" name="heating_system_id" value="{{ $hs->id }}" {{ old('heating_system_id') == $hs->id ? 'checked' : '' }} class="sr-only">
                            <span>{{ $hsName ?? $hs->value }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <select name="heating_system_id" id="heating_system_id"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-900 focus:ring-2 focus:ring-orange-500">
                    <option value="">{{ __('add_property.select') }}</option>
                    @foreach($heatingSystems as $hs)
                        @php
                            $hsName = is_array($hs->name) ? ($hs->name[app()->getLocale()] ?? $hs->name['az'] ?? reset($hs->name)) : $hs->name;
                        @endphp
                        <option value="{{ $hs->id }}" {{ old('heating_system_id') == $hs->id ? 'selected' : '' }}>{{ $hsName ?? $hs->value }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <!-- Pəncərə Baxışı -->
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('add_property.window_view') }}</label>
            @if($windowViews->count() <= 2)
                <div class="grid grid-cols-{{ $windowViews->count() }} gap-2">
                    @foreach($windowViews as $wv)
                        @php
                            $wvName = is_array($wv->name) ? ($wv->name[app()->getLocale()] ?? $wv->name['az'] ?? reset($wv->name)) : $wv->name;
                        @endphp
                        <label class="relative flex items-center justify-center p-2.5 text-center rounded-xl border cursor-pointer select-none transition-all
                            has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-semibold
                            border-gray-200 bg-gray-50 text-gray-700 font-medium hover:border-gray-300 text-xs">
                            <input type="radio" name="window_view_id" value="{{ $wv->id }}" {{ old('window_view_id') == $wv->id ? 'checked' : '' }} class="sr-only">
                            <span>{{ $wvName ?? $wv->value }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <select name="window_view_id" id="window_view_id"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-900 focus:ring-2 focus:ring-orange-500">
                    <option value="">{{ __('add_property.select') }}</option>
                    @foreach($windowViews as $wv)
                        @php
                            $wvName = is_array($wv->name) ? ($wv->name[app()->getLocale()] ?? $wv->name['az'] ?? reset($wv->name)) : $wv->name;
                        @endphp
                        <option value="{{ $wv->id }}" {{ old('window_view_id') == $wv->id ? 'selected' : '' }}>{{ $wvName ?? $wv->value }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <!-- Sənədlər və Kredit Şərtləri -->
    <div id="section_documents_credit" class="pt-4 border-t border-gray-100">
        <span class="block text-xs font-semibold text-gray-700 mb-3">{{ __('add_property.documents_and_credit') }}</span>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <label class="flex items-center gap-3 p-3 bg-gray-50/70 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                <input type="checkbox" name="has_document" value="1" {{ old('has_document') ? 'checked' : '' }} class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                <span class="text-xs font-semibold text-gray-800">{{ __('add_property.has_document') }}</span>
            </label>

            <label class="flex items-center gap-3 p-3 bg-gray-50/70 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                <input type="checkbox" name="has_mortgage" value="1" {{ old('has_mortgage') ? 'checked' : '' }} class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                <span class="text-xs font-semibold text-gray-800">{{ __('add_property.has_mortgage') }}</span>
            </label>

            <label class="flex items-center gap-3 p-3 bg-gray-50/70 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                <input type="checkbox" name="has_internal_credit" value="1" {{ old('has_internal_credit') ? 'checked' : '' }} class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                <span class="text-xs font-semibold text-gray-800">{{ __('add_property.has_internal_credit') }}</span>
            </label>
        </div>
    </div>

    <!-- Təsvir (Rich Text Editor) -->
    <div class="pt-4 border-t border-gray-100">
        <label class="block text-xs font-semibold text-gray-700 mb-2">{{ __('add_property.detailed_description') }}</label>
        <div id="editor_wrapper" class="bg-white rounded-xl border border-gray-200 overflow-hidden focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-500/20 transition cursor-text shadow-sm">
            <div id="editor_container" class="min-h-[160px] text-sm text-gray-900 cursor-text">
                {!! old('description') !!}
            </div>
        </div>
        <input type="hidden" name="description" id="description_input">
    </div>
</div>
