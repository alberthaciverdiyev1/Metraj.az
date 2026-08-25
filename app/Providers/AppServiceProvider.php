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

        view()->composer('*', function ($view) {
            try {
                $view->with('siteSetting', \App\Modules\Shared\Models\SiteSetting::current());
            } catch (\Throwable $e) {
                // Fallback
            }
        });
    }
}
