<?php

namespace App\Filament\Admin\Resources\RoommateListingResource\Pages;

use App\Filament\Admin\Resources\RoommateListingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoommateListings extends ListRecords
{
    protected static string $resource = RoommateListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
