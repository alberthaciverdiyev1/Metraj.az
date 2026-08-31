<?php

namespace App\Providers\Filament;

use App\Filament\Pages\EditProfile;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
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
            ->darkMode(false)
            ->brandName('KibrisKare.com')
            ->brandLogo(asset('images/kibriskarelogo1.png'))
            ->brandLogoHeight('2.2rem')
            ->colors([
                'primary' => Color::Orange,
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn (): string => __('panel.profile'))
                    ->url(fn (): string => EditProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
                'website' => MenuItem::make()
                    ->label(fn (): string => __('panel.visit_site'))
                    ->url('/')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->openUrlInNewTab(true),
            ])
            ->discoverResources(in: app_path('Filament/Agency/Resources'), for: 'App\\Filament\\Agency\\Resources')
            ->discoverPages(in: app_path('Filament/Agency/Pages'), for: 'App\\Filament\\Agency\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->navigationItems([
                NavigationItem::make(fn (): string => __('panel.visit_site'))
                    ->url('/')
                    ->icon('heroicon-o-globe-alt')
                    ->sort(99)
                    ->openUrlInNewTab(true),
            ])
            ->widgets([
                \App\Filament\Agency\Widgets\AgencyStatsOverviewWidget::class,
                \App\Filament\Agency\Widgets\AgencyPropertiesTrendChartWidget::class,
                \App\Filament\Agency\Widgets\AgencyLatestInquiriesTableWidget::class,
                \App\Filament\Agency\Widgets\AgencyLatestPropertiesTableWidget::class,
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
                \App\Modules\Shared\Middleware\SetLocale::class,
            ])
            ->topNavigation()
            ->breadcrumbs(false)
            ->maxContentWidth(\Filament\Support\Enums\MaxWidth::Full)
            ->renderHook(
                'panels::user-menu.before',
                fn () => view('filament.hooks.site-button')
            )
            ->renderHook(
                'panels::head.end',
                fn () => view('filament.head-end')->render()
            )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
