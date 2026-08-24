@extends('layouts.app')

@section('title', __('Tələb Elanı Yerləşdir') . ' - Metraj.az')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border: none !important;
        border-bottom: 1px solid #e5e7eb !important;
        background: #f9fafb !important;
        border-top-left-radius: 1rem !important;
        border-top-right-radius: 1rem !important;
        padding: 8px 12px !important;
    }
    .ql-container.ql-snow {
        border: none !important;
        font-family: inherit !important;
        font-size: 0.875rem !important;
        min-height: 160px !important;
    }
    .ql-editor {
        min-height: 160px !important;
        padding: 12px 16px !important;
    }
    .ql-editor.ql-blank::before {
        color: #9ca3af !important;
        font-style: normal !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-[1000px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(isset($breadcrumbs))
        <div class="mb-6">
            @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-2xl mb-8 text-xs sm:text-sm space-y-1">
            <div class="font-bold flex items-center gap-2">
                <i class="bi bi-exclamation-octagon-fill text-base text-rose-500"></i>
                <span>{{ __('Zəhmət olmasa xətaları düzəldin:') }}</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-600 ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Form -->
    <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="propertyRequestForm">
        @csrf

        <!-- SECTION 1: TƏLƏB NÖVÜNÜN SEÇİLMƏSİ (4 CATEGORY CARDS) -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
            <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-extrabold flex items-center justify-center">1</span>
                <span>{{ __('Nə axtarırsınız?') }}</span>
            </h2>
            <p class="text-xs text-gray-500 mb-5 ml-8">{{ __('Tələbinizə uyğun kateqoriyanı seçin') }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">

                <!-- 1: Almaq İstəyirəm -->
                <label class="cursor-pointer">
                    <input type="radio" name="request_type" value="buy" class="peer sr-only request-type-radio" {{ old('request_type', request('type', 'buy')) === 'buy' ? 'checked' : '' }}>
                    <div class="h-full p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/40 hover:border-gray-300 transition-all flex flex-col justify-between">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg mb-3">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xs sm:text-sm text-gray-900 mb-0.5">{{ __('Almaq İstəyirəm') }}</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">{{ __('Mənzil, ev, torpaq və ya obyekt almaq üçün') }}</p>
                        </div>
                    </div>
                </label>

                <!-- 2: Kirayə Axtarıram -->
                <label class="cursor-pointer">
                    <input type="radio" name="request_type" value="rent_monthly" class="peer sr-only request-type-radio" {{ old('request_type', request('type')) === 'rent_monthly' || old('request_type', request('type')) === 'rent' ? 'checked' : '' }}>
                    <div class="h-full p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50/40 hover:border-gray-300 transition-all flex flex-col justify-between">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg mb-3">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xs sm:text-sm text-gray-900 mb-0.5">{{ __('Kirayə Axtarıram') }}</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">{{ __('Aylıq uzunmüddətli kirayə mənzil/ev tapmaq üçün') }}</p>
                        </div>
                    </div>
                </label>

                <!-- 3: Günlük Axtarıram -->
                <label class="cursor-pointer">
                    <input type="radio" name="request_type" value="rent_daily" class="peer sr-only request-type-radio" {{ old('request_type', request('type')) === 'rent_daily' || old('request_type', request('type')) === 'daily' ? 'checked' : '' }}>
                    <div class="h-full p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50/40 hover:border-gray-300 transition-all flex flex-col justify-between">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg mb-3">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xs sm:text-sm text-gray-900 mb-0.5">{{ __('Günlük Qalmaq') }}</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">{{ __('Qısamüddətli və ya istirahət üçün günlük ev') }}</p>
                        </div>
                    </div>
                </label>

                <!-- 4: Otaq Yoldaşı -->
                <label class="cursor-pointer">
                    <input type="radio" name="request_type" value="roommate_have" class="peer sr-only request-type-radio" {{ old('request_type', request('type')) === 'roommate_have' || old('request_type', request('type')) === 'roommate' || old('request_type') === 'roommate_need' ? 'checked' : '' }}>
                    <div class="h-full p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-purple-500 peer-checked:bg-purple-50/40 hover:border-gray-300 transition-all flex flex-col justify-between">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg mb-3">
                            <i class="fa-solid fa-people-roof"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xs sm:text-sm text-gray-900 mb-0.5">{{ __('Otaq Yoldaşı') }}</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">{{ __('Evim var yoldaş axtarıram və ya ev axtarıram') }}</p>
                        </div>
                    </div>
                </label>

            </div>
        </div>

        <!-- SECTION 2: ƏSAS PARAMETRLƏR VƏ BÜDCƏ -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-extrabold flex items-center justify-center">2</span>
                    <span>{{ __('Tələbin Başlığı və Büdcəniz') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('Nəzərdə tutulan məbləğ və əsas parametrlər') }}</p>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Elanın Başlığı') }} <span class="text-rose-500">*</span></label>
                <input type="text" name="title" id="titleInput" value="{{ old('title') }}" required
                       placeholder="{{ __('Məs: Nərimanovda 2-3 otaqlı kupçalı mənzil almaq istəyirəm') }}"
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <!-- Property Type (for buy/rent) -->
                <div id="propertyTypeField">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Əmlak Növü') }}</label>
                    <select name="property_type"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="Mənzil" {{ old('property_type') === 'Mənzil' ? 'selected' : '' }}>{{ __('Mənzil (Bina evi)') }}</option>
                        <option value="Həyət evi" {{ old('property_type') === 'Həyət evi' ? 'selected' : '' }}>{{ __('Həyət evi / Bağ') }}</option>
                        <option value="Villa" {{ old('property_type') === 'Villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                        <option value="Torpaq" {{ old('property_type') === 'Torpaq' ? 'selected' : '' }}>{{ __('Torpaq') }}</option>
                        <option value="Obyekt" {{ old('property_type') === 'Obyekt' ? 'selected' : '' }}>{{ __('Obyekt') }}</option>
                        <option value="Ofis" {{ old('property_type') === 'Ofis' ? 'selected' : '' }}>{{ __('Ofis') }}</option>
                    </select>
                </div>

                <!-- Budget Max -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Maksimum Büdcə (₼)') }} <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="budget_max" value="{{ old('budget_max') }}" required min="1" step="any"
                               placeholder="150000"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl pl-4 pr-10 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 font-bold text-gray-500 text-xs">₼</span>
                    </div>
                </div>

                <!-- Rooms -->
                <div id="roomsField">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Otaq Sayı') }}</label>
                    <select name="rooms"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('Fərqi yoxdur') }}</option>
                        <option value="1" {{ old('rooms') === '1' ? 'selected' : '' }}>1 {{ __('otaqlı') }}</option>
                        <option value="2" {{ old('rooms') === '2' ? 'selected' : '' }}>2 {{ __('otaqlı') }}</option>
                        <option value="3" {{ old('rooms') === '3' ? 'selected' : '' }}>3 {{ __('otaqlı') }}</option>
                        <option value="4+" {{ old('rooms') === '4+' ? 'selected' : '' }}>4+ {{ __('otaqlı') }}</option>
                    </select>
                </div>

            </div>

            <!-- Dynamic Specific Requirements (Buy vs Rent vs Roommate) -->
            <div id="buyFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <label class="inline-flex items-center gap-2.5 cursor-pointer select-none p-3 rounded-xl bg-gray-50 border border-gray-200 hover:bg-gray-100/70 transition">
                    <input type="checkbox" name="has_deed" value="1" {{ old('has_deed') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                    <span class="text-xs sm:text-sm font-semibold text-gray-800">{{ __('Yalnız Kupçalı (Çıxarışlı) olsun') }}</span>
                </label>

                <label class="inline-flex items-center gap-2.5 cursor-pointer select-none p-3 rounded-xl bg-gray-50 border border-gray-200 hover:bg-gray-100/70 transition">
                    <input type="checkbox" name="mortgage_eligible" value="1" {{ old('mortgage_eligible') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                    <span class="text-xs sm:text-sm font-semibold text-gray-800">{{ __('İpotekaya yararlı olsun') }}</span>
                </label>
            </div>

            <div id="rentFields" class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 hidden">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Kimlər qalacaq?') }}</label>
                    <select name="occupancy_type"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="Ailə" {{ old('occupancy_type') === 'Ailə' ? 'selected' : '' }}>{{ __('Ailə') }}</option>
                        <option value="Tələbələr" {{ old('occupancy_type') === 'Tələbələr' ? 'selected' : '' }}>{{ __('Tələbələr') }}</option>
                        <option value="İşləyən şəxs" {{ old('occupancy_type') === 'İşləyən şəxs' ? 'selected' : '' }}>{{ __('İşləyən şəxs') }}</option>
                        <option value="Xarici vətəndaş" {{ old('occupancy_type') === 'Xarici vətəndaş' ? 'selected' : '' }}>{{ __('Xarici vətəndaş') }}</option>
                    </select>
                </div>

                <div class="flex items-center sm:pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="bills_included" value="1" {{ old('bills_included') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                        <span class="text-xs sm:text-sm font-semibold text-gray-700">{{ __('Kommunal xərclər daxil olsun') }}</span>
                    </label>
                </div>
            </div>

            <div id="roommateFields" class="space-y-4 pt-2 hidden">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Cinsiyyət Tələbi') }}</label>
                        <select name="gender_preference"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                            <option value="any">{{ __('Fərqi yoxdur (Hamı)') }}</option>
                            <option value="female">{{ __('Yalnız Xanım') }}</option>
                            <option value="male">{{ __('Yalnız Bəy') }}</option>
                        </select>
                    </div>

                    <div class="flex items-center sm:pt-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="smoker_allowed" value="1" {{ old('smoker_allowed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span class="text-xs sm:text-sm font-semibold text-gray-700">{{ __('Siqaret olar') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center sm:pt-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="pet_allowed" value="1" {{ old('pet_allowed') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span class="text-xs sm:text-sm font-semibold text-gray-700">{{ __('Ev heyvanı olar') }}</span>
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <!-- SECTION 3: MƏKAN -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-extrabold flex items-center justify-center">3</span>
                    <span>{{ __('İstədiyiniz Ərazi') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('Axtardığınız şəhər, rayon və ya metro') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- City -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Şəhər') }} <span class="text-rose-500">*</span></label>
                    <select name="city_id" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('Şəhər seçin') }}</option>
                        @foreach($cities as $city)
                            @php
                                $cName = is_array($city->name) ? ($city->name[app()->getLocale()] ?? $city->name['az'] ?? reset($city->name)) : $city->name;
                            @endphp
                            <option value="{{ $city->id }}" {{ old('city_id', 1) == $city->id ? 'selected' : '' }}>
                                {{ $cName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Location Note -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Metro, Qəsəbə və ya Nişangah') }}</label>
                    <input type="text" name="location_note" value="{{ old('location_note') }}"
                           placeholder="{{ __('Məs: Nərimanov, Elmlər, 28 May m/s yaxınlığı') }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>
            </div>
        </div>

        <!-- SECTION 4: ƏTRAFLI TƏSVİR VƏ İSTƏYƏ GÖRƏ ŞƏKİLLƏR -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-extrabold flex items-center justify-center">4</span>
                    <span>{{ __('Ətraflı Tələbləriniz') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('İstədiyiniz xüsusiyyətləri aydın şəkildə izah edin') }}</p>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Ətraflı Təsvir') }} <span class="text-rose-500">*</span></label>
                <div id="editor_wrapper" class="bg-white border border-gray-200 rounded-2xl overflow-hidden focus-within:border-[#f1913d] transition shadow-2xs">
                    <div id="editor_container" class="min-h-[160px] text-xs sm:text-sm font-normal">
                        {!! old('description') !!}
                    </div>
                </div>
                <input type="hidden" name="description" id="description_input" value="{{ old('description') }}" required>
            </div>
        </div>

        <!-- SECTION 5: FOTOŞƏKİLLƏR (Otaq Yoldaşı və ya Mənzil şəkilləri) -->
        <div id="imageUploadSection" class="hidden bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-purple-600 text-white text-xs font-extrabold flex items-center justify-center">
                        <i class="fa-solid fa-camera text-[10px]"></i>
                    </span>
                    <span>{{ __('Fotoşəkillər (Otaq / Mənzil şəkilləri)') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('Otağın və ya evin real şəkillərini əlavə edərək elanınızı daha cəlbedici edin (Maks. 10 şəkil)') }}</p>
            </div>

            <div class="border-2 border-dashed border-gray-200 hover:border-[#f1913d] rounded-2xl p-6 text-center cursor-pointer transition bg-gray-50/50 hover:bg-orange-50/30"
                 onclick="document.getElementById('requestImagesInput').click()">
                <input type="file" name="images[]" id="requestImagesInput" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="bi bi-images"></i>
                </div>
                <p class="text-xs sm:text-sm font-bold text-gray-800 mb-1">{{ __('Şəkilləri seçmək üçün klikləyin') }}</p>
                <p class="text-[11px] text-gray-400">JPG, PNG, WEBP (Hər biri maks. 8MB)</p>
            </div>

            <!-- Previews -->
            <div id="imagePreviewGrid" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 pt-2"></div>
        </div>

        <!-- SECTION 6: ƏLAQƏ MƏLUMATLARI -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-extrabold flex items-center justify-center">6</span>
                    <span>{{ __('Əlaqə Məlumatları') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('Təkliflərin sizə çatması üçün əlaqə vasitələri') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Adınız') }} <span class="text-rose-500">*</span></label>
                    <input type="text" name="contact_name" value="{{ old('contact_name', auth()->user()?->name) }}" required
                           placeholder="Əli Məmmədov"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('Telefon Nömrəsi') }} <span class="text-rose-500">*</span></label>
                    <input type="tel" name="contact_phone" value="{{ old('contact_phone', auth()->user()?->phone) }}" required
                           placeholder="+994 50 123 45 67"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>

                <!-- WhatsApp -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">{{ __('WhatsApp Nömrəsi') }}</label>
                    <input type="tel" name="contact_whatsapp" value="{{ old('contact_whatsapp', auth()->user()?->phone) }}"
                           placeholder="+994 50 123 45 67"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center pt-2">
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-[#f1913d] hover:bg-[#e07f2c] text-white font-bold text-base rounded-2xl shadow-md transition hover:shadow-lg w-full sm:w-auto cursor-pointer">
                <i class="bi bi-check2-circle text-lg"></i>
                <span>{{ __('Tələb Elanını Dərc Et') }}</span>
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1) Initialize Quill Editor
    const quill = new Quill('#editor_container', {
        theme: 'snow',
        placeholder: '{{ __('Axtardığınız əmlak, tələbləriniz və ya təklif edəcəyiniz şərtlər barədə ətraflı yazın...') }}',
        modules: {
            toolbar: [
                [{ 'header': [2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['clean']
            ]
        }
    });

    const form = document.getElementById('propertyRequestForm');
    const descriptionInput = document.getElementById('description_input');

    if (form) {
        form.addEventListener('submit', function (e) {
            if (quill.getText().trim().length === 0) {
                descriptionInput.value = '';
            } else {
                descriptionInput.value = quill.root.innerHTML;
            }
        });
    }

    // 2) Category Sections Switcher
    const radios = document.querySelectorAll('.request-type-radio');
    const buyFields = document.getElementById('buyFields');
    const rentFields = document.getElementById('rentFields');
    const roommateFields = document.getElementById('roommateFields');
    const propertyTypeField = document.getElementById('propertyTypeField');
    const roomsField = document.getElementById('roomsField');
    const imageUploadSection = document.getElementById('imageUploadSection');
    const imageInput = document.getElementById('requestImagesInput');
    const previewGrid = document.getElementById('imagePreviewGrid');

    function updateSections() {
        let selected = 'buy';
        radios.forEach(r => { if (r.checked) selected = r.value; });

        if (selected === 'buy') {
            buyFields.classList.remove('hidden');
            rentFields.classList.add('hidden');
            roommateFields.classList.add('hidden');
            propertyTypeField.classList.remove('hidden');
            roomsField.classList.remove('hidden');
            imageUploadSection.classList.add('hidden');
        } else if (selected === 'rent_monthly' || selected === 'rent_daily') {
            buyFields.classList.add('hidden');
            rentFields.classList.remove('hidden');
            roommateFields.classList.add('hidden');
            propertyTypeField.classList.remove('hidden');
            roomsField.classList.remove('hidden');
            imageUploadSection.classList.add('hidden');
        } else {
            // roommate
            buyFields.classList.add('hidden');
            rentFields.classList.add('hidden');
            roommateFields.classList.remove('hidden');
            propertyTypeField.classList.add('hidden');
            roomsField.classList.add('hidden');
            imageUploadSection.classList.remove('hidden');
        }
    }

    radios.forEach(r => r.addEventListener('change', updateSections));
    updateSections();

    // 3) Image preview
    if (imageInput && previewGrid) {
        imageInput.addEventListener('change', function () {
            previewGrid.innerHTML = '';
            const files = Array.from(this.files);

            files.slice(0, 10).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function (e) {
                    const div = document.createElement('div');
                    div.className = 'relative rounded-xl overflow-hidden aspect-square border border-gray-200 bg-gray-100 shadow-2xs group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                    `;
                    previewGrid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    }
});
</script>
@endpush
@endsection
