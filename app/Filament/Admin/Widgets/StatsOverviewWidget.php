<?php

namespace App\Filament\Admin\Widgets;

use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Agency\Models\Agency;
use App\Modules\Inquiry\Models\Inquiry;
use App\Modules\Property\Models\Property;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Ümumi Əmlak Elanları', Property::count())
                ->description('Bütün statuslar üzrə')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary')
                ->chart([3, 5, 8, 12, 15, 18, 20]),

            Stat::make('Dərc Olunmuş Elanlar', Property::where('status', PropertyStatus::Published)->count())
                ->description('Saytda aktiv görünən')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Təsdiq Gözləyən Elanlar', Property::where('status', PropertyStatus::PendingApproval)->count())
                ->description('Moderasiya olunmalıdır')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Aktiv Agentliklər', Agency::count())
                ->description('Qeydiyyatdan keçmiş')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info'),

            Stat::make('Müştəri Müraciətləri', Inquiry::count())
                ->description('Yeni və aktiv müraciətlər')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('danger'),

            Stat::make('İstifadəçilər', User::count())
                ->description('Sistem istifadəçiləri')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),
        ];
    }
}
