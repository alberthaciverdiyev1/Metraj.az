<div id="cityFilterModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-md hidden z-[99999] flex items-center justify-center p-4 transition-all duration-300"
     style="z-index: 99999;">
    <div class="bg-white w-[95%] max-w-5xl h-[85vh] rounded-3xl shadow-2xl overflow-hidden flex flex-col border border-gray-100 transform scale-100 transition-all">
        <!-- Header -->
        <div class="flex items-center justify-between px-8 py-5 border-b border-gray-100 shrink-0 bg-gray-50/50">
            <span class="font-bold text-xl text-gray-900 flex items-center gap-2">
                <i class="bi bi-geo-alt text-orange-500 text-lg"></i>
                {{ __('Şəhər, Bölqə və Nişangah') }}
            </span>
            <button id="closeCityModal" class="p-2 hover:bg-gray-200/70 rounded-full text-gray-400 hover:text-gray-700 transition duration-200 flex items-center justify-center">
                <i class="bi bi-x-lg text-sm leading-none"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Left Panel: City List -->
            <div class="w-1/3 border-r border-gray-100 overflow-y-auto p-6 bg-gray-50/50 space-y-4 shrink-0">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('Şəhərlər') }}</h4>
                <div class="flex flex-col gap-2" id="cityButtonContainer">
                    @foreach($cities as $city)
                        <button type="button" data-city-id="{{ $city->id }}"
                                class="city-btn w-full px-4 py-3 text-left rounded-2xl text-sm font-semibold text-gray-700 border border-gray-200/60 bg-white hover:bg-gray-50 hover:border-gray-300 transition duration-200 flex items-center justify-between group">
                            <span>{{ $city->name['az'] ?? $city->value }}</span>
                            <i class="bi bi-chevron-right text-gray-400 opacity-0 group-hover:opacity-100 transition"></i>
                        </button>
                    @endforeach
                </div>
                <!-- Hidden select input to keep compatibility with listing.js -->
                <select id="citySelect" class="hidden">
                    <option value="">{{ __('Şəhər seçin') }}</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('cityId') == $city->id ? 'selected' : '' }}>
                            {{ $city->name['az'] ?? $city->value }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Right Panel: Region & Landmark Tabs + Content -->
            <div class="w-2/3 flex flex-col overflow-hidden relative">
                <!-- Placeholder when no city is selected -->
                <div id="rightPanelPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center bg-white z-10">
                    <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center mb-4">
                        <i class="bi bi-geo text-orange-500 text-2xl animate-bounce"></i>
                    </div>
                    <h5 class="text-base font-bold text-gray-800 mb-1">{{ __('Bölqə və Nişangah axtarışı') }}</h5>
                    <p class="text-xs text-gray-500 max-w-xs">{{ __('Əlaqəli bölqə və nişangahları görmək üçün soldan şəhər seçin.') }}</p>
                </div>

                <!-- Tabs (only visible when city is selected) -->
                <div class="flex gap-2 px-8 py-4 bg-gray-50/50 border-b border-gray-100 shrink-0">
                    <button class="tabBtn px-6 py-2.5 rounded-xl border border-orange-500 text-orange-500 font-bold text-xs bg-orange-50 shadow-sm transition duration-200"
                            data-tab="rayonTab">{{ __('Bölqə') }}</button>
                    <button class="tabBtn px-6 py-2.5 rounded-xl border border-transparent text-gray-600 hover:bg-gray-200/50 font-bold text-xs transition duration-200"
                            data-tab="nishangahTab">{{ __('Nişangah') }}</button>
                </div>

                <!-- Content Area -->
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-6">
                    
                    <!-- Bölqə Section -->
                    <section id="rayonTab" class="tabContent space-y-4">
                        <div class="relative">
                            <input type="text" id="rayonSearch" placeholder="{{ __('Bölqə axtar...') }}"
                                   class="w-full px-4 py-3 border border-gray-200 bg-gray-50/50 focus:bg-white rounded-2xl text-sm outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm placeholder:text-gray-400">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="bi bi-search text-sm"></i>
                            </div>
                        </div>
                        <div id="rayonList" class="grid grid-cols-2 lg:grid-cols-3 gap-3 pt-2"></div>
                        <div id="rayonEmpty" class="text-gray-500 text-sm hidden py-6 text-center">{{ __('Bölqə tapılmadı') }}</div>
                    </section>

                    <!-- Nişangah Section -->
                    <section id="nishangahTab" class="tabContent hidden space-y-4">
                        <div class="relative">
                            <input id="nishangahSearch" type="text" placeholder="{{ __('Nişangah axtar...') }}"
                                   class="w-full px-4 py-3 border border-gray-200 bg-gray-50/50 focus:bg-white rounded-2xl text-sm outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition duration-200 shadow-sm placeholder:text-gray-400">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="bi bi-search text-sm"></i>
                            </div>
                        </div>
                        <div id="nishangahList" class="grid grid-cols-2 lg:grid-cols-3 gap-3 pt-2"></div>
                        <p id="nishangahEmpty" class="text-gray-500 text-sm hidden py-6 text-center">{{ __('Heç nə tapılmadı.') }}</p>
                    </section>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-8 py-5 border-t border-gray-100 bg-gray-50 shrink-0">
            <button type="button" id="resetCityFilters"
                    class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-100 hover:text-gray-900 font-bold text-xs transition duration-200 shadow-sm">
                {{ __('Sıfırla') }}
            </button>
            <button type="button" id="applyCityFilters"
                    class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold text-xs shadow-md transition duration-200 transform active:scale-95 flex items-center gap-2">
                <span id="applyCount" class="bg-white/20 px-2 py-0.5 rounded-md text-[10px]">0</span>
                <span>{{ __('elan göstər') }}</span>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var citySelect = document.getElementById('citySelect');
        var cityButtons = document.querySelectorAll('.city-btn');
        var rightPanelPlaceholder = document.getElementById('rightPanelPlaceholder');
        
        function selectCity(cityId) {
            if (!citySelect) return;
            citySelect.value = cityId;
            citySelect.dispatchEvent(new Event('change'));

            // Toggle active state in city buttons
            cityButtons.forEach(function (btn) {
                var isTarget = btn.getAttribute('data-city-id') == cityId;
                btn.classList.toggle('border-orange-500', isTarget);
                btn.classList.toggle('bg-orange-50/50', isTarget);
                btn.classList.toggle('text-orange-500', isTarget);
                btn.classList.toggle('border-gray-200/60', !isTarget);
                btn.classList.toggle('bg-white', !isTarget);
                btn.classList.toggle('text-gray-700', !isTarget);
            });

            // Hide placeholder to show the region/landmark choices
            if (rightPanelPlaceholder) {
                rightPanelPlaceholder.classList.add('hidden');
            }
        }

        cityButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var cityId = this.getAttribute('data-city-id');
                selectCity(cityId);
            });
        });

        // Initialize state on load if city is already selected
        if (citySelect && citySelect.value) {
            selectCity(citySelect.value);
        }
    });
</script>
