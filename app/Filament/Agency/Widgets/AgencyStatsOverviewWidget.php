<?php

namespace App\Filament\Agency\Widgets;

use App\Modules\Inquiry\Models\Inquiry;
use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Models\ListingPhoneReveal;
use App\Modules\Property\Models\Property;
use App\Modules\PropertyRequest\Models\PropertyRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AgencyStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columns = [
        'sm' => 2,
        'lg' => 4,
        'xl' => 4,
    ];

    protected function getStats(): array
    {
        $user = Auth::user();
        $tenantAgency = $user?->tenantAgency();
        $isOwner = $user?->isTenantOwner() && $tenantAgency;

        $now = Carbon::now();

        // 1. Property Query Scoping
        $propertyQuery = Property::query();
        if ($isOwner) {
            $propertyQuery->where('agency_id', $tenantAgency->id);
        } else {
            $propertyQuery->where('user_id', $user?->id);
        }

        $propertyIds = (clone $propertyQuery)->pluck('id');

        // 7-day sparkline data for properties
        $propertyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->format('Y-m-d');
            $propertyTrend[] = (clone $propertyQuery)->whereDate('created_at', $day)->count();
        }

        $totalProperties = $propertyIds->count();
        $publishedCount = (clone $propertyQuery)->where('status', PropertyStatus::Published)->count();
        $pendingCount = (clone $propertyQuery)->where('status', PropertyStatus::PendingApproval)->count();
        $totalViews = (int) (clone $propertyQuery)->sum('views_count');

        // 2. Inquiries Query Scoping
        $inquiriesCount = 0;
        if ($propertyIds->isNotEmpty()) {
            $inquiriesCount = Inquiry::whereIn('property_id', $propertyIds)
                ->when($isOwner, fn ($q) => $q->orWhere('agency_id', $tenantAgency->id))
                ->count();
        }

        // 3. Phone reveals
        $phoneRevealsCount = 0;
        if ($propertyIds->isNotEmpty()) {
            $phoneRevealsCount = ListingPhoneReveal::whereIn('listing_id', $propertyIds)->count();
        }

        // 4. Market property requests (Arıyorum)
        $marketRequestsCount = PropertyRequest::count();

        // Stats array
        $stats = [
            Stat::make(__('panel.my_listings'), number_format($totalProperties))
                ->description(app()->getLocale() === 'tr' ? 'Toplam portföy' : (app()->getLocale() === 'az' ? 'Ümumi portfel' : 'Total listings'))
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary')
                ->chart($propertyTrend),

            Stat::make(app()->getLocale() === 'tr' ? 'Yayında Olanlar' : (app()->getLocale() === 'az' ? 'Dərc Olunmuş' : 'Published'), number_format($publishedCount))
                ->description(app()->getLocale() === 'tr' ? 'Aktif elanlar' : (app()->getLocale() === 'az' ? 'Aktiv elanlar' : 'Active listings'))
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make(app()->getLocale() === 'tr' ? 'Təsdiq Gözləyən' : (app()->getLocale() === 'az' ? 'Təsdiq Gözləyən' : 'Pending Approval'), number_format($pendingCount))
                ->description($pendingCount > 0 ? (app()->getLocale() === 'tr' ? 'İnceleme aşamasında' : 'Moderasiyada') : (app()->getLocale() === 'tr' ? 'Bekleyen yok' : 'Hamısı aktiv'))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'gray'),

            Stat::make(app()->getLocale() === 'tr' ? 'Görüntülenme Sayısı' : (app()->getLocale() === 'az' ? 'Baxış Sayı' : 'Total Views'), number_format($totalViews))
                ->description(app()->getLocale() === 'tr' ? 'Tüm ilanların görüntülenmesi' : (app()->getLocale() === 'az' ? 'Bütün elanların baxışı' : 'Total property views'))
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make(__('panel.inquiries'), number_format($inquiriesCount))
                ->description(app()->getLocale() === 'tr' ? 'Müşteri mesajları' : (app()->getLocale() === 'az' ? 'Gələn müraciətlər' : 'Lead messages'))
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary'),

            Stat::make(app()->getLocale() === 'tr' ? 'Numara Gösterimleri' : (app()->getLocale() === 'az' ? 'Nömrə Baxışları' : 'Phone Reveals'), number_format($phoneRevealsCount))
                ->description(app()->getLocale() === 'tr' ? 'Telefonu göster tıklamaları' : (app()->getLocale() === 'az' ? 'Telefonu göstər klikləri' : 'Phone click reveals'))
                ->descriptionIcon('heroicon-m-phone')
                ->color('success'),
        ];

        // If Agency Owner -> Show Agent count
        if ($isOwner) {
            $agentsCount = $tenantAgency->agents()->count();
            $stats[] = Stat::make(__('panel.my_agents'), number_format($agentsCount))
                ->description(app()->getLocale() === 'tr' ? 'Ekip danışmanları' : (app()->getLocale() === 'az' ? 'Kollektiv rieltorları' : 'Team agents'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning');
        }

        // Market Seeking requests
        $stats[] = Stat::make(__('panel.property_requests'), number_format($marketRequestsCount))
            ->description(app()->getLocale() === 'tr' ? 'Pazardaki alıcı/kiracı talepleri' : (app()->getLocale() === 'az' ? 'Bazarda axtarılan əmlaklar' : 'Open market requests'))
            ->descriptionIcon('heroicon-m-megaphone')
            ->color('danger');

        return $stats;
    }
}
