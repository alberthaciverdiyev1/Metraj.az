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
        \Illuminate\Support\Facades\URL::defaults([
            'locale' => config('app.locale', 'tr'),
        ]);

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

        // Telegram Notification Observers
        \App\Modules\Property\Models\Property::created(function ($property) {
            if ($property->status === \App\Modules\Property\Enums\PropertyStatus::PendingApproval || $property->status === 'pending_approval') {
                dispatch(function () use ($property) {
                    try {
                        $property->refresh();
                        app(\App\Services\TelegramBotService::class)->sendNewListingNotification($property);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Telegram notification error (Property): ' . $e->getMessage());
                    }
                })->afterResponse();
            }
        });

        \App\Modules\PropertyRequest\Models\PropertyRequest::created(function ($request) {
            if ($request->status === \App\Modules\PropertyRequest\Enums\RequestStatus::Pending || $request->status === 'pending') {
                dispatch(function () use ($request) {
                    try {
                        $request->refresh();
                        app(\App\Services\TelegramBotService::class)->sendNewListingNotification($request);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Telegram notification error (PropertyRequest): ' . $e->getMessage());
                    }
                })->afterResponse();
            }
        });

        \App\Modules\Roommate\Models\RoommateListing::created(function ($roommate) {
            if ($roommate->status === \App\Modules\Roommate\Enums\RoommateStatus::Pending || $roommate->status === 'pending') {
                dispatch(function () use ($roommate) {
                    try {
                        $roommate->refresh();
                        app(\App\Services\TelegramBotService::class)->sendNewListingNotification($roommate);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Telegram notification error (RoommateListing): ' . $e->getMessage());
                    }
                })->afterResponse();
            }
        });
    }
}
