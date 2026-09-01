<?php

namespace App\Filament\Admin\Pages;

use App\Modules\Agency\Models\Agency;
use App\Modules\Property\Models\Property;
use App\Modules\PropertyRequest\Models\PropertyRequest;
use App\Modules\Shared\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SystemHealth extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationGroup = 'Kataloq və Tənzimləmələr';
    protected static ?string $navigationLabel = 'Server və Sistem İdarəetməsi';
    protected static ?string $title = 'Server Vəziyyəti və Sistem Alətləri';
    protected static ?int $navigationSort = 7;

    protected static string $view = 'filament.pages.system-health';

    public ?string $lastActionOutput = null;
    public ?string $lastActionTitle = null;
    public ?string $lastActionStatus = null;
    public ?string $lastActionTime = null;

    /**
     * 1. Tam Optimizasiya və Keşləmə
     */
    public function runOptimizeAll(): void
    {
        $start = microtime(true);
        $output = '';

        try {
            Artisan::call('optimize:clear');
            $output .= ">>> php artisan optimize:clear\n" . Artisan::output() . "\n";

            Artisan::call('config:cache');
            $output .= ">>> php artisan config:cache\n" . Artisan::output() . "\n";

            Artisan::call('route:cache');
            $output .= ">>> php artisan route:cache\n" . Artisan::output() . "\n";

            Artisan::call('view:cache');
            $output .= ">>> php artisan view:cache\n" . Artisan::output() . "\n";

            if (function_exists('opcache_reset')) {
                @opcache_reset();
                $output .= ">>> OPcache: Keş uğurla sıfırlandı.\n";
            }

            $duration = round(microtime(true) - $start, 3);
            $this->setActionResult('Tam Sistem Optimizasiyası', $output, 'success', $duration);

            Notification::make()
                ->title('Sistem tam optimizasiya olundu və keşləndi!')
                ->body("İcra müddəti: {$duration} saniyə")
                ->success()
                ->send();
        } catch (\Throwable $e) {
            $this->setActionResult('Tam Optimizasiya Xətası', $e->getMessage(), 'error');
            Notification::make()->title('Optimizasiya zamanı xəta baş verdi')->body($e->getMessage())->danger()->send();
        }
    }

    /**
     * 2. Bütün Keşləri Təmizlə (Optimize Clear)
     */
    public function runClearAllCache(): void
    {
        $start = microtime(true);
        try {
            Artisan::call('optimize:clear');
            $output = Artisan::output();

            if (function_exists('opcache_reset')) {
                @opcache_reset();
                $output .= "\nOPcache sıfırlandı.";
            }

            $duration = round(microtime(true) - $start, 3);
            $this->setActionResult('Bütün Keşlərin Təmizlənməsi', $output, 'success', $duration);

            Notification::make()->title('Bütün keşlər uğurla təmizləndi!')->success()->send();
        } catch (\Throwable $e) {
            $this->setActionResult('Keş Təmizləmə Xətası', $e->getMessage(), 'error');
            Notification::make()->title('Xəta baş verdi')->body($e->getMessage())->danger()->send();
        }
    }

    /**
     * 3. Tətbiq Keşini Təmizlə (cache:clear)
     */
    public function runClearAppCache(): void
    {
        $start = microtime(true);
        try {
            Artisan::call('cache:clear');
            $output = Artisan::output();
            $duration = round(microtime(true) - $start, 3);
            $this->setActionResult('Tətbiq Keşi Təmizləndi', $output, 'success', $duration);
            Notification::make()->title('Tətbiq keşi (Application Cache) təmizləndi!')->success()->send();
        } catch (\Throwable $e) {
            $this->setActionResult('Xəta', $e->getMessage(), 'error');
            Notification::make()->title('Xəta baş verdi')->body($e->getMessage())->danger()->send();
        }
    }

    /**
     * 4. Görünüş Keşini Təmizlə və Yenidən Yığ (view:clear)
     */
    public function runClearViews(): void
    {
        $start = microtime(true);
        try {
            Artisan::call('view:clear');
            $output = ">>> php artisan view:clear\n" . Artisan::output() . "\n";
            Artisan::call('view:cache');
            $output .= ">>> php artisan view:cache\n" . Artisan::output();

            $duration = round(microtime(true) - $start, 3);
            $this->setActionResult('Blade Görünüşləri Yeniləndi', $output, 'success', $duration);
            Notification::make()->title('Blade şablonları təmizləndi və yenidən yığıldı!')->success()->send();
        } catch (\Throwable $e) {
            $this->setActionResult('Xəta', $e->getMessage(), 'error');
            Notification::make()->title('Xəta baş verdi')->body($e->getMessage())->danger()->send();
        }
    }

    /**
     * 5. Konfiqurasiya Keşini Sıfırla (config:clear & config:cache)
     */
    public function runClearConfig(): void
    {
        $start = microtime(true);
        try {
            Artisan::call('config:clear');
            $output = ">>> php artisan config:clear\n" . Artisan::output() . "\n";
            Artisan::call('config:cache');
            $output .= ">>> php artisan config:cache\n" . Artisan::output();

            $duration = round(microtime(true) - $start, 3);
            $this->setActionResult('Konfiqurasiya Keşi Yeniləndi', $output, 'success', $duration);
            Notification::make()->title('Konfiqurasiya (.env və config) yeniləndi!')->success()->send();
        } catch (\Throwable $e) {
            $this->setActionResult('Xəta', $e->getMessage(), 'error');
            Notification::make()->title('Xəta baş verdi')->body($e->getMessage())->danger()->send();
        }
    }

    /**
     * 6. Route Keşini Yenilə (route:clear & route:cache)
     */
    public function runClearRoutes(): void
    {
        $start = microtime(true);
        try {
            Artisan::call('route:clear');
            $output = ">>> php artisan route:clear\n" . Artisan::output() . "\n";
            Artisan::call('route:cache');
            $output .= ">>> php artisan route:cache\n" . Artisan::output();

            $duration = round(microtime(true) - $start, 3);
            $this->setActionResult('Route Keşi Yeniləndi', $output, 'success', $duration);
            Notification::make()->title('Bütün marşrutlar (routes) keşləndi!')->success()->send();
        } catch (\Throwable $e) {
            $this->setActionResult('Xəta', $e->getMessage(), 'error');
            Notification::make()->title('Xəta baş verdi')->body($e->getMessage())->danger()->send();
        }
    }

    /**
     * 7. OPcache Keşini Sıfırla
     */
    public function runResetOpcache(): void
    {
        $start = microtime(true);
        if (function_exists('opcache_reset')) {
            $res = @opcache_reset();
            $duration = round(microtime(true) - $start, 3);
            if ($res) {
                $this->setActionResult('PHP OPcache Sıfırlandı', "PHP Zend OPcache yaddaşı uğurla sıfırlandı və bütün PHP skriptləri yenidən oxundu.", 'success', $duration);
                Notification::make()->title('OPcache uğurla sıfırlandı!')->success()->send();
            } else {
                $this->setActionResult('OPcache Xətası', "OPcache sıfırlana bilmədi və ya aktiv deyil.", 'warning', $duration);
                Notification::make()->title('OPcache sıfırlanmadı')->warning()->send();
            }
        } else {
            $this->setActionResult('OPcache Dəstəklənmir', "opcache_reset funksiyası mövcud deyil.", 'warning');
            Notification::make()->title('OPcache mövcud deyil')->warning()->send();
        }
    }

    /**
     * 8. Storage Link Yoxla və Yenilə (storage:link)
     */
    public function runStorageLink(): void
    {
        $start = microtime(true);
        try {
            Artisan::call('storage:link');
            $output = Artisan::output();
            $duration = round(microtime(true) - $start, 3);
            $this->setActionResult('Storage Simvolik Linki', $output ?: 'Storage link uğurla yoxlanıldı.', 'success', $duration);
            Notification::make()->title('Storage link yoxlanıldı!')->success()->send();
        } catch (\Throwable $e) {
            $this->setActionResult('Xəta', $e->getMessage(), 'error');
            Notification::make()->title('Xəta baş verdi')->body($e->getMessage())->danger()->send();
        }
    }

    /**
     * 9. Log Fayllarını Təmizlə (Truncate laravel.log)
     */
    public function runClearLogs(): void
    {
        $start = microtime(true);
        try {
            $logFile = storage_path('logs/laravel.log');
            if (File::exists($logFile)) {
                File::put($logFile, '');
            }

            // Also clear older daily logs if any
            $logs = File::glob(storage_path('logs/*.log'));
            foreach ($logs as $f) {
                if (basename($f) !== 'laravel.log') {
                    @File::delete($f);
                }
            }

            $duration = round(microtime(true) - $start, 3);
            $this->setActionResult('Log Faylları Təmizləndi', 'Bütün köhnə sistem qeydləri (laravel.log) uğurla təmizləndi.', 'success', $duration);
            Notification::make()->title('Log faylları sıfırlandı!')->success()->send();
        } catch (\Throwable $e) {
            $this->setActionResult('Xəta', $e->getMessage(), 'error');
            Notification::make()->title('Logları təmizləyərkən xəta baş verdi')->body($e->getMessage())->danger()->send();
        }
    }

    /**
     * 10. Queue / Növbə İşləyicilərini Yenidən Başlat
     */
    public function runRestartQueue(): void
    {
        $start = microtime(true);
        try {
            Artisan::call('queue:restart');
            $output = Artisan::output();
            $duration = round(microtime(true) - $start, 3);
            $this->setActionResult('Növbə İşləyiciləri (Queue)', $output ?: 'Queue restart siqnalı göndərildi.', 'success', $duration);
            Notification::make()->title('Növbə işləyiciləri yenidən başladıldı!')->success()->send();
        } catch (\Throwable $e) {
            $this->setActionResult('Xəta', $e->getMessage(), 'error');
            Notification::make()->title('Xəta baş verdi')->body($e->getMessage())->danger()->send();
        }
    }

    protected function setActionResult(string $title, string $output, string $status, ?float $duration = null): void
    {
        $this->lastActionTitle = $title;
        $this->lastActionOutput = trim($output);
        $this->lastActionStatus = $status;
        $this->lastActionTime = now()->format('H:i:s') . ($duration ? " ({$duration}s)" : '');
    }

    /**
     * Bütün Sistem və Server Məlumatlarını toplayır
     */
    public function getSystemMetrics(): array
    {
        // 1. Server & Hardware
        $os = php_uname('s') . ' ' . php_uname('r') . ' (' . php_uname('m') . ')';
        if (File::exists('/etc/os-release')) {
            $osRelease = @parse_ini_file('/etc/os-release');
            if (!empty($osRelease['PRETTY_NAME'])) {
                $os = $osRelease['PRETTY_NAME'] . ' (' . php_uname('r') . ')';
            }
        }

        $uptimeStr = 'Məlum deyil';
        $rawUptime = @file_get_contents('/proc/uptime');
        if ($rawUptime) {
            $sec = (int) explode(' ', $rawUptime)[0];
            $days = floor($sec / 86400);
            $hours = floor(($sec % 86400) / 3600);
            $mins = floor(($sec % 3600) / 60);
            $uptimeStr = "{$days} gün, {$hours} saat, {$mins} dəqiqə";
        }

        $cpuLoad = sys_getloadavg();
        $cpuLoadStr = is_array($cpuLoad) ? implode(' / ', array_map(fn($v) => round($v, 2), $cpuLoad)) : 'N/A';

        // RAM Memory
        $ramTotalMb = 0;
        $ramUsedMb = 0;
        $ramPct = 0;
        $memInfo = @file_get_contents('/proc/meminfo');
        if ($memInfo) {
            preg_match('/MemTotal:\s+(\d+)/', $memInfo, $t);
            preg_match('/MemAvailable:\s+(\d+)/', $memInfo, $a);
            $ramTotalMb = round((($t[1] ?? 0) / 1024), 0);
            $availMb = round((($a[1] ?? 0) / 1024), 0);
            $ramUsedMb = max(0, $ramTotalMb - $availMb);
            $ramPct = $ramTotalMb > 0 ? round(($ramUsedMb / $ramTotalMb) * 100, 1) : 0;
        }

        // Disk Storage
        $diskTotal = @disk_total_space('/') ?: 1;
        $diskFree = @disk_free_space('/') ?: 0;
        $diskUsed = max(0, $diskTotal - $diskFree);
        $diskPct = round(($diskUsed / $diskTotal) * 100, 1);
        $diskTotalGb = round($diskTotal / 1073741824, 1);
        $diskUsedGb = round($diskUsed / 1073741824, 1);
        $diskFreeGb = round($diskFree / 1073741824, 1);

        // 2. PHP & Web Server
        $opcacheStatus = [
            'enabled' => function_exists('opcache_get_status') && is_array(@opcache_get_status()),
            'memory_used' => 'N/A',
            'cached_scripts' => 0,
            'hit_rate' => 0,
        ];
        if ($opcacheStatus['enabled']) {
            $status = @opcache_get_status(false);
            if ($status && isset($status['memory_usage'])) {
                $opcacheStatus['memory_used'] = round($status['memory_usage']['used_memory'] / 1048576, 1) . ' MB';
                $opcacheStatus['cached_scripts'] = $status['opcache_statistics']['num_cached_scripts'] ?? 0;
                $opcacheStatus['hit_rate'] = round($status['opcache_statistics']['opcache_hit_rate'] ?? 0, 1);
            }
        }

        // 3. PostgreSQL Database
        $dbSize = '0 MB';
        $dbVersion = 'PostgreSQL';
        $activeConnections = 1;
        try {
            $sizeRes = DB::select("SELECT pg_size_pretty(pg_database_size(current_database())) as size");
            if (!empty($sizeRes)) $dbSize = $sizeRes[0]->size;

            $verRes = DB::select("SELECT version() as ver");
            if (!empty($verRes)) {
                $dbVersion = explode(' on ', $verRes[0]->ver)[0] ?? $verRes[0]->ver;
            }

            $connRes = DB::select("SELECT count(*) as active FROM pg_stat_activity WHERE datname = current_database()");
            if (!empty($connRes)) $activeConnections = $connRes[0]->active;
        } catch (\Throwable $e) {}

        // 4. Directory Sizes
        $publicStorageSize = $this->formatSize($this->getDirSizeBytes(storage_path('app/public')));
        $frameworkCacheSize = $this->formatSize($this->getDirSizeBytes(storage_path('framework/cache')));
        $frameworkViewsSize = $this->formatSize($this->getDirSizeBytes(storage_path('framework/views')));
        $logsSize = $this->formatSize($this->getDirSizeBytes(storage_path('logs')));

        // 5. Project Counts
        $propertyCount = Property::count();
        $userCount = User::count();
        $agencyCount = Agency::count();
        $requestCount = PropertyRequest::count();

        // 6. PHP Extensions Check
        $extensions = [
            'pdo_pgsql' => ['name' => 'PostgreSQL Driver (PDO)', 'status' => extension_loaded('pdo_pgsql')],
            'gd' => ['name' => 'GD Library (Şəkil Emalı)', 'status' => extension_loaded('gd')],
            'imagick' => ['name' => 'Imagick (Qabaqcıl Şəkil Emalı)', 'status' => extension_loaded('imagick')],
            'redis' => ['name' => 'Redis Driver', 'status' => extension_loaded('redis')],
            'curl' => ['name' => 'cURL (Xarici API İstəkləri)', 'status' => extension_loaded('curl')],
            'mbstring' => ['name' => 'Multibyte String (Çoxdillilik)', 'status' => extension_loaded('mbstring')],
            'intl' => ['name' => 'Intl (Beynəlxalq Lokallaşdırma)', 'status' => extension_loaded('intl')],
            'exif' => ['name' => 'Exif (Foto Metadata)', 'status' => extension_loaded('exif')],
            'zip' => ['name' => 'Zip Archive', 'status' => extension_loaded('zip')],
            'openssl' => ['name' => 'OpenSSL (Təhlükəsizlik/SSL)', 'status' => extension_loaded('openssl')],
        ];

        return [
            'server' => [
                'os' => $os,
                'hostname' => gethostname(),
                'server_ip' => request()->server('SERVER_ADDR') ?: ($_SERVER['SERVER_ADDR'] ?? '188.132.197.77'),
                'web_server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Nginx',
                'uptime' => $uptimeStr,
                'cpu_load' => $cpuLoadStr,
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => app()->environment(),
                'debug_mode' => config('app.debug'),
            ],
            'resources' => [
                'ram_total_mb' => $ramTotalMb,
                'ram_used_mb' => $ramUsedMb,
                'ram_pct' => $ramPct,
                'disk_total_gb' => $diskTotalGb,
                'disk_used_gb' => $diskUsedGb,
                'disk_free_gb' => $diskFreeGb,
                'disk_pct' => $diskPct,
            ],
            'php_ini' => [
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time') . 's',
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'max_input_vars' => ini_get('max_input_vars'),
            ],
            'opcache' => $opcacheStatus,
            'database' => [
                'name' => config('database.connections.pgsql.database'),
                'size' => $dbSize,
                'version' => $dbVersion,
                'connections' => $activeConnections,
            ],
            'storage_sizes' => [
                'public_uploads' => $publicStorageSize,
                'views_cache' => $frameworkViewsSize,
                'framework_cache' => $frameworkCacheSize,
                'logs' => $logsSize,
            ],
            'counts' => [
                'properties' => $propertyCount,
                'users' => $userCount,
                'agencies' => $agencyCount,
                'requests' => $requestCount,
            ],
            'extensions' => $extensions,
        ];
    }

    protected function getDirSizeBytes(string $dir): int
    {
        if (!File::isDirectory($dir)) return 0;
        $size = 0;
        try {
            foreach (File::allFiles($dir) as $file) {
                $size += $file->getSize();
            }
        } catch (\Throwable $e) {}
        return $size;
    }

    protected function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
