<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Hər modulun öz Routes/ qovluğunu avtomatik qeydiyyatdan keçirir.
 *
 * - Routes: app/Modules/{Module}/Routes/web.php  →  web middleware qrupu ilə yüklənir
 *
 * Bütün Blade view-lar merkezi `resources/views/` altında saxlanılır
 * (modulların içində ayrıca Views/ qovluğu yoxdur).
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Yüklənəcək modullar (qovluq adları).
     */
    protected array $modules = [
        'Property',
        'Agency',
        'Blog',
        'Inquiry',
        'Location',
        'Shared',
    ];

    public function boot(): void
    {
        foreach ($this->modules as $module) {
            $modulePath = app_path("Modules/{$module}");

            $routesPath = "{$modulePath}/Routes/web.php";
            if (is_file($routesPath)) {
                Route::middleware('web')->group(fn () => $this->loadRoutesFrom($routesPath));
            }
        }
    }
}
