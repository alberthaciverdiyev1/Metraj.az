<div id="moreFiltersModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-md hidden z-[110] flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl w-full max-w-5xl relative flex flex-col max-h-[85vh] shadow-2xl border border-gray-100 overflow-hidden transform scale-100 transition-all">
        <!-- Header -->
        <div class="flex justify-between items-center px-8 py-5 border-b border-gray-100 shrink-0 bg-gray-50/50">
            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <i class="bi bi-sliders text-orange-500 text-lg"></i>
                {{ __('Ətraflı axtarış') }}
            </h3>
            <button type="button" id="closeMoreFilters"
                    class="p-2 hover:bg-gray-200/70 rounded-full text-gray-400 hover:text-gray-700 transition duration-200 flex items-center justify-center">
                <i class="bi bi-x-lg text-sm leading-none"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto px-8 py-4 divide-y divide-gray-100">



            <!-- Alqı-satqı növü Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 py-4">
                <div class="md:col-span-1 text-sm font-semibold text-gray-700">
                    {{ __('Alqı-satqı növü') }}
                </div>
                <div class="md:col-span-3">
                    <div class="flex gap-2 bg-gray-100 p-1 rounded-2xl max-w-xs border border-gray-200/50">
                        <label class="flex-1 cursor-pointer select-none">
                            <input type="radio" name="adType" value="sale" class="hidden peer" {{ request('adType') === 'sale' ? 'checked' : '' }}>
                            <span class="block text-center py-2 text-xs font-bold rounded-xl transition duration-200 peer-checked:bg-white peer-checked:text-orange-500 peer-checked:shadow-md text-gray-600 hover:text-gray-900">
                                {{ __('Alış') }}
                            </span>
                        </label>
                        <label class="flex-1 cursor-pointer select-none">
                            <input type="radio" name="adType" value="rent" class="hidden peer" {{ request('adType') === 'rent' ? 'checked' : '' }}>
                            <span class="block text-center py-2 text-xs font-bold rounded-xl transition duration-200 peer-checked:bg-white peer-checked:text-orange-500 peer-checked:shadow-md text-gray-600 hover:text-gray-900">
                                {{ __('Kirayə') }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Otaq sayı Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 py-4">
                <div class="md:col-span-1 text-sm font-semibold text-gray-700">
                    {{ __('Otaq sayı') }}
                </div>
                <div class="md:col-span-3">
                    <div class="flex flex-wrap gap-2">
                        @foreach(['' => __('Fərqi yoxdur'), '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6+'] as $val => $label)
                            <label class="cursor-pointer select-none">
                                <input type="radio" name="roomCount" value="{{ $val }}" class="hidden peer" {{ request('roomCount') == $val ? 'checked' : '' }}>
                                <span class="inline-flex items-center justify-center min-w-[2.5rem] h-9 px-3.5 border border-gray-200 rounded-xl text-xs font-bold bg-gray-50 text-gray-600 hover:bg-gray-100 hover:border-gray-300 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-200 shadow-sm">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Qiymət & Checkboxes Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 py-4">
                <div class="md:col-span-1 text-sm font-semibold text-gray-700">
                    {{ __('Qiymət, ₼') }}
                </div>
                <div class="md:col-span-3 flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <input type="text" name="minPrice" placeholder="min." value="{{ request('minPrice') }}"
                               class="w-28 px-4 py-2.5 border border-gray-200 bg-gray-50/40 hover:bg-white focus:bg-white rounded-xl text-xs outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm font-bold text-gray-800 placeholder:text-gray-400">
                        <span class="text-gray-300">—</span>
                        <input type="text" name="maxPrice" placeholder="maks." value="{{ request('maxPrice') }}"
                               class="w-28 px-4 py-2.5 border border-gray-200 bg-gray-50/40 hover:bg-white focus:bg-white rounded-xl text-xs outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm font-bold text-gray-800 placeholder:text-gray-400">
                    </div>
                    <div class="flex flex-wrap items-center gap-2 pl-4 border-l border-gray-200">
                        <label class="cursor-pointer select-none">
                            <input type="checkbox" name="hasDeed" value="1" {{ request('hasDeed') ? 'checked' : '' }} class="hidden peer" style="display: none;">
                            <span class="inline-block px-4 py-2.5 border border-gray-200 rounded-xl text-xs font-bold bg-gray-50 text-gray-600 hover:bg-gray-100 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-200 shadow-sm">
                                {{ __('Çıxarış var') }}
                            </span>
                        </label>
                        <label class="cursor-pointer select-none">
                            <input type="checkbox" name="inCredit" value="1" {{ request('inCredit') ? 'checked' : '' }} class="hidden peer" style="display: none;">
                            <span class="inline-block px-4 py-2.5 border border-gray-200 rounded-xl text-xs font-bold bg-gray-50 text-gray-600 hover:bg-gray-100 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-200 shadow-sm">
                                {{ __('İpoteka var') }}
                            </span>
                        </label>
                        <label class="cursor-pointer select-none">
                            <input type="checkbox" name="hasVideo" value="1" {{ request('hasVideo') ? 'checked' : '' }} class="hidden peer" style="display: none;">
                            <span class="inline-block px-4 py-2.5 border border-gray-200 rounded-xl text-xs font-bold bg-gray-50 text-gray-600 hover:bg-gray-100 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-200 shadow-sm">
                                {{ __('Videolu elan') }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Sahə Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 py-4">
                <div class="md:col-span-1 text-sm font-semibold text-gray-700">
                    {{ __('Sahə (m²)') }}
                </div>
                <div class="md:col-span-3">
                    <div class="flex items-center gap-2">
                        <input type="text" name="minArea" placeholder="min." value="{{ request('minArea') }}"
                               class="w-28 px-4 py-2.5 border border-gray-200 bg-gray-50/40 hover:bg-white focus:bg-white rounded-xl text-xs outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm font-bold text-gray-800 placeholder:text-gray-400">
                        <span class="text-gray-300">—</span>
                        <input type="text" name="maxArea" placeholder="maks." value="{{ request('maxArea') }}"
                               class="w-28 px-4 py-2.5 border border-gray-200 bg-gray-50/40 hover:bg-white focus:bg-white rounded-xl text-xs outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm font-bold text-gray-800 placeholder:text-gray-400">
                    </div>
                </div>
            </div>

            <!-- Mərtəbə Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 py-4">
                <div class="md:col-span-1 text-sm font-semibold text-gray-700">
                    {{ __('Mərtəbə') }}
                </div>
                <div class="md:col-span-3">
                    <div class="flex items-center gap-2">
                        <input type="text" name="floorMin" placeholder="min." value="{{ request('floorMin') }}"
                               class="w-28 px-4 py-2.5 border border-gray-200 bg-gray-50/40 hover:bg-white focus:bg-white rounded-xl text-xs outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm font-bold text-gray-800 placeholder:text-gray-400">
                        <span class="text-gray-300">—</span>
                        <input type="text" name="floorMax" placeholder="maks." value="{{ request('floorMax') }}"
                               class="w-28 px-4 py-2.5 border border-gray-200 bg-gray-50/40 hover:bg-white focus:bg-white rounded-xl text-xs outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm font-bold text-gray-800 placeholder:text-gray-400">
                    </div>
                </div>
            </div>

            <!-- Torpaq Sahəsi Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 py-4">
                <div class="md:col-span-1 text-sm font-semibold text-gray-700">
                    {{ __('Torpaq Sahəsi (sot)') }}
                </div>
                <div class="md:col-span-3">
                    <div class="flex items-center gap-2">
                        <input type="text" name="fieldAreaMin" placeholder="min." value="{{ request('fieldAreaMin') }}"
                               class="w-28 px-4 py-2.5 border border-gray-200 bg-gray-50/40 hover:bg-white focus:bg-white rounded-xl text-xs outline-none focus:ring-4 focus:ring-orange-500/10 transition duration-200 shadow-sm font-bold text-gray-800 placeholder:text-gray-400">
                        <span class="text-gray-300">—</span>
                        <input type="text" name="fieldAreaMax" placeholder="maks." value="{{ request('fieldAreaMax') }}"
                               class="w-28 px-4 py-2.5 border border-gray-200 bg-gray-50/40 hover:bg-white focus:bg-white rounded-xl text-xs outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm font-bold text-gray-800 placeholder:text-gray-400">
                    </div>
                </div>
            </div>

            <!-- Dynamic Filters (Loaded from Database / Admin) -->
            @foreach($dynamicFilters ?? [] as $dFilter)
                @php
                    $options = $dFilter->options;
                    $filterName = $dFilter->name['az'] ?? $dFilter->key;
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 py-4">
                    <div class="md:col-span-1 text-sm font-semibold text-gray-700">
                        {{ $filterName }}
                    </div>
                    <div class="md:col-span-3">
                        @if($options->count() > 3)
                            <div class="relative max-w-md">
                                <select name="filter_options[]" class="w-full px-4 py-3 border border-gray-200 bg-gray-50/40 hover:bg-white hover:border-gray-300 focus:bg-white rounded-2xl text-xs outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm appearance-none cursor-pointer font-bold text-gray-700">
                                    <option value="">{{ __('Fərqi yoxdur') }}</option>
                                    @foreach($options as $opt)
                                        <option value="{{ $opt->id }}" {{ in_array($opt->id, (array)request('filter_options', [])) ? 'selected' : '' }}>
                                            {{ $opt->name['az'] ?? $opt->value }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <i class="bi bi-chevron-down text-sm"></i>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($options as $opt)
                                    @php
                                        $isChecked = in_array($opt->id, (array)request('filter_options', []));
                                    @endphp
                                    <label class="cursor-pointer select-none">
                                        <input type="checkbox" name="filter_options[]" value="{{ $opt->id }}" class="hidden peer" style="display: none;" {{ $isChecked ? 'checked' : '' }}>
                                        <span class="inline-block px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold bg-gray-50 text-gray-600 hover:bg-gray-100 hover:border-gray-300 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-200 select-none shadow-sm">
                                            {{ $opt->name['az'] ?? $opt->value }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Satıcı Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 py-4">
                <div class="md:col-span-1 text-sm font-semibold text-gray-700">
                    {{ __('Satıcı') }}
                </div>
                <div class="md:col-span-3">
                    <div class="flex flex-wrap gap-2">
                        <label class="cursor-pointer select-none">
                            <input type="checkbox" name="advertiserType" value="user" {{ request('advertiserType') === 'user' ? 'checked' : '' }} class="hidden peer" style="display: none;">
                            <span class="inline-block px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold bg-gray-50 text-gray-600 hover:bg-gray-100 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-200 shadow-sm">
                                {{ __('Mülkiyyətçi') }}
                            </span>
                        </label>
                        <label class="cursor-pointer select-none">
                            <input type="checkbox" name="advertiserType" value="realtor" {{ request('advertiserType') === 'realtor' ? 'checked' : '' }} class="hidden peer" style="display: none;">
                            <span class="inline-block px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold bg-gray-50 text-gray-600 hover:bg-gray-100 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-200 shadow-sm">
                                {{ __('Agent') }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Kirayə müddəti Row -->
            <div id="rentTypeWrapper" class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 py-4 {{ request('adType') === 'rent' ? '' : 'hidden' }}">
                <div class="md:col-span-1 text-sm font-semibold text-gray-700">
                    {{ __('Kirayə müddəti') }}
                </div>
                <div class="md:col-span-3">
                    <div class="flex flex-wrap gap-2">
                        <label class="cursor-pointer select-none">
                            <input type="radio" name="rentType" value="" {{ !request('rentType') ? 'checked' : '' }} class="hidden peer" style="display: none;">
                            <span class="inline-block px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold bg-gray-50 text-gray-600 hover:bg-gray-100 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-200 shadow-sm">
                                {{ __('Fərqi yoxdur') }}
                            </span>
                        </label>
                        <label class="cursor-pointer select-none">
                            <input type="radio" name="rentType" value="daily" {{ request('rentType') === 'daily' ? 'checked' : '' }} class="hidden peer" style="display: none;">
                            <span class="inline-block px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold bg-gray-50 text-gray-600 hover:bg-gray-100 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-200 shadow-sm">
                                {{ __('Günlük') }}
                            </span>
                        </label>
                        <label class="cursor-pointer select-none">
                            <input type="radio" name="rentType" value="monthly" {{ request('rentType') === 'monthly' ? 'checked' : '' }} class="hidden peer" style="display: none;">
                            <span class="inline-block px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold bg-gray-50 text-gray-600 hover:bg-gray-100 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition duration-200 shadow-sm">
                                {{ __('Aylıq') }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Elanın nömrəsi Row (Ən aşağıda və kiçik) -->
            <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 py-4">
                <div class="md:col-span-1 text-sm font-semibold text-gray-700">
                    {{ __('Elanın nömrəsi') }}
                </div>
                <div class="md:col-span-3">
                    <input type="text" name="adNo" placeholder="123456" value="{{ request('adNo') }}"
                           class="w-36 px-4 py-2.5 border border-gray-200 bg-gray-50/40 hover:bg-white focus:bg-white rounded-xl text-xs outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm font-bold text-gray-800 placeholder:text-gray-400">
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-8 py-5 border-t border-gray-100 bg-gray-50 shrink-0">
            <button type="button" id="closeMoreFiltersBtn"
                    class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-100 hover:text-gray-900 font-bold text-xs transition duration-200 shadow-sm">{{ __('Ləğv et') }}</button>
            <button type="submit"
                    class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold text-xs shadow-md transition duration-200 transform active:scale-95">{{ __('Nəticələri Göstər') }}</button>
        </div>
    </div>
</div>
