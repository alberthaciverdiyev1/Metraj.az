<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Hər modulun öz Routes/ qovluğunu vahid şəkildə {locale} prefiksi ilə qeydiyyatdan keçirir.
 * Bütün 4 dil (az, en, ru, tr) hər zaman prefikslə işləyir.
 * Prefikssiz daxil olan istifadəçilər avtomatik uyğun dil prefiksinə yönləndirilir.
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
        'PropertyRequest',
        'Roommate',
        'Shared',
    ];

    public function boot(): void
    {
        // 1. Dil və Valyuta Dəyişmə Marşrutları və Kök AJAX Servisləri (Prefikssiz birbaşa kökdə)
        Route::middleware('web')->group(function () {
            Route::get('/lang/{lang}', [\App\Modules\Shared\Controllers\LocaleController::class, 'switchLanguage'])->name('lang.switch');
            Route::get('/currency/{code}', [\App\Modules\Shared\Controllers\LocaleController::class, 'switchCurrency'])->name('currency.switch');
            Route::match(['GET', 'POST'], '/listings/{listing}/reveal-phone', [\App\Modules\Property\Controllers\RevealPhoneController::class, 'reveal'])
                ->middleware('throttle:30,1');
            Route::match(['GET', 'POST'], '/properties/{listing}/reveal-phone', [\App\Modules\Property\Controllers\RevealPhoneController::class, 'reveal'])
                ->middleware('throttle:30,1');
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

        // 3. Prefikssiz ana səhifə və prefikssiz kök URL-lər üçün avtomatik dil yönləndirməsi
        Route::middleware('web')->group(function () {
            Route::get('/', function () {
                $locale = session('lang', config('app.locale', 'tr'));
                if (!in_array($locale, ['az', 'en', 'ru', 'tr'], true)) {
                    $locale = 'tr';
                }
                return redirect()->to('/' . $locale);
            });

            Route::fallback(function (\Illuminate\Http\Request $request) {
                // Admin, agency, api, livewire və asset sorğularını yönləndirmə
                if (
                    $request->is('admin*') ||
                    $request->is('agency*') ||
                    $request->is('api*') ||
                    $request->is('livewire*') ||
                    $request->is('build*') ||
                    $request->is('vendor*') ||
                    $request->is('storage*') ||
                    $request->is('*reveal-phone*')
                ) {
                    abort(404);
                }

                $locale = session('lang', config('app.locale', 'tr'));
                if (!in_array($locale, ['az', 'en', 'ru', 'tr'], true)) {
                    $locale = 'tr';
                }

                $path = ltrim($request->path(), '/');
                $query = $request->getQueryString() ? '?' . $request->getQueryString() : '';

                return redirect()->to('/' . $locale . '/' . $path . $query);
            });
        });
    }
}
