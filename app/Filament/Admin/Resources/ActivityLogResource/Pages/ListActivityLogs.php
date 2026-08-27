<?php

namespace App\Filament\Admin\Resources\ActivityLogResource\Pages;

use App\Filament\Admin\Resources\ActivityLogResource;
use App\Filament\Admin\Resources\ActivityLogResource\Widgets\ActivityLogStatsWidget;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ActivityLogStatsWidget::class,
        ];
    }
}
