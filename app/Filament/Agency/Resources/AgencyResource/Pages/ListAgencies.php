<?php

namespace App\Filament\Agency\Resources\AgencyResource\Pages;

use App\Filament\Agency\Resources\AgencyResource;
use Filament\Resources\Pages\ListRecords;

class ListAgencies extends ListRecords
{
    protected static string $resource = AgencyResource::class;

    protected static ?string $title = 'Agentlik Məlumatlarım';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
