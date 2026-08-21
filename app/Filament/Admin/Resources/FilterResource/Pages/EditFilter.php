<?php

namespace App\Filament\Admin\Resources\FilterResource\Pages;

use App\Filament\Admin\Resources\FilterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFilter extends EditRecord
{
    protected static string $resource = FilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
