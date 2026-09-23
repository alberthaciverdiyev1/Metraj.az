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
    protected int | string | array $columns = [
        'sm' => 2,
        'lg' => 4,
        'xl' => 4,
    ];

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
        $totalViews = (int) Property::sum('views_count');

        $totalUsers = User::count();
        $newUsersThisWeek = User::where('created_at', '>=', $now->copy()->subDays(7))->count();

        $agencyCount = Agency::count();
        $agentCount = Agent::count();

        $roommateCount = RoommateListing::count();
        $requestCount = PropertyRequest::count();
        $inquiryCount = Inquiry::count();

        return [
            Stat::make(__('admin.total_properties'), number_format($totalProperties))
                ->description(__('admin.last_7_days_plus') . array_sum($propertyTrend))
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary')
                ->chart($propertyTrend),

            Stat::make(__('admin.published_short'), number_format($publishedCount))
                ->description('Saytda aktiv elanlar')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make(__('admin.pending_approval_tr'), number_format($pendingCount))
                ->description($pendingCount > 0 ? __('admin.moderation_required') : __('admin.checked'))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'gray'),

            Stat::make(__('admin.users'), number_format($totalUsers))
                ->description(__('admin.stat_new_users_this_week', ['count' => $newUsersThisWeek]))
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->chart($userTrend),

            Stat::make(__('admin.agencies_and_agents'), "{$agencyCount} / {$agentCount}")
                ->description(__('admin.stat_agencies_agents_desc', ['agencies' => $agencyCount, 'agents' => $agentCount]))
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make(__('admin.roommate_and_request'), "{$roommateCount} / {$requestCount}")
                ->description(__('admin.stat_roommates_requests_desc', ['roommates' => $roommateCount, 'requests' => $requestCount]))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make(__('admin.customer_inquiries'), number_format($inquiryCount))
                ->description(__('admin.incoming_messages_requests'))
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('danger'),

            Stat::make(__('admin.total_view_count'), number_format($totalViews))
                ->description(__('admin.total_views_all'))
                ->descriptionIcon('heroicon-m-eye')
                ->color('gray'),
        ];
    }
}
