<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 * Hər modulun öz Routes/ və Views/ qovluqlarını avtomatik qeydiyyatdan keçirir.
 *
 * - Routes: app/Modules/{Module}/Routes/web.php  →  web middleware qrupu ilə yüklənir
 * - Views:  app/Modules/{Module}/Views           →  view('property::...') kimi namespace ilə
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
            $key = Str::kebab($module); // property, agency, blog, ...

            $routesPath = "{$modulePath}/Routes/web.php";
            if (is_file($routesPath)) {
                Route::middleware('web')->group(fn () => $this->loadRoutesFrom($routesPath));
            }

            $viewsPath = "{$modulePath}/Views";
            if (is_dir($viewsPath)) {
                $this->loadViewsFrom($viewsPath, $key);
            }
        }
    }
}
