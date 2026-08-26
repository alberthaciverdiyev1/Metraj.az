<?php

namespace App\Filament\Admin\Resources\ActivityLogResource\Pages;

use App\Filament\Admin\Resources\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;
}
