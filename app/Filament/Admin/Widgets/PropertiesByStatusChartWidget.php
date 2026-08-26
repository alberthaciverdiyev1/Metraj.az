<?php

namespace App\Filament\Admin\Widgets;

use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Models\Property;
use Filament\Widgets\ChartWidget;

class PropertiesByStatusChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Statuslar Üzrə Əmlaklar';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    protected function getData(): array
    {
        $statuses = [
            PropertyStatus::Published->value => ['label' => 'Dərc olunub', 'color' => '#10b981'],
            PropertyStatus::PendingApproval->value => ['label' => 'Təsdiq gözləyir', 'color' => '#f59e0b'],
            PropertyStatus::Draft->value => ['label' => 'Qaralama', 'color' => '#6b7280'],
            PropertyStatus::Rejected->value => ['label' => 'İmtina edilib', 'color' => '#ef4444'],
            PropertyStatus::Sold->value => ['label' => 'Satılıb', 'color' => '#3b82f6'],
            PropertyStatus::Rented->value => ['label' => 'Kirayə verilib', 'color' => '#8b5cf6'],
            PropertyStatus::Archived->value => ['label' => 'Arxivlənib', 'color' => '#94a3b8'],
        ];

        $labels = [];
        $data = [];
        $backgroundColors = [];

        foreach ($statuses as $statusKey => $info) {
            $count = Property::where('status', $statusKey)->count();
            if ($count > 0) {
                $labels[] = $info['label'];
                $data[] = $count;
                $backgroundColors[] = $info['color'];
            }
        }

        if (empty($data)) {
            $labels = ['Elan yoxdur'];
            $data = [1];
            $backgroundColors = ['#94a3b8'];
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }
}
