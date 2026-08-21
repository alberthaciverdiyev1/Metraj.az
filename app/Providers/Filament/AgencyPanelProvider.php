<?php

namespace App\Providers\Filament;

use App\Filament\Pages\EditProfile;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AgencyPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('agency')
            ->path('agency')
            ->login()
            ->profile(EditProfile::class, isSimple: false)
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Agency/Resources'), for: 'App\\Filament\\Agency\\Resources')
            ->discoverPages(in: app_path('Filament/Agency/Pages'), for: 'App\\Filament\\Agency\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Agency/Widgets'), for: 'App\\Filament\\Agency\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->topNavigation()
            ->breadcrumbs(false)
            ->maxContentWidth(\Filament\Support\Enums\MaxWidth::Full)
            ->renderHook(
                'panels::head.end',
                fn () => '
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <style>
                        /* Səhifə, form və cədvəllər üçün yan (sağ/sol) boşluqların yığcam və optimal edilməsi */
                        .fi-main-ctn, .fi-top-nav-ctn {
                            max-width: 97% !important;
                            margin-left: auto !important;
                            margin-right: auto !important;
                            padding-left: 1rem !important;
                            padding-right: 1rem !important;
                        }
                        .fi-page {
                            padding-left: 0.25rem !important;
                            padding-right: 0.25rem !important;
                        }
                        .fi-ta-ctn {
                            border-radius: 14px !important;
                        }
                        /* Grid görünüşündə cədvəl kənarlıqlarını təmizləyib kartı tam açmaq */
                        .fi-ta-content.grid {
                            gap: 1.25rem !important;
                        }
                        .fi-ta-content.grid .fi-ta-record,
                        .fi-ta-content.grid .fi-ta-cell,
                        .fi-ta-content.grid .fi-ta-col-wrp {
                            background: transparent !important;
                            border: none !important;
                            box-shadow: none !important;
                            padding: 0 !important;
                        }
                    </style>
                '
            )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
