<?php

namespace App\Filament\Agency\Widgets;

use App\Modules\Property\Models\Property;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AgencyPropertiesTrendChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '230px';
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'xl' => 2,
    ];

    public function getHeading(): ?string
    {
        return app()->getLocale() === 'tr'
            ? 'Portföy İlan İstatistiği (Son 30 Gün)'
            : (app()->getLocale() === 'az' ? 'Portfel Elan Statistikası (Son 30 Gün)' : 'Portfolio Listings Trend (Last 30 Days)');
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $tenantAgency = $user?->tenantAgency();
        $isOwner = $user?->isTenantOwner() && $tenantAgency;

        $propertyQuery = Property::query();
        if ($isOwner) {
            $propertyQuery->where('agency_id', $tenantAgency->id);
        } else {
            $propertyQuery->where('user_id', $user?->id);
        }

        $days = 30;
        $propertyData = [];
        $labels = [];

        $now = Carbon::now();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $propertyData[] = (clone $propertyQuery)->whereDate('created_at', $dateString)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => app()->getLocale() === 'tr' ? 'Eklenen İlanlar' : (app()->getLocale() === 'az' ? 'Əlavə edilən elanlar' : 'New Listings'),
                    'data' => $propertyData,
                    'borderColor' => '#ea580c',
                    'backgroundColor' => 'rgba(234, 88, 12, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
