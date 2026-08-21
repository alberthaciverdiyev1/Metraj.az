<?php

namespace App\Filament\Admin\Resources\FilterResource\Pages;

use App\Filament\Admin\Resources\FilterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFilters extends ListRecords
{
    protected static string $resource = FilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
