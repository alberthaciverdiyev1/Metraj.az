<?php

namespace App\Filament\Agency\Resources\AgencyResource\Pages;

use App\Filament\Agency\Resources\AgencyResource;
use Filament\Resources\Pages\ListRecords;

class ListAgencies extends ListRecords
{
    protected static string $resource = AgencyResource::class;

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable { return __('admin.my_agency_information'); }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
