<?php

namespace App\Filament\Admin\Resources\QuickSearchResource\Pages;

use App\Filament\Admin\Resources\QuickSearchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuickSearches extends ListRecords
{
    protected static string $resource = QuickSearchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
