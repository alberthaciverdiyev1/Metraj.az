<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Filament\Tables\Table::configureUsing(function (\Filament\Tables\Table $table): void {
            $table
                ->paginationPageOptions([20])
                ->defaultPaginationPageOption(20);
        });

        view()->composer(['layouts.app', 'layouts.footer'], function ($view) {
            try {
                $view->with('siteSetting', \App\Modules\Shared\Models\SiteSetting::current());
                $view->with('seoSetting', \App\Modules\Shared\Models\SeoSetting::current());
                $view->with('currentPageSeo', \App\Modules\Shared\Models\PageSeo::findForCurrentRoute());
            } catch (\Throwable $e) {
                // Fallback
            }
        });
    }
}
