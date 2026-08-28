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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile(EditProfile::class, isSimple: false)
            ->darkMode(false)
            ->brandName('KibrisKare.com')
            ->brandLogo(asset('images/kibriskarelogo1.png'))
            ->brandLogoHeight('2.2rem')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('Profilim')
                    ->url(fn (): string => EditProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
                'website' => MenuItem::make()
                    ->label('Sayta Keçid')
                    ->url('/')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->openUrlInNewTab(true),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Əmlak və Müraciətlər')
                    ->collapsed(false),
                NavigationGroup::make('İstifadəçilər və Agentliklər')
                    ->collapsed(false),
                NavigationGroup::make('Məzmun və Axtarış')
                    ->collapsed(false),
                NavigationGroup::make('Kataloq və Tənzimləmələr')
                    ->collapsed(true),
            ])
            ->navigationItems([
                NavigationItem::make('Profilim')
                    ->url(fn (): string => EditProfile::getUrl())
                    ->group('Kataloq və Tənzimləmələr')
                    ->icon('heroicon-o-user-circle')
                    ->sort(10),
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([])
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
