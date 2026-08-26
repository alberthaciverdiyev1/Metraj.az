<?php

namespace App\Filament\Admin\Widgets;

use App\Modules\Property\Models\Property;
use App\Modules\Shared\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PropertiesTrendChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Elan və Qeydiyyat Dinamikası (Son 30 Gün)';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 2,
    ];

    protected function getData(): array
    {
        $days = 30;
        $propertyData = [];
        $userData = [];
        $labels = [];

        $now = Carbon::now();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $propertyData[] = Property::whereDate('created_at', $dateString)->count();
            $userData[] = User::whereDate('created_at', $dateString)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Yeni Əmlaklar',
                    'data' => $propertyData,
                    'borderColor' => '#ea580c',
                    'backgroundColor' => 'rgba(234, 88, 12, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Yeni İstifadəçilər',
                    'data' => $userData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.12)',
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
