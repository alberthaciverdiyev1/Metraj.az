<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Hər modulun öz Routes/ qovluğunu vahid şəkildə {locale} prefiksi ilə qeydiyyatdan keçirir.
 * Bütün 4 dil (az, en, ru, tr) hər zaman prefikslə işləyir.
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
        // 1. Dil və Valyuta Dəyişmə Marşrutları (Prefikssiz birbaşa kökdə)
        Route::middleware('web')->group(function () {
            Route::get('/lang/{lang}', [\App\Modules\Shared\Controllers\LocaleController::class, 'switchLanguage'])->name('lang.switch');
            Route::get('/currency/{code}', [\App\Modules\Shared\Controllers\LocaleController::class, 'switchCurrency'])->name('currency.switch');
        });

        // 2. Bütün Modul Marşrutları ({locale} prefiksi ilə: /tr/..., /az/..., /en/..., /ru/...)
        Route::middleware('web')
            ->prefix('{locale}')
            ->where(['locale' => 'az|en|ru|tr'])
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
