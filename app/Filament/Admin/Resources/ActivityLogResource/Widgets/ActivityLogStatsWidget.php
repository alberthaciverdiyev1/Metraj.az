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
            Stat::make(__('admin.all_actions_today'), number_format($totalToday))
                ->description(__('admin.all_events_requests'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),

            Stat::make(__('admin.unique_visitors_ip'), number_format($uniqueIpsToday))
                ->description(__('admin.unique_visitors_hint'))
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make(__('admin.successful_logins'), number_format($loginsToday))
                ->description(__('admin.users_who_logged_in'))
                ->descriptionIcon('heroicon-m-lock-open')
                ->color('info'),

            Stat::make(__('admin.property_searches'), number_format($searchesToday))
                ->description(__('admin.filtered_global_searches'))
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('warning'),
        ];
    }
}
