<?php

namespace App\Filament\Admin\Widgets;

use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Models\Property;
use Filament\Widgets\ChartWidget;

class PropertiesByStatusChartWidget extends ChartWidget
{
    public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable | null {
        return __('admin.properties_by_status'); }
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '230px';
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $statuses = [
            PropertyStatus::Published->value => ['label' => __('admin.published'), 'color' => '#10b981'],
            PropertyStatus::PendingApproval->value => ['label' => __('admin.pending_approval'), 'color' => '#f59e0b'],
            PropertyStatus::Draft->value => ['label' => 'Qaralama', 'color' => '#6b7280'],
            PropertyStatus::Rejected->value => ['label' => __('admin.rejected'), 'color' => '#ef4444'],
            PropertyStatus::Sold->value => ['label' => __('admin.sold'), 'color' => '#3b82f6'],
            PropertyStatus::Rented->value => ['label' => __('admin.rented_out'), 'color' => '#8b5cf6'],
            PropertyStatus::Archived->value => ['label' => __('admin.archived'), 'color' => '#94a3b8'],
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
        return 'polarArea';
    }
}
