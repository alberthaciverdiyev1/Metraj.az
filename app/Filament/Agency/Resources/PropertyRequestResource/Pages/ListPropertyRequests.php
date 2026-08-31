<?php

namespace App\Filament\Agency\Resources\PropertyRequestResource\Pages;

use App\Filament\Agency\Resources\PropertyRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListPropertyRequests extends ListRecords
{
    protected static string $resource = PropertyRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
