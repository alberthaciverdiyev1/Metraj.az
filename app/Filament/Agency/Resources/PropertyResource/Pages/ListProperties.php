<?php

namespace App\Filament\Agency\Resources\PropertyResource\Pages;

use App\Filament\Agency\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProperties extends ListRecords
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        $currentLayout = session('properties_table_layout', 'table');

        return [
            Actions\CreateAction::make(),

            Actions\Action::make('setTableView')
                ->label(false)
                ->icon('heroicon-o-table-cells')
                ->color($currentLayout === 'table' ? 'primary' : 'gray')
                ->outlined($currentLayout !== 'table')
                ->action(function () {
                    session(['properties_table_layout' => 'table']);
                    $this->redirect(static::getResource()::getUrl('index'));
                }),

            Actions\Action::make('setGridView')
                ->label(false)
                ->icon('heroicon-o-squares-2x2')
                ->color($currentLayout === 'grid' ? 'primary' : 'gray')
                ->outlined($currentLayout !== 'grid')
                ->action(function () {
                    session(['properties_table_layout' => 'grid']);
                    $this->redirect(static::getResource()::getUrl('index'));
                }),
        ];
    }
}
