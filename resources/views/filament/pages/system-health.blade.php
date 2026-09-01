<x-filament-panels::page>
    @php
        $m = $this->getSystemMetrics();
    @endphp

    <div class="space-y-6">

        {{-- 1. Əməliyyatlar və İdarəetmə Alətləri (Quick Actions Toolbar) --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-gray-100 dark:border-gray-800">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-wrench-screwdriver class="w-5 h-5 text-orange-500" />
                        Sistem Optimizasiyası və Keş İdarəetmə Alətləri
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Canlı mühitdə performansı artırmaq, konfiqurasiya və ya şablon dəyişikliklərini dərhal tətbiq etmək üçün istifadə olunur.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span wire:loading.inline-flex class="items-center gap-1.5 px-3 py-1 bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 text-xs font-semibold rounded-lg border border-orange-200/60 dark:border-orange-800/60 animate-pulse">
                        <svg class="animate-spin -ml-1 mr-1 h-3.5 w-3.5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Əməliyyat icra olunur...
                    </span>
                </div>
            </div>

            {{-- Düymələr Şəbəkəsi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                {{-- Tam Optimizasiya --}}
                <button
                    type="button"
                    wire:click="runOptimizeAll"
                    wire:loading.attr="disabled"
                    class="group relative flex flex-col p-3.5 text-left rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 text-white shadow-md hover:shadow-lg hover:from-orange-600 hover:to-amber-700 transition duration-150 active:scale-[0.99] disabled:opacity-50"
                >
                    <div class="flex items-center justify-between w-full">
                        <span class="p-2 rounded-lg bg-white/20 text-white">
                            <x-heroicon-o-bolt class="w-5 h-5" />
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white/25">Tövsiyə olunur</span>
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-sm">Tam Optimizasiya</div>
                        <div class="text-xs text-orange-100 mt-0.5">Bütün keşləri təmizləyir və istehsala uyğun yenidən yığır</div>
                    </div>
                </button>

                {{-- Bütün Keşləri Təmizlə --}}
                <button
                    type="button"
                    wire:click="runClearAllCache"
                    wire:loading.attr="disabled"
                    class="flex flex-col p-3.5 text-left rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 text-gray-900 dark:text-white transition duration-150 active:scale-[0.99] disabled:opacity-50 group"
                >
                    <div class="p-2 rounded-lg bg-orange-100 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 w-max group-hover:bg-orange-500 group-hover:text-white transition">
                        <x-heroicon-o-trash class="w-5 h-5" />
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-sm">Bütün Keşləri Sıfırla</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Optimize Clear (Config, Route, View)</div>
                    </div>
                </button>

                {{-- Tətbiq Keşini Təmizlə --}}
                <button
                    type="button"
                    wire:click="runClearAppCache"
                    wire:loading.attr="disabled"
                    class="flex flex-col p-3.5 text-left rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 text-gray-900 dark:text-white transition duration-150 active:scale-[0.99] disabled:opacity-50 group"
                >
                    <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 w-max group-hover:bg-blue-500 group-hover:text-white transition">
                        <x-heroicon-o-circle-stack class="w-5 h-5" />
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-sm">Tətbiq Keşi (App Cache)</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Sistem və dinamik məlumat keşini silir</div>
                    </div>
                </button>

                {{-- Görünüşləri (Blade) Sıfırla --}}
                <button
                    type="button"
                    wire:click="runClearViews"
                    wire:loading.attr="disabled"
                    class="flex flex-col p-3.5 text-left rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 text-gray-900 dark:text-white transition duration-150 active:scale-[0.99] disabled:opacity-50 group"
                >
                    <div class="p-2 rounded-lg bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 w-max group-hover:bg-emerald-500 group-hover:text-white transition">
                        <x-heroicon-o-eye class="w-5 h-5" />
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-sm">Blade Görünüşləri</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kompilyasiya olunmuş şablonları yeniləyir</div>
                    </div>
                </button>

                {{-- Konfiqurasiya Keşi --}}
                <button
                    type="button"
                    wire:click="runClearConfig"
                    wire:loading.attr="disabled"
                    class="flex flex-col p-3.5 text-left rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 text-gray-900 dark:text-white transition duration-150 active:scale-[0.99] disabled:opacity-50 group"
                >
                    <div class="p-2 rounded-lg bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 w-max group-hover:bg-amber-500 group-hover:text-white transition">
                        <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-sm">Konfiqurasiya (.env)</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Config və .env tənzimləmələrini yeniləyir</div>
                    </div>
                </button>

                {{-- Route Keşi --}}
                <button
                    type="button"
                    wire:click="runClearRoutes"
                    wire:loading.attr="disabled"
                    class="flex flex-col p-3.5 text-left rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 text-gray-900 dark:text-white transition duration-150 active:scale-[0.99] disabled:opacity-50 group"
                >
                    <div class="p-2 rounded-lg bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 w-max group-hover:bg-purple-500 group-hover:text-white transition">
                        <x-heroicon-o-map-pin class="w-5 h-5" />
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-sm">Marşrutlar (Route Cache)</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Bütün URL marşrutlarını təkrar indeksləyir</div>
                    </div>
                </button>

                {{-- OPcache Sıfırla --}}
                <button
                    type="button"
                    wire:click="runResetOpcache"
                    wire:loading.attr="disabled"
                    class="flex flex-col p-3.5 text-left rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 text-gray-900 dark:text-white transition duration-150 active:scale-[0.99] disabled:opacity-50 group"
                >
                    <div class="p-2 rounded-lg bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 w-max group-hover:bg-rose-500 group-hover:text-white transition">
                        <x-heroicon-o-fire class="w-5 h-5" />
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-sm">PHP Zend OPcache</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">PHP RAM kod yaddaşını dərhal boşaldır</div>
                    </div>
                </button>

                {{-- Storage Link --}}
                <button
                    type="button"
                    wire:click="runStorageLink"
                    wire:loading.attr="disabled"
                    class="flex flex-col p-3.5 text-left rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 text-gray-900 dark:text-white transition duration-150 active:scale-[0.99] disabled:opacity-50 group"
                >
                    <div class="p-2 rounded-lg bg-teal-100 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 w-max group-hover:bg-teal-500 group-hover:text-white transition">
                        <x-heroicon-o-link class="w-5 h-5" />
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-sm">Storage Link</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">public/storage simvolik keçidini yoxlayır</div>
                    </div>
                </button>

                {{-- Log Faylını Təmizlə --}}
                <button
                    type="button"
                    wire:click="runClearLogs"
                    wire:confirm="Bütün sistem log fayllarını təmizləmək istədiyinizdən əminsiniz?"
                    wire:loading.attr="disabled"
                    class="flex flex-col p-3.5 text-left rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-red-500 dark:hover:border-red-500 text-gray-900 dark:text-white transition duration-150 active:scale-[0.99] disabled:opacity-50 group"
                >
                    <div class="p-2 rounded-lg bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 w-max group-hover:bg-red-500 group-hover:text-white transition">
                        <x-heroicon-o-document-text class="w-5 h-5" />
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-sm">Log Fayllarını Sıfırla</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">laravel.log faylının ölçüsünü 0 edir</div>
                    </div>
                </button>

                {{-- Növbə (Queue) Restart --}}
                <button
                    type="button"
                    wire:click="runRestartQueue"
                    wire:loading.attr="disabled"
                    class="flex flex-col p-3.5 text-left rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 text-gray-900 dark:text-white transition duration-150 active:scale-[0.99] disabled:opacity-50 group"
                >
                    <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 w-max group-hover:bg-indigo-500 group-hover:text-white transition">
                        <x-heroicon-o-arrow-path class="w-5 h-5" />
                    </div>
                    <div class="mt-3">
                        <div class="font-bold text-sm">Növbələri Yenidən Başlat</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Queue worker-lərə restart siqnalı verir</div>
                    </div>
                </button>
            </div>

            {{-- Əməliyyat Konsol Çıxışı (Əgər varsa) --}}
            @if(!empty($this->lastActionOutput))
                <div class="mt-4 p-4 rounded-xl bg-gray-950 text-gray-100 border border-gray-800 text-xs font-mono space-y-2 shadow-inner">
                    <div class="flex items-center justify-between border-b border-gray-800 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2.5 h-2.5 rounded-full {{ $this->lastActionStatus === 'success' ? 'bg-emerald-500' : ($this->lastActionStatus === 'error' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                            <span class="font-bold text-white">{{ $this->lastActionTitle }}</span>
                            <span class="text-gray-500 text-[11px]">{{ $this->lastActionTime }}</span>
                        </div>
                        <button type="button" wire:click="$set('lastActionOutput', null)" class="text-gray-400 hover:text-white text-xs">Bağla &times;</button>
                    </div>
                    <pre class="overflow-x-auto whitespace-pre-wrap max-h-48 leading-relaxed text-emerald-400">{{ $this->lastActionOutput }}</pre>
                </div>
            @endif
        </div>

        {{-- 2. Resurs İstehlakı (CPU, RAM, Disk) Göstəriciləri --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- RAM İstehlakı --}}
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm font-bold text-gray-900 dark:text-white">
                        <x-heroicon-o-cpu-chip class="w-5 h-5 text-blue-500" />
                        RAM Yaddaş İstehlakı
                    </div>
                    <span class="text-xs font-extrabold px-2.5 py-0.5 rounded-full {{ $m['resources']['ram_pct'] > 85 ? 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-400' : ($m['resources']['ram_pct'] > 70 ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400') }}">
                        {{ $m['resources']['ram_pct'] }}%
                    </span>
                </div>

                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full transition-all duration-500 {{ $m['resources']['ram_pct'] > 85 ? 'bg-red-500' : ($m['resources']['ram_pct'] > 70 ? 'bg-amber-500' : 'bg-blue-500') }}" style="width: {{ min(100, $m['resources']['ram_pct']) }}%"></div>
                </div>

                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 font-medium pt-1">
                    <span>İstifadə: <strong class="text-gray-800 dark:text-gray-200">{{ number_format($m['resources']['ram_used_mb']) }} MB</strong></span>
                    <span>Ümumi: <strong class="text-gray-800 dark:text-gray-200">{{ number_format($m['resources']['ram_total_mb']) }} MB</strong></span>
                </div>
            </div>

            {{-- Disk Həcmi --}}
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm font-bold text-gray-900 dark:text-white">
                        <x-heroicon-o-server class="w-5 h-5 text-emerald-500" />
                        Disk Həcmi (SSD / NVMe)
                    </div>
                    <span class="text-xs font-extrabold px-2.5 py-0.5 rounded-full {{ $m['resources']['disk_pct'] > 90 ? 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-400' : ($m['resources']['disk_pct'] > 75 ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400') }}">
                        {{ $m['resources']['disk_pct'] }}%
                    </span>
                </div>

                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full transition-all duration-500 {{ $m['resources']['disk_pct'] > 90 ? 'bg-red-500' : ($m['resources']['disk_pct'] > 75 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ min(100, $m['resources']['disk_pct']) }}%"></div>
                </div>

                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 font-medium pt-1">
                    <span>Boş sahə: <strong class="text-emerald-600 dark:text-emerald-400">{{ $m['resources']['disk_free_gb'] }} GB</strong></span>
                    <span>Ümumi: <strong class="text-gray-800 dark:text-gray-200">{{ $m['resources']['disk_total_gb'] }} GB</strong></span>
                </div>
            </div>

            {{-- CPU Yükü və Uptime --}}
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm font-bold text-gray-900 dark:text-white">
                        <x-heroicon-o-clock class="w-5 h-5 text-purple-500" />
                        Server Yükü & İş Vaxtı
                    </div>
                    <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400">
                        {{ $m['server']['cpu_load'] }}
                    </span>
                </div>

                <div class="text-xs text-gray-600 dark:text-gray-300">
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-gray-500">CPU Load (1m, 5m, 15m):</span>
                        <strong class="font-mono text-gray-900 dark:text-white">{{ $m['server']['cpu_load'] }}</strong>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-gray-500">Uptime (Aktiv vaxt):</span>
                        <strong class="text-gray-900 dark:text-white text-[11px]">{{ $m['server']['uptime'] }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Məlumat Kartları Şəbəkəsi (Server, PHP, DB, Qovluqlar) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Server & Hostname --}}
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
                <div class="flex items-center gap-2 font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2">
                    <x-heroicon-o-globe-alt class="w-4 h-4 text-orange-500" />
                    Server & Şəbəkə
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Server IP:</span>
                        <strong class="font-mono text-gray-800 dark:text-gray-200">{{ $m['server']['server_ip'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Hostname:</span>
                        <strong class="font-mono text-gray-800 dark:text-gray-200">{{ $m['server']['hostname'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Web Server:</span>
                        <strong class="text-gray-800 dark:text-gray-200">{{ $m['server']['web_server'] }}</strong>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-500 shrink-0">OS / Kernel:</span>
                        <span class="text-right text-[11px] font-medium text-gray-800 dark:text-gray-300">{{ $m['server']['os'] }}</span>
                    </div>
                </div>
            </div>

            {{-- PHP & Laravel Mühiti --}}
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
                <div class="flex items-center gap-2 font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2">
                    <x-heroicon-o-code-bracket class="w-4 h-4 text-blue-500" />
                    PHP & Framework
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">PHP Versiyası:</span>
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-400 font-mono font-bold rounded">PHP {{ $m['server']['php_version'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Laravel:</span>
                        <strong class="font-mono text-gray-800 dark:text-gray-200">v{{ $m['server']['laravel_version'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Mühit (Env):</span>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400 font-bold uppercase text-[10px] rounded">{{ $m['server']['environment'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Debug Rejimi:</span>
                        <span class="px-2 py-0.5 font-bold text-[10px] rounded {{ $m['server']['debug_mode'] ? 'bg-amber-100 text-amber-700 dark:bg-amber-950' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                            {{ $m['server']['debug_mode'] ? 'AKTİV (TRUE)' : 'QAPALI (FALSE)' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- PostgreSQL Verilənlər Bazası --}}
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
                <div class="flex items-center gap-2 font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2">
                    <x-heroicon-o-circle-stack class="w-4 h-4 text-emerald-500" />
                    PostgreSQL Bazası
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Baza Adı:</span>
                        <strong class="font-mono text-gray-800 dark:text-gray-200">{{ $m['database']['name'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">DB Həcmi:</span>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400 font-bold font-mono rounded">{{ $m['database']['size'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Aktiv Qoşulmalar:</span>
                        <strong class="text-gray-800 dark:text-gray-200">{{ $m['database']['connections'] }} ədəd</strong>
                    </div>
                    <div class="flex justify-between items-start">
                        <span class="text-gray-500 shrink-0">Versiya:</span>
                        <span class="text-right text-[11px] font-mono text-gray-800 dark:text-gray-300 truncate max-w-[150px]">{{ $m['database']['version'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Qovluq və Keş Həcmləri --}}
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
                <div class="flex items-center gap-2 font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2">
                    <x-heroicon-o-folder class="w-4 h-4 text-amber-500" />
                    Qovluq və Keş Həcmləri
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Yüklənmiş Şəkillər:</span>
                        <strong class="text-gray-800 dark:text-gray-200 font-mono">{{ $m['storage_sizes']['public_uploads'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Görünüş Keşi (Views):</span>
                        <strong class="text-gray-800 dark:text-gray-200 font-mono">{{ $m['storage_sizes']['views_cache'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Tətbiq Keşi (Cache):</span>
                        <strong class="text-gray-800 dark:text-gray-200 font-mono">{{ $m['storage_sizes']['framework_cache'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Log Qovluğu:</span>
                        <strong class="text-gray-800 dark:text-gray-200 font-mono">{{ $m['storage_sizes']['logs'] }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. PHP Tənzimləmələri & Genişlənmələri (Extensions & Ini) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            {{-- PHP Tənzimləmələri (php.ini) --}}
            <div class="lg:col-span-5 bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
                <div class="flex items-center gap-2 font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-2">
                    <x-heroicon-o-adjustments-horizontal class="w-4 h-4 text-purple-500" />
                    PHP.ini Tənzimləmələri
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800">
                        <div class="text-gray-500 text-[11px]">Memory Limit</div>
                        <strong class="text-gray-900 dark:text-white font-mono text-sm">{{ $m['php_ini']['memory_limit'] }}</strong>
                    </div>
                    <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800">
                        <div class="text-gray-500 text-[11px]">Max Execution Time</div>
                        <strong class="text-gray-900 dark:text-white font-mono text-sm">{{ $m['php_ini']['max_execution_time'] }}</strong>
                    </div>
                    <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800">
                        <div class="text-gray-500 text-[11px]">Upload Max Filesize</div>
                        <strong class="text-gray-900 dark:text-white font-mono text-sm">{{ $m['php_ini']['upload_max_filesize'] }}</strong>
                    </div>
                    <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800">
                        <div class="text-gray-500 text-[11px]">Post Max Size</div>
                        <strong class="text-gray-900 dark:text-white font-mono text-sm">{{ $m['php_ini']['post_max_size'] }}</strong>
                    </div>
                </div>

                {{-- OPcache Status Bar --}}
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-1.5">
                            <x-heroicon-o-fire class="w-4 h-4 text-orange-500" />
                            Zend OPcache
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $m['opcache']['enabled'] ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-red-100 text-red-700' }}">
                            {{ $m['opcache']['enabled'] ? 'AKTİV' : 'QEYRİ-AKTİV' }}
                        </span>
                    </div>
                    @if($m['opcache']['enabled'])
                        <div class="flex items-center justify-between text-[11px] text-gray-500 dark:text-gray-400">
                            <span>Yaddaş: <strong>{{ $m['opcache']['memory_used'] }}</strong></span>
                            <span>Keşlənmiş fayl: <strong>{{ number_format($m['opcache']['cached_scripts']) }}</strong></span>
                            <span>Hit Rate: <strong>{{ $m['opcache']['hit_rate'] }}%</strong></span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- PHP Genişlənmələri (Extensions) --}}
            <div class="lg:col-span-7 bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                    <div class="flex items-center gap-2 font-bold text-sm text-gray-900 dark:text-white">
                        <x-heroicon-o-puzzle-piece class="w-4 h-4 text-teal-500" />
                        Kritik PHP Genişlənmələri və Modulları
                    </div>
                    <span class="text-xs text-gray-400">10/10 Modul Yoxlanıldı</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    @foreach($m['extensions'] as $key => $ext)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800">
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $ext['name'] }}</span>
                            @if($ext['status'])
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-lg border border-emerald-200/50">
                                    <x-heroicon-m-check class="w-3 h-3" />
                                    Aktiv
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/60 px-2 py-0.5 rounded-lg border border-red-200/50">
                                    <x-heroicon-m-x-mark class="w-3 h-3" />
                                    Yoxdur
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
