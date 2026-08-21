<?php

namespace App\Filament\Admin\Resources\PropertyResource\Pages;

use App\Filament\Admin\Resources\PropertyResource;
use App\Filament\Traits\SyncsDynamicFilters;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateProperty extends CreateRecord
{
    use SyncsDynamicFilters;

    protected static string $resource = PropertyResource::class;

    public bool $isDraft = false;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Oluştur')
                ->extraAttributes([
                    'style' => 'flex: 9 1 0%; min-width: 0;',
                    'class' => 'w-[88%] md:w-[90%] justify-center font-bold text-base py-3 shadow-md rounded-xl',
                ]),

            Action::make('saveDraft')
                ->label('Draft')
                ->color('warning')
                ->icon('heroicon-o-document-text')
                ->action(function () {
                    $this->isDraft = true;
                    $this->create();
                })
                ->extraAttributes([
                    'style' => 'flex: 1 1 0%; min-width: 90px;',
                    'class' => 'w-[12%] md:w-[10%] justify-center font-bold text-base py-3 shadow-md rounded-xl bg-amber-500 hover:bg-amber-600 text-white border-amber-500',
                ]),
        ];
    }
}
