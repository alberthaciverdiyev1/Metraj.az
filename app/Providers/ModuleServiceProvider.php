<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Hər modulun öz Routes/ qovluğunu vahid şəkildə {locale?} prefiksi ilə qeydiyyatdan keçirir.
 * Bütün route() çağırışları URL::defaults(['locale' => ...]) vasitəsilə avtomatik aktiv dil ilə yaranır.
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
        Route::middleware('web')
            ->prefix('{locale?}')
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
