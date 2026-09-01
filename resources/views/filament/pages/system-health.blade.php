<x-filament-panels::page>
    @php
        $m = $this->getSystemMetrics();
    @endphp

    <div class="space-y-6">

        {{-- 1. Resurs İstehlakı Stat Kartları --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- RAM --}}
            <x-filament::section compact>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">RAM Yaddaş</span>
                    <x-filament::badge :color="$m['resources']['ram_pct'] > 85 ? 'danger' : ($m['resources']['ram_pct'] > 70 ? 'warning' : 'success')" size="xs">
                        {{ $m['resources']['ram_pct'] }}%
                    </x-filament::badge>
                </div>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                        {{ number_format($m['resources']['ram_used_mb']) }} MB
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        / {{ number_format($m['resources']['ram_total_mb']) }} MB
                    </span>
                </div>
                <div class="mt-3 w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden">
                    <div class="h-1.5 rounded-full {{ $m['resources']['ram_pct'] > 85 ? 'bg-red-500' : ($m['resources']['ram_pct'] > 70 ? 'bg-amber-500' : 'bg-primary-600') }}" style="width: {{ min(100, $m['resources']['ram_pct']) }}%"></div>
                </div>
            </x-filament::section>

            {{-- Disk --}}
            <x-filament::section compact>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Disk Sahəsi (SSD)</span>
                    <x-filament::badge :color="$m['resources']['disk_pct'] > 90 ? 'danger' : ($m['resources']['disk_pct'] > 75 ? 'warning' : 'success')" size="xs">
                        {{ $m['resources']['disk_pct'] }}%
                    </x-filament::badge>
                </div>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                        {{ $m['resources']['disk_free_gb'] }} GB
                    </span>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Boş Sahə</span>
                </div>
                <div class="mt-3 w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden">
                    <div class="h-1.5 rounded-full {{ $m['resources']['disk_pct'] > 90 ? 'bg-red-500' : ($m['resources']['disk_pct'] > 75 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ min(100, $m['resources']['disk_pct']) }}%"></div>
                </div>
            </x-filament::section>

            {{-- CPU & Uptime --}}
            <x-filament::section compact>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">CPU Yükü (Load)</span>
                    <span class="font-mono text-xs font-bold text-gray-700 dark:text-gray-300">{{ $m['server']['cpu_load'] }}</span>
                </div>
                <div class="mt-2">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Aktiv İş Vaxtı (Uptime):</div>
                    <div class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $m['server']['uptime'] }}</div>
                </div>
            </x-filament::section>

            {{-- Database --}}
            <x-filament::section compact>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">PostgreSQL Bazası</span>
                    <x-filament::badge color="info" size="xs">
                        {{ $m['database']['connections'] }} Qoşulma
                    </x-filament::badge>
                </div>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                        {{ $m['database']['size'] }}
                    </span>
                    <span class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $m['database']['name'] }}</span>
                </div>
            </x-filament::section>
        </div>

        {{-- 2. Sistem Əməliyyatları və Keş İdarəetməsi --}}
        <x-filament::section
            heading="Sistem Optimizasiyası və Keş Alətləri"
            description="Tətbiq keşlərini təmizləmək və canlı server performansını artırmaq üçün sürətli əmrlər."
            icon="heroicon-o-wrench-screwdriver"
        >
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                <x-filament::button
                    wire:click="runOptimizeAll"
                    color="warning"
                    icon="heroicon-m-bolt"
                    size="sm"
                    class="w-full justify-center"
                >
                    Tam Optimizasiya
                </x-filament::button>

                <x-filament::button
                    wire:click="runClearAllCache"
                    color="gray"
                    icon="heroicon-m-trash"
                    size="sm"
                    class="w-full justify-center"
                >
                    Bütün Keşlər
                </x-filament::button>

                <x-filament::button
                    wire:click="runClearAppCache"
                    color="gray"
                    icon="heroicon-m-circle-stack"
                    size="sm"
                    class="w-full justify-center"
                >
                    Tətbiq Keşi
                </x-filament::button>

                <x-filament::button
                    wire:click="runClearConfig"
                    color="gray"
                    icon="heroicon-m-cog-6-tooth"
                    size="sm"
                    class="w-full justify-center"
                >
                    Konfiqurasiya (.env)
                </x-filament::button>

                <x-filament::button
                    wire:click="runClearRoutes"
                    color="gray"
                    icon="heroicon-m-map-pin"
                    size="sm"
                    class="w-full justify-center"
                >
                    Marşrutlar (Route)
                </x-filament::button>

                <x-filament::button
                    wire:click="runClearViews"
                    color="gray"
                    icon="heroicon-m-eye"
                    size="sm"
                    class="w-full justify-center"
                >
                    Görünüşlər (Blade)
                </x-filament::button>

                <x-filament::button
                    wire:click="runResetOpcache"
                    color="gray"
                    icon="heroicon-m-fire"
                    size="sm"
                    class="w-full justify-center"
                >
                    Zend OPcache
                </x-filament::button>

                <x-filament::button
                    wire:click="runStorageLink"
                    color="gray"
                    icon="heroicon-m-link"
                    size="sm"
                    class="w-full justify-center"
                >
                    Storage Link
                </x-filament::button>

                <x-filament::button
                    wire:click="runRestartQueue"
                    color="gray"
                    icon="heroicon-m-arrow-path"
                    size="sm"
                    class="w-full justify-center"
                >
                    Queue Restart
                </x-filament::button>

                <x-filament::button
                    wire:click="runClearLogs"
                    wire:confirm="Bütün log fayllarını təmizləmək istədiyinizdən əminsiniz?"
                    color="danger"
                    icon="heroicon-m-document-text"
                    size="sm"
                    class="w-full justify-center"
                >
                    Logları Sıfırla
                </x-filament::button>
            </div>

            {{-- Terminal Konsol Çıxışı (Əgər komanda icra edilibsə) --}}
            @if(!empty($this->lastActionOutput))
                <div class="mt-4 p-4 rounded-xl bg-gray-950 text-gray-100 border border-gray-800 text-xs font-mono space-y-2">
                    <div class="flex items-center justify-between border-b border-gray-800 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full {{ $this->lastActionStatus === 'success' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            <span class="font-bold text-white">{{ $this->lastActionTitle }}</span>
                            <span class="text-gray-500 text-[11px]">{{ $this->lastActionTime }}</span>
                        </div>
                        <button type="button" wire:click="$set('lastActionOutput', null)" class="text-gray-400 hover:text-white text-xs">Bağla &times;</button>
                    </div>
                    <pre class="overflow-x-auto whitespace-pre-wrap max-h-48 leading-relaxed text-emerald-400">{{ $this->lastActionOutput }}</pre>
                </div>
            @endif
        </x-filament::section>

        {{-- 3. İki Sütunlu Sistem Məlumatları (Server & PHP Mühiti) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Server & Əməliyyat Sistemi --}}
            <x-filament::section
                heading="Server və Mühit Parametrləri"
                icon="heroicon-o-server"
            >
                <div class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Server IP Ünvanı</span>
                        <span class="font-mono font-semibold text-gray-900 dark:text-white">{{ $m['server']['server_ip'] }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Hostname</span>
                        <span class="font-mono font-medium text-gray-900 dark:text-white">{{ $m['server']['hostname'] }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Veb Server</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $m['server']['web_server'] }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Əməliyyat Sistemi</span>
                        <span class="font-medium text-gray-900 dark:text-white text-xs">{{ $m['server']['os'] }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Laravel Versiyası</span>
                        <x-filament::badge color="primary" size="xs">
                            v{{ $m['server']['laravel_version'] }}
                        </x-filament::badge>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">İşləmə Mühiti (APP_ENV)</span>
                        <x-filament::badge :color="$m['server']['environment'] === 'production' ? 'success' : 'warning'" size="xs">
                            {{ strtoupper($m['server']['environment']) }}
                        </x-filament::badge>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Sazlama Modu (APP_DEBUG)</span>
                        <x-filament::badge :color="$m['server']['debug_mode'] ? 'danger' : 'gray'" size="xs">
                            {{ $m['server']['debug_mode'] ? 'AKTİV' : 'QAPALI' }}
                        </x-filament::badge>
                    </div>
                </div>
            </x-filament::section>

            {{-- PHP & OPcache Konfiqurasiyası --}}
            <x-filament::section
                heading="PHP və OPcache Konfiqurasiyası"
                icon="heroicon-o-code-bracket"
            >
                <div class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">PHP Versiyası</span>
                        <x-filament::badge color="info" size="xs">
                            PHP {{ $m['server']['php_version'] }}
                        </x-filament::badge>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Yaddaş Limiti (memory_limit)</span>
                        <span class="font-mono font-semibold text-gray-900 dark:text-white">{{ $m['php_ini']['memory_limit'] }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Maksimum İcra Müddəti</span>
                        <span class="font-mono font-medium text-gray-900 dark:text-white">{{ $m['php_ini']['max_execution_time'] }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Maksimum Fayl Yükləmə Ölçüsü</span>
                        <span class="font-mono font-medium text-gray-900 dark:text-white">{{ $m['php_ini']['upload_max_filesize'] }} (Post: {{ $m['php_ini']['post_max_size'] }})</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Zend OPcache Statusu</span>
                        <x-filament::badge :color="$m['opcache']['enabled'] ? 'success' : 'danger'" size="xs">
                            {{ $m['opcache']['enabled'] ? 'Aktiv' : 'Deaktiv' }}
                        </x-filament::badge>
                    </div>
                    @if($m['opcache']['enabled'])
                        <div class="py-2.5 flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400">OPcache İstehlakı / Hit Rate</span>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">
                                {{ $m['opcache']['memory_used'] }} &bull; {{ $m['opcache']['hit_rate'] }}% hit
                            </span>
                        </div>
                    @endif
                </div>
            </x-filament::section>
        </div>

        {{-- 4. Qovluq Həcmləri & PHP Modulları --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Qovluq və Keş Həcmləri --}}
            <x-filament::section
                heading="Yaddaş və Keş Qovluqları"
                icon="heroicon-o-folder"
            >
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200/60 dark:border-gray-800">
                        <div class="text-gray-500 dark:text-gray-400">Yüklənmiş Şəkillər</div>
                        <div class="text-base font-bold text-gray-900 dark:text-white mt-1">{{ $m['storage_sizes']['public_uploads'] }}</div>
                        <div class="text-[11px] text-gray-400 truncate mt-0.5">storage/app/public</div>
                    </div>

                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200/60 dark:border-gray-800">
                        <div class="text-gray-500 dark:text-gray-400">Blade Şablon Keşi</div>
                        <div class="text-base font-bold text-gray-900 dark:text-white mt-1">{{ $m['storage_sizes']['views_cache'] }}</div>
                        <div class="text-[11px] text-gray-400 truncate mt-0.5">framework/views</div>
                    </div>

                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200/60 dark:border-gray-800">
                        <div class="text-gray-500 dark:text-gray-400">Tətbiq Keş Qovluğu</div>
                        <div class="text-base font-bold text-gray-900 dark:text-white mt-1">{{ $m['storage_sizes']['framework_cache'] }}</div>
                        <div class="text-[11px] text-gray-400 truncate mt-0.5">framework/cache</div>
                    </div>

                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200/60 dark:border-gray-800">
                        <div class="text-gray-500 dark:text-gray-400">Sistem Logları</div>
                        <div class="text-base font-bold text-gray-900 dark:text-white mt-1">{{ $m['storage_sizes']['logs'] }}</div>
                        <div class="text-[11px] text-gray-400 truncate mt-0.5">storage/logs</div>
                    </div>
                </div>
            </x-filament::section>

            {{-- PHP Modulları (Extensions) --}}
            <x-filament::section
                heading="Kritik PHP Genişlənmələri"
                icon="heroicon-o-puzzle-piece"
            >
                <div class="grid grid-cols-2 gap-2 text-xs">
                    @foreach($m['extensions'] as $key => $ext)
                        <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-200/60 dark:border-gray-800">
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $ext['name'] }}</span>
                            @if($ext['status'])
                                <x-filament::badge color="success" size="xs">Aktiv</x-filament::badge>
                            @else
                                <x-filament::badge color="danger" size="xs">Yoxdur</x-filament::badge>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        </div>

    </div>
</x-filament-panels::page>
