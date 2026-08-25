<x-filament-panels::page>
    <div class="space-y-6">
        @php
            $files = $this->getLogFiles();
            $logs = $this->getParsedLogs();
        @endphp

        {{-- Log Faylları və Filtrlər Paneli --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                {{-- Fayl Seçimi --}}
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                        Log Faylı Seçin
                    </label>
                    <select wire:model.live="selectedFile" class="w-full text-sm rounded-xl border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm bg-gray-50 py-2.5 px-3">
                        @forelse($files as $file)
                            <option value="{{ $file['name'] }}">
                                {{ $file['name'] }} ({{ $file['size'] }} — {{ $file['modified'] }})
                            </option>
                        @empty
                            <option value="">Heç bir log faylı tapılmadı</option>
                        @endforelse
                    </select>
                </div>

                {{-- Səviyyə Filtri (Level) --}}
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                        Log Səviyyəsi
                    </label>
                    <select wire:model.live="filterLevel" class="w-full text-sm rounded-xl border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm bg-gray-50 py-2.5 px-3">
                        <option value="ALL">Hamısı (Bütün Səviyyələr)</option>
                        <option value="ERROR">ERROR / Xəta</option>
                        <option value="CRITICAL">CRITICAL / Kritik</option>
                        <option value="WARNING">WARNING / Xəbərdarlıq</option>
                        <option value="INFO">INFO / Məlumat</option>
                        <option value="DEBUG">DEBUG / Sazlama</option>
                    </select>
                </div>

                {{-- Mətn Axtarışı --}}
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                        Axtarış
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Açar söz və ya xəta mətni..."
                               class="w-full text-sm rounded-xl border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm bg-gray-50 py-2.5 px-3 pl-9">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                {{-- Limit --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                        Göstərilən Say
                    </label>
                    <select wire:model.live="limit" class="w-full text-sm rounded-xl border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm bg-gray-50 py-2.5 px-3">
                        <option value="50">Son 50</option>
                        <option value="100">Son 100</option>
                        <option value="250">Son 250</option>
                        <option value="500">Son 500</option>
                        <option value="0">Hamısı (Limitsiz)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Log Qeydlərinin Siyahısı --}}
        @if(empty($logs))
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-heroicon-o-check-circle class="w-8 h-8 text-green-500" />
                </div>
                <h3 class="text-base font-semibold text-gray-800 mb-1">Loq qeydi tapılmadı və ya fayl boşdur</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">
                    Seçilmiş log faylında heç bir xəta və ya qeyd mövcud deyil, yaxud tətbiq olunmuş filtrə uyğun nəticə yoxdur.
                </p>
            </div>
        @else
            <div class="space-y-3" x-data="{ openLog: null }">
                <div class="flex items-center justify-between text-xs text-gray-500 px-1">
                    <span>Cəmi tapılan qeyd sayı: <strong>{{ count($logs) }}</strong></span>
                    <span>Ən sonuncu qeydlər yuxarıdadır</span>
                </div>

                @foreach($logs as $index => $log)
                    @php
                        $level = strtoupper($log['level']);
                        $badgeClasses = match($level) {
                            'ERROR', 'CRITICAL', 'EMERGENCY', 'ALERT' => 'bg-red-100 text-red-700 border-red-200',
                            'WARNING' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'INFO', 'NOTICE' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'DEBUG' => 'bg-gray-100 text-gray-700 border-gray-200',
                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                        };
                    @endphp

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden transition hover:border-gray-300">
                        {{-- Log Header / Summary Line --}}
                        <div class="p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 cursor-pointer select-none"
                             @click="openLog = (openLog === {{ $index }} ? null : {{ $index }})">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg border {{ $badgeClasses }}">
                                    {{ $level }}
                                </span>
                                <span class="text-xs font-medium text-gray-500 font-mono">
                                    {{ $log['date'] }}
                                </span>
                                <span class="text-[11px] px-2 py-0.5 bg-gray-100 text-gray-600 rounded font-semibold uppercase">
                                    {{ $log['env'] }}
                                </span>
                            </div>

                            <div class="text-xs text-gray-400 flex items-center gap-1.5 ml-auto">
                                <span x-text="openLog === {{ $index }} ? 'Bağla' : 'Detallara bax'"></span>
                                <x-heroicon-m-chevron-down class="w-4 h-4 transition duration-200 transform"
                                                           x-bind:class="openLog === {{ $index }} ? 'rotate-180' : ''" />
                            </div>
                        </div>

                        {{-- Log Message --}}
                        <div class="px-4 pb-3 cursor-pointer" @click="openLog = (openLog === {{ $index }} ? null : {{ $index }})">
                            <p class="text-sm font-semibold text-gray-900 font-mono break-all line-clamp-2">
                                {{ $log['message'] }}
                            </p>
                        </div>

                        {{-- Stacktrace / Full Details Accordion --}}
                        <div x-show="openLog === {{ $index }}" x-collapse x-cloak class="border-t border-gray-100 bg-gray-900 p-4 text-xs font-mono text-gray-200 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] text-gray-400 font-sans font-semibold uppercase tracking-wider">
                                    Tam Stacktrace & Məlumat
                                </span>
                                <button type="button"
                                        x-on:click="navigator.clipboard.writeText($el.closest('.space-y-3').querySelector('pre').innerText); alert('Kopyalandı!')"
                                        class="px-2.5 py-1 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded text-xs transition flex items-center gap-1">
                                    <x-heroicon-o-clipboard class="w-3.5 h-3.5" />
                                    <span>Kopyala</span>
                                </button>
                            </div>

                            <pre class="overflow-x-auto whitespace-pre-wrap break-all text-xs leading-relaxed max-h-96 overflow-y-auto text-emerald-400 bg-black/40 p-3 rounded-xl border border-gray-800 font-mono">{{ $log['message'] . "\n\n" . ($log['stack'] ?? '') }}</pre>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>
