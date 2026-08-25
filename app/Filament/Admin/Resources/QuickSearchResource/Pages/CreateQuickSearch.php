<?php

namespace App\Filament\Admin\Resources\QuickSearchResource\Pages;

use App\Filament\Admin\Resources\QuickSearchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuickSearch extends CreateRecord
{
    protected static string $resource = QuickSearchResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
