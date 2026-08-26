<?php

namespace App\Filament\Admin\Widgets;

use App\Modules\Location\Models\City;
use Filament\Widgets\ChartWidget;

class PropertiesByCityChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Şəhərlər Üzrə Əmlak Bölgüsü';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '230px';
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $cities = City::withCount('properties')->orderByDesc('properties_count')->get();

        $labels = [];
        $data = [];
        $colors = [
            '#f97316', '#3b82f6', '#10b981', '#8b5cf6', '#ec4899', '#eab308', '#06b6d4', '#64748b'
        ];

        foreach ($cities as $city) {
            if ($city->properties_count > 0) {
                $rawName = $city->name;
                $nameStr = is_array($rawName)
                    ? ($rawName['tr'] ?? $rawName['az'] ?? (reset($rawName) ?: $city->slug))
                    : ($rawName ?: $city->slug);
                $labels[] = $nameStr;
                $data[] = $city->properties_count;
            }
        }

        if (empty($data)) {
            $labels = ['Elan yoxdur'];
            $data = [1];
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 10,
                        'font' => ['size' => 10],
                        'padding' => 8,
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
