<?php

namespace App\Filament\Agency\Resources\PropertyResource\Pages;

use App\Filament\Agency\Resources\PropertyResource;
use App\Filament\Traits\SyncsDynamicFilters;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProperty extends EditRecord
{
    use SyncsDynamicFilters;

    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
