@extends('layouts.app')

@section('title', __('Otaq Yoldaşı Elanı Yerləşdir') . ' - Metraj.az')

@section('content')
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
    <form action="{{ route('roommates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="roommateCreateForm">
        @csrf

        <!-- SECTION 1: ELAN NÖVÜ -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">1</span>
                <span>{{ __('Elanın Məqsədi') }}</span>
            </h2>
            <p class="text-xs text-gray-500 mb-5 ml-8">{{ __('Elanınız hansı məqsədlə verilir?') }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- Option 1: Have Room -->
                <label class="cursor-pointer">
                    <input type="radio" name="listing_type" value="have_room" class="peer sr-only" {{ old('listing_type', 'have_room') === 'have_room' ? 'checked' : '' }}>
                    <div class="h-full p-5 rounded-2xl border-2 border-gray-200 peer-checked:border-orange-500 peer-checked:bg-orange-50/40 hover:border-gray-300 transition-all flex flex-col justify-between">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                                <i class="fa-solid fa-door-open"></i>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-orange-500 peer-checked:bg-orange-500 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">{{ __('Evim var, otaq yoldaşı axtarıram') }}</h3>
                            <p class="text-xs text-gray-500">{{ __('Mövcud kirayə və ya şəxsi evinizdə boş otaq / yer üçün yoldaş axtarırsınız.') }}</p>
                        </div>
                    </div>
                </label>

                <!-- Option 2: Need Room -->
                <label class="cursor-pointer">
                    <input type="radio" name="listing_type" value="need_room" class="peer sr-only" {{ old('listing_type') === 'need_room' ? 'checked' : '' }}>
                    <div class="h-full p-5 rounded-2xl border-2 border-gray-200 peer-checked:border-orange-500 peer-checked:bg-orange-50/40 hover:border-gray-300 transition-all flex flex-col justify-between">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-orange-500 peer-checked:bg-orange-500 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">{{ __('Ev axtarıram, ortaq yoldaş axtarıram') }}</h3>
                            <p class="text-xs text-gray-500">{{ __('Büdcəniz var və birgə ev tutub qalmaq üçün yoldaş axtarırsınız.') }}</p>
                        </div>
                    </div>
                </label>

            </div>
        </div>

        <!-- SECTION 2: ƏSAS MƏLUMATLAR VƏ QİYMƏT -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">2</span>
                    <span>{{ __('Elanın Başlığı və Qiyməti') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('Aylıq ödəniş və əsas parametrlər') }}</p>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Elanın Başlığı') }} <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="{{ __('Məs: Nərimanov metrosu yaxınlığında tələbə qız üçün otaq yoldaşı') }}"
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Price -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Aylıq Ödəniş (₼)') }} <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="price" value="{{ old('price') }}" required min="1" step="any"
                               placeholder="150"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl pl-4 pr-12 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-semibold text-gray-500 text-xs sm:text-sm">₼ / ay</span>
                    </div>
                </div>

                <!-- Bills Included Checkbox -->
                <div class="flex items-center sm:pt-6">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer select-none p-3 rounded-xl bg-gray-50 border border-gray-200 hover:bg-gray-100/70 w-full transition">
                        <input type="checkbox" name="bills_included" value="1" {{ old('bills_included') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                        <span class="text-xs sm:text-sm font-semibold text-gray-800">{{ __('Kommunal xərclər qiymətə daxildir') }}</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Stay Duration -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Qalma Müddəti') }}</label>
                    <select name="stay_duration"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="">{{ __('Seçin (Fərqi yoxdur)') }}</option>
                        <option value="Uzunmüddətli" {{ old('stay_duration') === 'Uzunmüddətli' ? 'selected' : '' }}>{{ __('Uzunmüddətli (1 il+)') }}</option>
                        <option value="6 ay+" {{ old('stay_duration') === '6 ay+' ? 'selected' : '' }}>{{ __('6 ay və daha çox') }}</option>
                        <option value="Qısamüddətli" {{ old('stay_duration') === 'Qısamüddətli' ? 'selected' : '' }}>{{ __('Qısamüddətli (1-3 ay)') }}</option>
                    </select>
                </div>

                <!-- Available From -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Köçmə Tarixi') }}</label>
                    <input type="date" name="available_from" value="{{ old('available_from') }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>

                <!-- Total Roommates -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Evdə Ümumi Adam Sayı') }}</label>
                    <input type="number" name="total_roommates" value="{{ old('total_roommates') }}" min="1" max="20" placeholder="2"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>
            </div>
        </div>

        <!-- SECTION 3: MƏKAN -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">3</span>
                    <span>{{ __('Məkan və Ünvan') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('Mənzilin və ya axtarılan ərazinin yerləşməsi') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- City -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Şəhər') }} <span class="text-rose-500">*</span></label>
                    <select name="city_id" id="createCitySelect" required
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

                <!-- Location Note / Metro / Street -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Metro, Qəsəbə və ya Nişangah') }}</label>
                    <input type="text" name="location_note" value="{{ old('location_note') }}"
                           placeholder="{{ __('Məs: 28 May metrosu, Tibb Universitetinin yanı') }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">
                </div>
            </div>
        </div>

        <!-- SECTION 4: OTAQ YOLDAŞI TƏLƏBLƏRİ VƏ İMKANLAR -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">4</span>
                    <span>{{ __('Tələblər və Ev Şəraiti') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('Yoldaş üçün tələbləriniz və mənzildə olan imkanlar') }}</p>
            </div>

            <!-- Gender Preference -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">{{ __('Cinsiyyət Tələbi') }} <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="gender_preference" value="any" class="peer sr-only" {{ old('gender_preference', 'any') === 'any' ? 'checked' : '' }}>
                        <div class="p-3 text-center rounded-xl border-2 border-gray-200 peer-checked:border-orange-500 peer-checked:bg-orange-50/50 hover:bg-gray-50 transition text-xs sm:text-sm font-semibold text-gray-800">
                            <i class="fa-solid fa-users mr-1.5 text-gray-400"></i> {{ __('Fərqi yoxdur') }}
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="gender_preference" value="female" class="peer sr-only" {{ old('gender_preference') === 'female' ? 'checked' : '' }}>
                        <div class="p-3 text-center rounded-xl border-2 border-gray-200 peer-checked:border-pink-500 peer-checked:bg-pink-50/50 hover:bg-gray-50 transition text-xs sm:text-sm font-semibold text-gray-800">
                            <i class="fa-solid fa-venus mr-1.5 text-pink-500"></i> {{ __('Yalnız Xanım') }}
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="gender_preference" value="male" class="peer sr-only" {{ old('gender_preference') === 'male' ? 'checked' : '' }}>
                        <div class="p-3 text-center rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50/50 hover:bg-gray-50 transition text-xs sm:text-sm font-semibold text-gray-800">
                            <i class="fa-solid fa-mars mr-1.5 text-indigo-500"></i> {{ __('Yalnız Bəy') }}
                        </div>
                    </label>
                </div>
            </div>

            <!-- Occupation & Rules -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Məşğuliyyət') }}</label>
                    <select name="occupation_preference"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#f1913d] transition cursor-pointer">
                        <option value="any" {{ old('occupation_preference') === 'any' ? 'selected' : '' }}>{{ __('Fərqi yoxdur') }}</option>
                        <option value="student" {{ old('occupation_preference') === 'student' ? 'selected' : '' }}>{{ __('Yalnız Tələbə') }}</option>
                        <option value="working" {{ old('occupation_preference') === 'working' ? 'selected' : '' }}>{{ __('Yalnız İşləyən') }}</option>
                    </select>
                </div>

                <div class="flex items-center sm:pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="smoker_allowed" value="1" {{ old('smoker_allowed') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                        <span class="text-xs sm:text-sm font-semibold text-gray-700">{{ __('Siqaret çəkmək olar') }}</span>
                    </label>
                </div>

                <div class="flex items-center sm:pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="pet_allowed" value="1" {{ old('pet_allowed') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-4 w-4">
                        <span class="text-xs sm:text-sm font-semibold text-gray-700">{{ __('Ev heyvanı saxlamaq olar') }}</span>
                    </label>
                </div>
            </div>

            <!-- Amenities Checkboxes -->
            <div class="pt-2">
                <label class="block text-xs font-semibold text-gray-700 mb-2">{{ __('Mənzildə Olan Şərait və Təchizatlar') }}</label>
                @php
                    $availableAmenities = [
                        'Wi-Fi İnternet', 'Kondisioner', 'Paltaryuyan', 'Mərkəzi İstilik / Kombi',
                        'Qabyuyan', 'Soyuducu', 'Televizor', 'Balkon', 'Mebel / Çarpayı', 'Lift'
                    ];
                    $selectedAmenities = old('amenities', []);
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2.5">
                    @foreach($availableAmenities as $amenity)
                        <label class="inline-flex items-center gap-2 p-2.5 rounded-xl border border-gray-200 bg-gray-50/50 hover:bg-orange-50/50 transition cursor-pointer text-xs font-medium text-gray-700 select-none">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                                   {{ in_array($amenity, (array)$selectedAmenities) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f1913d] focus:ring-[#f1913d] h-3.5 w-3.5">
                            <span class="truncate">{{ $amenity }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SECTION 5: TƏSVİR VƏ ŞƏKİLLƏR -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">5</span>
                    <span>{{ __('Ətraflı Məlumat və Fotolar') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('Mənzil qaydaları və otaq şəkilləri') }}</p>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Ətraflı Təsvir') }} <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="5" required
                          placeholder="{{ __('Mənzil, otaq, birgə yaşayış qaydaları və istədiyiniz otaq yoldaşı haqqında ətraflı məlumat yazın...') }}"
                          class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-2xl p-4 focus:bg-white focus:outline-none focus:border-[#f1913d] transition">{{ old('description') }}</textarea>
            </div>

            <!-- Image Upload Zone -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Şəkillər Əlavə Et (Maksimum 10 foto)') }}</label>
                <div class="border-2 border-dashed border-gray-300 hover:border-orange-400 rounded-2xl p-6 text-center cursor-pointer transition bg-gray-50/50 relative group"
                     onclick="document.getElementById('imageUploadInput').click()">
                    <input type="file" id="imageUploadInput" name="images[]" multiple accept="image/*" class="sr-only">
                    <div class="w-12 h-12 rounded-full bg-orange-50 group-hover:bg-orange-100 text-orange-500 flex items-center justify-center mx-auto mb-2 text-xl transition">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-semibold text-gray-800 mb-0.5">{{ __('Fotoları yükləmək üçün klikləyin') }}</p>
                    <p class="text-[11px] text-gray-400">{{ __('PNG, JPG, JPEG və ya WEBP (hər biri maks. 8MB)') }}</p>
                </div>
                <div id="imagePreviewContainer" class="grid grid-cols-3 sm:grid-cols-5 gap-3 mt-3 hidden"></div>
            </div>
        </div>

        <!-- SECTION 6: ƏLAQƏ MƏLUMATLARI -->
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs font-bold flex items-center justify-center">6</span>
                    <span>{{ __('Əlaqə Məlumatları') }}</span>
                </h2>
                <p class="text-xs text-gray-500 ml-8">{{ __('Sizinlə əlaqə qurulacaq şəxsi nömrələr') }}</p>
            </div>

            <x-forms.contact-fields />
        </div>

        <!-- Submit Button -->
        <div class="text-center pt-2">
            <button type="submit" id="submitRoommateBtn"
                    class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-[#f1913d] hover:bg-[#e07f2c] text-white font-semibold text-base rounded-2xl shadow-md transition hover:shadow-lg w-full sm:w-auto cursor-pointer">
                <i class="bi bi-check2-circle text-lg"></i>
                <span>{{ __('Elanı Dərc Et') }}</span>
            </button>
        </div>

    </form>

</div>

@push('scripts')
    <script src="{{ asset('js/pages/roommates/create.js') }}"></script>
@endpush
@endsection
