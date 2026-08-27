<?php

namespace App\Filament\Admin\Resources\ActivityLogResource\Widgets;

use App\Modules\Shared\Models\ActivityLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ActivityLogStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $today = Carbon::today();

        $totalToday = ActivityLog::where('created_at', '>=', $today)->count();
        $uniqueIpsToday = ActivityLog::where('created_at', '>=', $today)->distinct('ip_address')->count('ip_address');
        $loginsToday = ActivityLog::where('created_at', '>=', $today)->where('action', 'user_login')->count();
        $searchesToday = ActivityLog::where('created_at', '>=', $today)->where('action', 'search_filter')->count();

        return [
            Stat::make('Bugünkü Bütün Hərəkətlər', number_format($totalToday))
                ->description('Saytdakı bütün hadisələr və sorğular')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),

            Stat::make('Unikal Ziyarətçilər (IP)', number_format($uniqueIpsToday))
                ->description('Bugün aktiv olan fərqli ziyarətçi sayı')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Uğurlu Girişlər', number_format($loginsToday))
                ->description('Hesaba daxil olan istifadəçilər')
                ->descriptionIcon('heroicon-m-lock-open')
                ->color('info'),

            Stat::make('Əmlak Axtarışları', number_format($searchesToday))
                ->description('Filtrlənmiş qlobal axtarış sorğuları')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('warning'),
        ];
    }
}
