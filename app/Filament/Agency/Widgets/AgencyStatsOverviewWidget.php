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
                ->description(__('admin.total_portfolio'))
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary')
                ->chart($propertyTrend),

            Stat::make(__('admin.published_short'), number_format($publishedCount))
                ->description(__('admin.active_listings'))
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make(__('admin.pending_approval_tr'), number_format($pendingCount))
                ->description($pendingCount > 0 ? (__('admin.under_moderation')) : (__('admin.all_active')))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'gray'),

            Stat::make(__('admin.view_count'), number_format($totalViews))
                ->description(__('admin.views_of_all_listings'))
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make(__('panel.inquiries'), number_format($inquiriesCount))
                ->description(__('admin.incoming_inquiries_tr'))
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary'),

            Stat::make(__('admin.phone_views'), number_format($phoneRevealsCount))
                ->description(__('admin.phone_reveal_clicks'))
                ->descriptionIcon('heroicon-m-phone')
                ->color('success'),
        ];

        // If Agency Owner -> Show Agent count
        if ($isOwner) {
            $agentsCount = $tenantAgency->agents()->count();
            $stats[] = Stat::make(__('panel.my_agents'), number_format($agentsCount))
                ->description(__('admin.collective_realtors'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning');
        }

        // Market Seeking requests (Arıyorum) - Yalnız Admin, Agentlik sahibi və Rieltorlar üçün
        $isAgentOrAgency = $isOwner || $user?->isAdmin() || (bool) $user?->agent()->exists();
        if ($isAgentOrAgency) {
            $stats[] = Stat::make(__('panel.property_requests'), number_format($marketRequestsCount))
                ->description(__('admin.properties_searched_in_market'))
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('danger');
        }

        return $stats;
    }
}
