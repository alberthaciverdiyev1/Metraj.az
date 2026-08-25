<?php

namespace App\Filament\Admin\Resources\QuickSearchResource\Pages;

use App\Filament\Admin\Resources\QuickSearchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuickSearch extends EditRecord
{
    protected static string $resource = QuickSearchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
