<!-- BÖLMƏ 2: Məkan və Xəritə -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('2. Məkan və Xəritə') }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">{{ __('Şəhər, rayon və xəritədə dəqiq yeri qeyd edin') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Şəhər') }} <span class="text-rose-500">*</span></label>
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
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Rayon / Qəsəbə') }}</label>
                    <select name="district_id" id="district_id"
                        class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">{{ __('Rayon seçin...') }}</option>
                    </select>
                </div>
            </div>

            <!-- Dəqiq Ünvan (2-way sync) -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Dəqiq Ünvan / Nişangah') }} <span class="text-rose-500">*</span></label>
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
