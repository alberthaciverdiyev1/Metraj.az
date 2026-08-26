<?php

namespace App\Filament\Admin\Widgets;

use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use App\Modules\Inquiry\Models\Inquiry;
use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Models\Property;
use App\Modules\PropertyRequest\Models\PropertyRequest;
use App\Modules\Roommate\Models\RoommateListing;
use App\Modules\Shared\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $now = Carbon::now();

        // 7-day sparkline data
        $propertyTrend = [];
        $userTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->format('Y-m-d');
            $propertyTrend[] = Property::whereDate('created_at', $day)->count();
            $userTrend[] = User::whereDate('created_at', $day)->count();
        }

        $totalProperties = Property::count();
        $publishedCount = Property::where('status', PropertyStatus::Published)->count();
        $pendingCount = Property::where('status', PropertyStatus::PendingApproval)->count();
        $vipCount = Property::where('is_vip', true)->orWhere('is_featured', true)->count();
        $totalViews = (int) Property::sum('views_count');

        $totalUsers = User::count();
        $newUsersThisWeek = User::where('created_at', '>=', $now->copy()->subDays(7))->count();

        $agencyCount = Agency::count();
        $agentCount = Agent::count();

        $roommateCount = RoommateListing::count();
        $requestCount = PropertyRequest::count();
        $inquiryCount = Inquiry::count();

        return [
            Stat::make('Ümumi Əmlak', number_format($totalProperties))
                ->description("Son 7 gündə: +".array_sum($propertyTrend))
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary')
                ->chart($propertyTrend),

            Stat::make('Dərc Olunmuş Elanlar', number_format($publishedCount))
                ->description('Saytda aktiv görünən')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Təsdiq Gözləyən', number_format($pendingCount))
                ->description($pendingCount > 0 ? 'Moderasiya tələb olunur' : 'Bütün elanlar yoxlanılıb')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'gray'),

            Stat::make('VIP & Premium Elanlar', number_format($vipCount))
                ->description('Xüsusi vurğulanmış elanlar')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('İstifadəçilər', number_format($totalUsers))
                ->description("Bu həftə: +{$newUsersThisWeek} yeni")
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->chart($userTrend),

            Stat::make('Agentliklər & Agentlər', "{$agencyCount} / {$agentCount}")
                ->description("{$agencyCount} agentlik, {$agentCount} agent")
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make('Otaq Yoldaşı Elanları', number_format($roommateCount))
                ->description('Aktiv otaq elanları')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Əmlak Sifarişləri', number_format($requestCount))
                ->description('Müştəri axtarış tələbləri')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),

            Stat::make('Müştəri Müraciətləri', number_format($inquiryCount))
                ->description('Daxil olmuş mesaj & əlaqələr')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('danger'),

            Stat::make('Ümumi Baxış Sayı', number_format($totalViews))
                ->description('Bütün elanların baxış cəmi')
                ->descriptionIcon('heroicon-m-eye')
                ->color('gray'),
        ];
    }
}
