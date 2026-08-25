@extends('layouts.app')

@section('title', __('requests.post_request_title') . ' - KibrisKare.com')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/requests-create.css') }}">
@endpush

@section('content')
<!-- Quill Rich Text Editor Assets -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(isset($breadcrumbs))
        <div class="mb-6">
            @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-2xl mb-8 text-xs sm:text-sm space-y-1">
            <div class="font-semibold flex items-center gap-2">
                <i class="bi bi-exclamation-octagon-fill text-base text-rose-500"></i>
                <span>{{ __('requests.fix_errors') }}</span>
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
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">1</span>
                <span>{{ __('requests.what_are_you_looking_for') }}</span>
            </h2>
            <p class="text-xs text-gray-500 mb-5 ml-8">{{ __('requests.select_matching_category') }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">

                <!-- 1: Almaq İstəyirəm -->
                <label class="cursor-pointer">
                    <input type="radio" name="request_type" value="buy" class="peer sr-only request-type-radio" {{ old('request_type', request('type', 'buy')) === 'buy' ? 'checked' : '' }}>
                    <div class="h-full p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/40 hover:border-gray-300 transition-all flex flex-col justify-between">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg mb-3">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-xs sm:text-sm text-gray-900 mb-0.5">{{ __('requests.want_to_buy') }}</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">{{ __('requests.buy_desc') }}</p>
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
                            <h3 class="font-semibold text-xs sm:text-sm text-gray-900 mb-0.5">{{ __('requests.looking_for_rent') }}</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">{{ __('requests.rent_monthly_desc') }}</p>
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
                            <h3 class="font-semibold text-xs sm:text-sm text-gray-900 mb-0.5">{{ __('requests.daily_stay') }}</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">{{ __('requests.rent_daily_desc') }}</p>
                        </div>
                    </div>
                </label>

                <!-- 4: Otaq Yoldaşı Axtarıram -->
                <label class="cursor-pointer">
                    <input type="radio" name="request_type" value="roommate_have" class="peer sr-only request-type-radio" {{ old('request_type', request('type')) === 'roommate_have' || old('request_type', request('type')) === 'roommate' ? 'checked' : '' }}>
                    <div class="h-full p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-purple-500 peer-checked:bg-purple-50/40 hover:border-gray-300 transition-all flex flex-col justify-between">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg mb-3">
                            <i class="fa-solid fa-people-roof"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-xs sm:text-sm text-gray-900 mb-0.5">{{ __('requests.roommate') }}</h3>
                            <p class="text-[11px] text-gray-500 leading-tight">{{ __('requests.roommate_desc') }}</p>
                        </div>
                    </div>
                </label>

            </div>
        </div>

        <!-- SECTION 2: ƏSAS MƏLUMATLAR -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">2</span>
                    <span>{{ __('requests.main_info') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('requests.main_info_desc') }}</p>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.title_required') }} <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="{{ __('requests.title_placeholder_example') }}"
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <!-- Property Type (for Buy/Rent/Daily) -->
                <div id="propertyTypeField">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.property_type') }}</label>
                    <select name="property_type" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="Mənzil" {{ old('property_type') === 'Mənzil' ? 'selected' : '' }}>{{ __('requests.apartment') }}</option>
                        <option value="Həyət evi" {{ old('property_type') === 'Həyət evi' ? 'selected' : '' }}>{{ __('requests.house') }}</option>
                        <option value="Villa" {{ old('property_type') === 'Villa' ? 'selected' : '' }}>{{ __('requests.villa') }}</option>
                        <option value="Torpaq" {{ old('property_type') === 'Torpaq' ? 'selected' : '' }}>{{ __('requests.land') }}</option>
                        <option value="Obyekt" {{ old('property_type') === 'Obyekt' ? 'selected' : '' }}>{{ __('requests.commercial') }}</option>
                        <option value="Ofis" {{ old('property_type') === 'Ofis' ? 'selected' : '' }}>{{ __('requests.office') }}</option>
                    </select>
                </div>

                <!-- Rooms -->
                <div id="roomsField">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.rooms_count') }}</label>
                    <select name="rooms" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('requests.no_preference') }}</option>
                        <option value="1" {{ old('rooms') === '1' ? 'selected' : '' }}>{{ __('requests.rooms_1') }}</option>
                        <option value="2" {{ old('rooms') === '2' ? 'selected' : '' }}>{{ __('requests.rooms_2') }}</option>
                        <option value="3" {{ old('rooms') === '3' ? 'selected' : '' }}>{{ __('requests.rooms_3') }}</option>
                        <option value="4+" {{ old('rooms') === '4+' ? 'selected' : '' }}>{{ __('requests.rooms_4_plus') }}</option>
                    </select>
                </div>

                <!-- Max Budget -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.budget_azn') }} <span class="text-rose-500">*</span></label>
                    <input type="number" name="budget_max" value="{{ old('budget_max') }}" required placeholder="{{ __('requests.budget_placeholder_example') }}" min="1"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>

            </div>

        </div>

        <!-- SECTION 3: YERLƏŞMƏ VƏ XÜSUSİ TƏLƏBLƏR -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">3</span>
                    <span>{{ __('requests.location_and_terms') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('requests.location_and_terms_desc') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- City -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.city') }} <span class="text-rose-500">*</span></label>
                    <select name="city_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
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
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.location_note_label') }}</label>
                    <input type="text" name="location_note" value="{{ old('location_note') }}"
                           placeholder="{{ __('requests.location_note_placeholder') }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>

            </div>

            <!-- Dynamic Category Specific Checkboxes/Fields -->
            <div id="buyFields" class="pt-2 border-t border-gray-100 flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="has_deed" value="1" {{ old('has_deed') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700">{{ __('requests.only_deed_checkbox') }}</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="mortgage_eligible" value="1" {{ old('mortgage_eligible') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700">{{ __('requests.mortgage_eligible_checkbox') }}</span>
                </label>
            </div>

            <div id="rentFields" class="hidden pt-2 border-t border-gray-100 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.who_will_stay_label') }}</label>
                        <select name="occupancy_type" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                            <option value="Ailə">{{ __('requests.occupancy_family') }}</option>
                            <option value="Tələbələr">{{ __('requests.occupancy_students') }}</option>
                            <option value="İşləyən şəxs">{{ __('requests.occupancy_single') }}</option>
                            <option value="Xarici vətəndaş">{{ __('requests.occupancy_foreigner') }}</option>
                        </select>
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="bills_included" value="1" {{ old('bills_included') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                            <span class="text-xs sm:text-sm font-semibold text-gray-700">{{ __('requests.bills_included_checkbox') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div id="roommateFields" class="hidden pt-2 border-t border-gray-100 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.gender_requirement') }}</label>
                        <select name="gender_preference" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                            <option value="any">{{ __('requests.no_preference') }}</option>
                            <option value="female">{{ __('requests.female_only') }}</option>
                            <option value="male">{{ __('requests.male_only') }}</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <!-- SECTION 4: ƏTRAFLI TƏSVİR (QUİLL RİCH TEXT EDİTOR) -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">4</span>
                    <span>{{ __('requests.detailed_requirements') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('requests.detailed_requirements_desc') }}</p>
            </div>

            <!-- Description via Quill Rich Text Editor -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('requests.detailed_description') }} <span class="text-rose-500">*</span></label>
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
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-purple-600 text-white text-xs font-bold flex items-center justify-center">
                        <i class="fa-solid fa-camera text-[10px]"></i>
                    </span>
                    <span>{{ __('requests.photos_section_title') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('requests.photos_section_desc') }}</p>
            </div>

            <div class="border-2 border-dashed border-gray-200 hover:border-[#f1913d] rounded-2xl p-6 text-center cursor-pointer transition bg-gray-50/50 hover:bg-orange-50/30"
                 onclick="document.getElementById('requestImagesInput').click()">
                <input type="file" name="images[]" id="requestImagesInput" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="bi bi-images"></i>
                </div>
                <p class="text-xs sm:text-sm font-semibold text-gray-800 mb-1">{{ __('requests.click_to_select_photos') }}</p>
                <p class="text-[11px] text-gray-400">{{ __('requests.photo_formats_limit') }}</p>
            </div>

            <!-- Previews -->
            <div id="imagePreviewGrid" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 pt-2"></div>
        </div>

        <!-- SECTION 6: ƏLAQƏ MƏLUMATLARI -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">5</span>
                    <span>{{ __('requests.contact_info_title') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('requests.contact_info_desc') }}</p>
            </div>

            <x-forms.contact-fields />
        </div>

        <!-- Submit Button -->
        <div class="text-center pt-2">
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-[#f1913d] hover:bg-[#e07f2c] text-white font-semibold text-base rounded-2xl shadow-md transition hover:shadow-lg w-full sm:w-auto cursor-pointer">
                <i class="bi bi-check2-circle text-lg"></i>
                <span>{{ __('requests.publish_request_btn') }}</span>
            </button>
        </div>

    </form>

</div>

<script>
    window.requestCreateConfig = {
        quillPlaceholder: "{{ __('requests.quill_placeholder') }}"
    };
</script>
<script src="{{ asset('js/pages/requests/create.js') }}"></script>
@endsection
