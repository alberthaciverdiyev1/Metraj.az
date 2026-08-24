<?php

namespace App\Filament\Admin\Resources\RoommateListingResource\Pages;

use App\Filament\Admin\Resources\RoommateListingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoommateListing extends EditRecord
{
    protected static string $resource = RoommateListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
