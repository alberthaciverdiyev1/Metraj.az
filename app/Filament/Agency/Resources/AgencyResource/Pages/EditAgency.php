<?php

namespace App\Filament\Agency\Resources\AgencyResource\Pages;

use App\Filament\Agency\Resources\AgencyResource;
use Filament\Resources\Pages\EditRecord;

class EditAgency extends EditRecord
{
    protected static string $resource = AgencyResource::class;

    protected static ?string $title = 'Agentlik Məlumatlarını Yenilə';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
