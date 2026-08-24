@extends('layouts.app')

@section('content')
<div class="w-full pt-4">
    @include('components.breadcrumb', ['items' => [
        ['label' => __('Ana Səhifə'), 'url' => '/'],
        ['label' => __('Yeni Elan Yerləşdir')],
    ]])
</div>

@include('components.scroll-top')

<section id="add-property" class="w-full py-4 mb-16">
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl mb-8 shadow-sm">
            <div class="flex items-center gap-2 font-bold text-sm mb-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-600"></i>
                <span>{{ __('Zəhmət olmasa formdakı xətaları düzəldin:') }}</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('add-property.store') }}" enctype="multipart/form-data" id="propertyForm" class="space-y-8">
        @csrf

        <!-- BÖLMƏ 1: Əsas Göstəricilər və Qiymət -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 sm:p-8 shadow-sm space-y-7">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-lg font-bold text-gray-900">{{ __('1. Əsas Məlumatlar və Qiymət') }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">{{ __('Əmlak növü, əməliyyat, qiymət və əsas parametrlər') }}</p>
            </div>

            <!-- Əmlak Növü & Alqı-satqı -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">{{ __('Əmlakın Növü') }} <span class="text-rose-500">*</span></label>
                    @if($propertyTypes->count() <= 2)
                        <div class="grid grid-cols-{{ $propertyTypes->count() }} gap-2">
                            @foreach($propertyTypes as $type)
                                <label class="relative flex items-center justify-center p-3 text-center rounded-xl border cursor-pointer select-none transition-all
                                    has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-bold
                                    border-gray-200 bg-gray-50/60 text-gray-700 font-medium hover:border-gray-300 text-xs sm:text-sm">
                                    <input type="radio" name="property_type_id" value="{{ $type->id }}" {{ old('property_type_id') == $type->id ? 'checked' : '' }} required class="sr-only">
                                    <span>{{ $type->name['az'] ?? $type->value }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <select name="property_type_id" id="property_type_id" required
                            class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                            <option value="">{{ __('Seçin...') }}</option>
                            @foreach($propertyTypes as $type)
                                <option value="{{ $type->id }}" {{ old('property_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name['az'] ?? $type->value }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">{{ __('Alqı-satqı Növü') }} <span class="text-rose-500">*</span></label>
                    @if($dealTypes->count() <= 2)
                        <div class="grid grid-cols-{{ $dealTypes->count() }} gap-2">
                            @foreach($dealTypes as $deal)
                                <label class="relative flex items-center justify-center p-3 text-center rounded-xl border cursor-pointer select-none transition-all
                                    has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-bold
                                    border-gray-200 bg-gray-50/60 text-gray-700 font-medium hover:border-gray-300 text-xs sm:text-sm">
                                    <input type="radio" name="deal_type_id" value="{{ $deal->id }}" {{ old('deal_type_id', $loop->first ? $deal->id : null) == $deal->id ? 'checked' : '' }} required class="sr-only">
                                    <span>{{ $deal->name['az'] ?? $deal->value }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <select name="deal_type_id" id="deal_type_id" required
                            class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                            <option value="">{{ __('Seçin...') }}</option>
                            @foreach($dealTypes as $deal)
                                <option value="{{ $deal->id }}" {{ old('deal_type_id') == $deal->id ? 'selected' : '' }}>
                                    {{ $deal->name['az'] ?? $deal->value }}
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
                        <span class="text-sm font-bold text-gray-900">{{ __('Qiymət və Əsas Valyuta') }}</span>
                        <p class="text-xs text-gray-500">{{ __('Əsas valyutanı seçib qiyməti daxil edin. Digər valyutalar avtomatik hesablanacaq.') }}</p>
                    </div>

                    <!-- Auto-convert toggle -->
                    <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" id="auto_convert_toggle" checked class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500 relative"></div>
                        <span class="text-xs font-semibold text-gray-700">{{ __('Avtomatik Məzənnə') }}</span>
                    </label>
                </div>

                <!-- Primary Price & Currency Select Row -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-1">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Əsas Valyuta') }}</label>
                        <select name="currency" id="main_currency" class="w-full bg-white border border-gray-300 rounded-xl px-3.5 py-3 text-sm font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
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
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Əsas Qiymət') }} <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span id="main_currency_symbol" class="absolute left-4 top-1/2 -translate-y-1/2 text-orange-600 font-extrabold text-base">£</span>
                            <input type="number" step="any" name="price" id="main_price_input" value="{{ old('price', old('price_gbp')) }}" required min="1" placeholder="Məs: 150000"
                                class="w-full bg-white border border-gray-300 rounded-xl pl-9 pr-4 py-3 text-base font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 transition shadow-inner">
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
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Sahə (m²)') }}</label>
                    <input type="number" step="any" name="area" id="area" value="{{ old('area') }}" placeholder="120"
                        class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div id="wrapper_land_area" class="hidden">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Torpaq Sahəsi (sot)') }} <span class="text-rose-500">*</span></label>
                    <input type="number" step="any" name="land_area" id="land_area" value="{{ old('land_area') }}" placeholder="6"
                        class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div id="wrapper_rooms">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Otaq Sayı') }}</label>
                    <select name="rooms" id="rooms"
                        class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">{{ __('Seçin...') }}</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('rooms') == $i ? 'selected' : '' }}>{{ $i }} otaqlı</option>
                        @endfor
                    </select>
                </div>

                <div id="wrapper_floor">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Mərtəbə') }}</label>
                    <input type="number" name="floor" id="floor" value="{{ old('floor') }}" placeholder="5" min="1" max="100"
                        class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div id="wrapper_total_floors">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Binanın Mərtəbəsi') }}</label>
                    <input type="number" name="total_floors" id="total_floors" value="{{ old('total_floors') }}" placeholder="16" min="1" max="100"
                        class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
            </div>

            <!-- Əlavə Xüsusiyyətlər (Tikili, Təmir, İstilik, Mənzərə) -->
            <div id="section_features" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2 border-t border-gray-100">
                <!-- Tikili Növü -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Tikili Növü') }}</label>
                    @if($buildingTypes->count() <= 2)
                        <div class="grid grid-cols-{{ $buildingTypes->count() }} gap-2">
                            @foreach($buildingTypes as $bt)
                                <label class="relative flex items-center justify-center p-2.5 text-center rounded-xl border cursor-pointer select-none transition-all
                                    has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-bold
                                    border-gray-200 bg-gray-50 text-gray-700 font-medium hover:border-gray-300 text-xs">
                                    <input type="radio" name="building_type_id" value="{{ $bt->id }}" {{ old('building_type_id') == $bt->id ? 'checked' : '' }} class="sr-only">
                                    <span>{{ $bt->name['az'] ?? $bt->value }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <select name="building_type_id" id="building_type_id"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-900 focus:ring-2 focus:ring-orange-500">
                            <option value="">{{ __('Seçin...') }}</option>
                            @foreach($buildingTypes as $bt)
                                <option value="{{ $bt->id }}" {{ old('building_type_id') == $bt->id ? 'selected' : '' }}>{{ $bt->name['az'] ?? $bt->value }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <!-- Təmir Vəziyyəti -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Təmir Vəziyyəti') }}</label>
                    @if($repairTypes->count() <= 2)
                        <div class="grid grid-cols-{{ $repairTypes->count() }} gap-2">
                            @foreach($repairTypes as $rt)
                                <label class="relative flex items-center justify-center p-2.5 text-center rounded-xl border cursor-pointer select-none transition-all
                                    has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-bold
                                    border-gray-200 bg-gray-50 text-gray-700 font-medium hover:border-gray-300 text-xs">
                                    <input type="radio" name="repair_type_id" value="{{ $rt->id }}" {{ old('repair_type_id') == $rt->id ? 'checked' : '' }} class="sr-only">
                                    <span>{{ $rt->name['az'] ?? $rt->value }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <select name="repair_type_id" id="repair_type_id"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-900 focus:ring-2 focus:ring-orange-500">
                            <option value="">{{ __('Seçin...') }}</option>
                            @foreach($repairTypes as $rt)
                                <option value="{{ $rt->id }}" {{ old('repair_type_id') == $rt->id ? 'selected' : '' }}>{{ $rt->name['az'] ?? $rt->value }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <!-- İstilik Sistemi -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('İstilik Sistemi') }}</label>
                    @if($heatingSystems->count() <= 2)
                        <div class="grid grid-cols-{{ $heatingSystems->count() }} gap-2">
                            @foreach($heatingSystems as $hs)
                                <label class="relative flex items-center justify-center p-2.5 text-center rounded-xl border cursor-pointer select-none transition-all
                                    has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-bold
                                    border-gray-200 bg-gray-50 text-gray-700 font-medium hover:border-gray-300 text-xs">
                                    <input type="radio" name="heating_system_id" value="{{ $hs->id }}" {{ old('heating_system_id') == $hs->id ? 'checked' : '' }} class="sr-only">
                                    <span>{{ $hs->name['az'] ?? $hs->value }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <select name="heating_system_id" id="heating_system_id"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-900 focus:ring-2 focus:ring-orange-500">
                            <option value="">{{ __('Seçin...') }}</option>
                            @foreach($heatingSystems as $hs)
                                <option value="{{ $hs->id }}" {{ old('heating_system_id') == $hs->id ? 'selected' : '' }}>{{ $hs->name['az'] ?? $hs->value }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <!-- Pəncərə Baxışı -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Pəncərə Baxışı') }}</label>
                    @if($windowViews->count() <= 2)
                        <div class="grid grid-cols-{{ $windowViews->count() }} gap-2">
                            @foreach($windowViews as $wv)
                                <label class="relative flex items-center justify-center p-2.5 text-center rounded-xl border cursor-pointer select-none transition-all
                                    has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-bold
                                    border-gray-200 bg-gray-50 text-gray-700 font-medium hover:border-gray-300 text-xs">
                                    <input type="radio" name="window_view_id" value="{{ $wv->id }}" {{ old('window_view_id') == $wv->id ? 'checked' : '' }} class="sr-only">
                                    <span>{{ $wv->name['az'] ?? $wv->value }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <select name="window_view_id" id="window_view_id"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-900 focus:ring-2 focus:ring-orange-500">
                            <option value="">{{ __('Seçin...') }}</option>
                            @foreach($windowViews as $wv)
                                <option value="{{ $wv->id }}" {{ old('window_view_id') == $wv->id ? 'selected' : '' }}>{{ $wv->name['az'] ?? $wv->value }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>

            <!-- Sənədlər və Kredit Şərtləri -->
            <div id="section_documents_credit" class="pt-4 border-t border-gray-100">
                <span class="block text-xs font-bold text-gray-700 mb-3">{{ __('Sənəd və Kredit Şərtləri') }}</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="flex items-center gap-3 p-3 bg-gray-50/70 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                        <input type="checkbox" name="has_document" value="1" {{ old('has_document') ? 'checked' : '' }} class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                        <span class="text-xs font-bold text-gray-800">{{ __('Çıxarış var (Kupça)') }}</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 bg-gray-50/70 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                        <input type="checkbox" name="has_mortgage" value="1" {{ old('has_mortgage') ? 'checked' : '' }} class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                        <span class="text-xs font-bold text-gray-800">{{ __('İpotekaya yararlı') }}</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 bg-gray-50/70 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                        <input type="checkbox" name="has_internal_credit" value="1" {{ old('has_internal_credit') ? 'checked' : '' }} class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                        <span class="text-xs font-bold text-gray-800">{{ __('Daxili kredit var') }}</span>
                    </label>
                </div>
            </div>

            <!-- Təsvir (Rich Text Editor) -->
            <div class="pt-4 border-t border-gray-100">
                <label class="block text-xs font-bold text-gray-700 mb-2">{{ __('Ətraflı Təsvir') }}</label>
                <div id="editor_wrapper" class="bg-white rounded-xl border border-gray-200 overflow-hidden focus-within:border-orange-500 focus-within:ring-2 focus-within:ring-orange-500/20 transition cursor-text shadow-sm">
                    <div id="editor_container" class="min-h-[160px] text-sm text-gray-900 cursor-text">
                        {!! old('description') !!}
                    </div>
                </div>
                <input type="hidden" name="description" id="description_input">
            </div>
        </div>

        <!-- BÖLMƏ 2: Məkan və Xəritə -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-lg font-bold text-gray-900">{{ __('2. Məkan və Xəritə') }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">{{ __('Şəhər, rayon və xəritədə dəqiq yeri qeyd edin') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Şəhər') }} <span class="text-rose-500">*</span></label>
                    <select name="city_id" id="city_id" required
                        class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">{{ __('Şəhər seçin...') }}</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" data-districts='@json($city->activeDistricts)' {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name['az'] ?? $city->slug }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Rayon / Qəsəbə') }}</label>
                    <select name="district_id" id="district_id"
                        class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">{{ __('Rayon seçin...') }}</option>
                    </select>
                </div>
            </div>

            <!-- Dəqiq Ünvan (2-way sync) -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Dəqiq Ünvan / Nişangah') }} <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <i class="bi bi-geo-alt text-orange-500 absolute left-3.5 top-1/2 -translate-y-1/2 text-base"></i>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" required
                        placeholder="Məs: Nizami küç. 45, Fəvvarələr meydanı yaxınlığı"
                        class="w-full bg-gray-50/70 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 shadow-sm">
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Ünvanı yazdıqda xəritə axtarış edir və ya xəritədə kliklədikdə ünvan bura yazılır.</p>
            </div>

            <!-- Leaflet Map Container -->
            <div class="relative w-full h-[380px] rounded-xl overflow-hidden border border-gray-200 shadow-inner">
                <div id="add_property_map" class="w-full h-full z-0"></div>

                <!-- Layer Switcher Floating Button -->
                <div class="absolute top-3 right-3 z-10 bg-white/95 backdrop-blur-md rounded-xl p-1 shadow-md border border-gray-200 flex gap-1 text-xs font-semibold">
                    <button type="button" onclick="switchMapLayer('carto')" id="btn_map_carto"
                        class="px-2.5 py-1 rounded-lg bg-orange-500 text-white shadow-sm transition">Xəritə</button>
                    <button type="button" onclick="switchMapLayer('satellite')" id="btn_map_sat"
                        class="px-2.5 py-1 rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 transition">Peyk</button>
                </div>

                <!-- Boundary Restriction Alert Banner -->
                <div id="map_boundary_notice" class="hidden absolute bottom-3 left-3 right-14 z-10 bg-rose-600/95 text-white text-xs font-semibold px-3 py-2 rounded-xl shadow-lg flex items-center gap-2 backdrop-blur-sm transition-all duration-300">
                    <i class="bi bi-exclamation-octagon-fill text-sm shrink-0"></i>
                    <span id="map_boundary_msg">Yalnız seçilmiş ərazi hüdudları daxilində nöqtə seçə bilərsiniz.</span>
                </div>
            </div>

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', '35.3382') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', '33.3186') }}">
        </div>

        <!-- BÖLMƏ 3: Şəkillər, Təchizatlar və Əlaqə -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 sm:p-8 shadow-sm space-y-7">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-lg font-bold text-gray-900">{{ __('3. Şəkillər, Təchizatlar və Əlaqə') }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">{{ __('Fotoşəkillər, mövcud şərait və əlaqə vasitələri') }}</p>
            </div>

            <!-- Şəkillər -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">{{ __('Fotoşəkillər') }}</label>
                <div id="dropzone_box" class="border-2 border-dashed border-gray-300 hover:border-orange-500 rounded-2xl p-6 text-center cursor-pointer bg-gray-50/50 hover:bg-orange-50/20 transition-all">
                    <input type="file" name="photos[]" id="photos_input" multiple accept="image/*" class="hidden">
                    <div class="flex flex-col items-center justify-center gap-2 pointer-events-none">
                        <i class="bi bi-cloud-arrow-up text-orange-500 text-3xl"></i>
                        <p class="text-sm font-bold text-gray-800">{{ __('Şəkilləri bura atın və ya klikləyib seçin') }}</p>
                        <p class="text-[11px] text-gray-400">JPG, PNG, WebP (İlk şəkil əsas örtük şəkli olur)</p>
                    </div>
                </div>
                <!-- Preview Gallery Grid -->
                <div id="photos_preview_grid" class="grid grid-cols-3 sm:grid-cols-6 gap-2.5 pt-3"></div>
            </div>

            <!-- Təchizatlar (Amenities) -->
            <div id="section_amenities" class="pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-bold text-gray-700">{{ __('Təchizatlar və İmkanlar') }}</label>
                </div>
                <div id="amenities_grid" class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    @foreach($amenities as $amenity)
                        <label class="flex items-center gap-2 p-2.5 bg-gray-50/70 border border-gray-100 rounded-xl cursor-pointer hover:border-orange-200 transition">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}
                                class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                            <span class="text-xs font-medium text-gray-800">{{ is_array($amenity->name) ? ($amenity->name['az'] ?? reset($amenity->name)) : $amenity->name }}</span>
                        </label>
                    @endforeach
                </div>

                @if($amenities instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $amenities->hasMorePages())
                <div id="load_more_amenities_wrapper" class="mt-4 flex justify-center">
                    <button type="button" id="load_more_amenities_btn" data-next-page="2"
                            class="px-5 py-2.5 bg-white border border-gray-200 hover:border-orange-500 hover:text-orange-600 text-gray-700 text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-2">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span>{{ __('Daha çox göstər') }}</span>
                    </button>
                </div>
                @endif
            </div>

            <!-- Əlaqə Məlumatları -->
            <div class="pt-4 border-t border-gray-100">
                <label class="block text-xs font-bold text-gray-700 mb-3">{{ __('Əlaqə Məlumatları') }}</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">{{ __('Satıcı növü') }} <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 gap-1.5">
                            <label class="relative flex items-center justify-center p-2 text-center rounded-xl border cursor-pointer select-none transition-all
                                has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-bold
                                border-gray-200 bg-gray-50 text-gray-700 text-xs">
                                <input type="radio" name="advertiser" value="owner" {{ old('advertiser', 'owner') == 'owner' ? 'checked' : '' }} required class="sr-only">
                                <span>{{ __('Mülkiyyətçi') }}</span>
                            </label>
                            <label class="relative flex items-center justify-center p-2 text-center rounded-xl border cursor-pointer select-none transition-all
                                has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-bold
                                border-gray-200 bg-gray-50 text-gray-700 text-xs">
                                <input type="radio" name="advertiser" value="agent" {{ old('advertiser') == 'agent' ? 'checked' : '' }} required class="sr-only">
                                <span>{{ __('Rieltor / Agent') }}</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">{{ __('Adınız / Şirkət') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="advertiser_name" id="advertiser_name" value="{{ old('advertiser_name', auth()->user()?->name) }}" required placeholder="Məs: Əli Əliyev"
                            class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3 py-2 text-xs sm:text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">{{ __('Telefon') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', auth()->user()?->agent?->phone ?? auth()->user()?->phone) }}" required placeholder="Məs: +994 50 123 45 67"
                            class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3 py-2 text-xs sm:text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">{{ __('WhatsApp Nömrəsi') }}</label>
                        <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', auth()->user()?->agent?->whatsapp) }}" placeholder="Məs: +994 50 123 45 67"
                            class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3 py-2 text-xs sm:text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">{{ __('Email') }} <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="elan@metraj.az"
                            class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3 py-2 text-xs sm:text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p class="text-[11px] text-gray-500 mt-1.5 flex items-center gap-1.5">
                            <i class="bi bi-info-circle text-orange-500 shrink-0 text-xs"></i>
                            <span>{{ __('Biz bunu yalnız elan statusunuzu sizə bildirmək üçün istifadə edirik.') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Action Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-5 bg-gray-50 border border-gray-200/90 rounded-2xl">
            <p class="text-xs text-gray-500">
                <i class="bi bi-shield-check text-orange-500 mr-1 text-sm"></i>
                <span>Elanınız göndərildikdən sonra moderator təsdiqindən keçərək saytda dərc olunacaq.</span>
            </p>
            <button type="submit" id="submit_property_btn"
                class="w-full sm:w-auto px-8 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-xl shadow transition duration-200 transform active:scale-98 flex items-center justify-center gap-2 shrink-0">
                <i class="bi bi-check2-circle text-base"></i>
                <span>{{ __('Elanı Yerləşdir') }}</span>
            </button>
        </div>
    </form>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Quill Rich Text Editor CDN -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
    window.addFormConfig = {
        rates: @json($dailyRates),
        amenityUrl: "{{ route('add-property.amenities') }}",
        i18n: {
            submit: "{{ __('Elanı Yerləşdir') }}",
            loading: "{{ __('Yüklənir...') }}"
        }
    };
</script>
<script src="/js/pages/property/add-form.js"></script>


<style>
.ql-container.ql-snow {
    border: none !important;
    font-family: inherit;
    font-size: 0.875rem;
}
.ql-toolbar.ql-snow {
    border: none !important;
    border-bottom: 1px solid #f3f4f6 !important;
    background-color: #fafafa;
}
.ql-editor {
    min-height: 160px;
    height: 100%;
    cursor: text !important;
    padding: 14px 16px !important;
}
.ql-editor.ql-blank::before {
    color: #9ca3af;
    font-style: normal;
    left: 16px;
    right: 16px;
}
@keyframes leaflet-pulse {
    0% { transform: scale(0.6); opacity: 0.8; }
    50% { transform: scale(1.3); opacity: 0; }
    100% { transform: scale(0.6); opacity: 0; }
}
</style>
@endsection
