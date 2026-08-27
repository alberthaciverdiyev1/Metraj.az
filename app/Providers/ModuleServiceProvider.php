<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Hər modulun öz Routes/ qovluğunu avtomatik qeydiyyatdan keçirir.
 * Həm birbaşa (default / session locale), həm də dil prefiksləri (/az/..., /en/..., /ru/..., /tr/...) ilə yüklənir.
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
        // 1. Dil prefiksli marşrutlar (/az/..., /en/..., /ru/..., /tr/...)
        Route::middleware('web')
            ->prefix('{locale}')
            ->where(['locale' => 'az|en|ru|tr'])
            ->as('localized.')
            ->group(function () {
                foreach ($this->modules as $module) {
                    $modulePath = app_path("Modules/{$module}");
                    $routesPath = "{$modulePath}/Routes/web.php";
                    if (is_file($routesPath)) {
                        $this->loadRoutesFrom($routesPath);
                    }
                }
            });

        // 2. Əsas / Prefikssiz marşrutlar (/, /satilik, /ilan/... və s.)
        Route::middleware('web')
            ->group(function () {
                foreach ($this->modules as $module) {
                    $modulePath = app_path("Modules/{$module}");
                    $routesPath = "{$modulePath}/Routes/web.php";
                    if (is_file($routesPath)) {
                        $this->loadRoutesFrom($routesPath);
                    }
                }
            });
    }
}
