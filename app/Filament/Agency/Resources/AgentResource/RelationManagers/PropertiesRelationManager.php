<?php

namespace App\Filament\Agency\Resources\AgentResource\RelationManagers;

use App\Core\Domain\Property\Enums\PropertyStatus;
use Filament\Facades\Filament;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PropertiesRelationManager extends RelationManager
{
    protected static string $relationship = 'properties';

    protected static ?string $title = 'Rieltorun Əlavə Etdiyi Elanlar';

    protected static ?string $modelLabel = 'Əmlak Elanı';

    protected static ?string $pluralModelLabel = 'Əmlak Elanları';

    public function table(Table $table): Table
    {
        $panelId = Filament::getCurrentPanel()?->getId() ?? 'agency';
        $propertyResource = $panelId === 'agency'
            ? \App\Filament\Agency\Resources\PropertyResource::class
            : \App\Filament\Admin\Resources\PropertyResource::class;

        return $table
            ->defaultSort('id', 'desc')
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Başlıq')
                    ->limit(45)
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Qiymət')
                    ->money('AZN')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('area')
                    ->label('Sahə')
                    ->suffix(' m²')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rooms')
                    ->label('Otaq')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (PropertyStatus $state): string => match ($state) {
                        PropertyStatus::Draft => 'gray',
                        PropertyStatus::PendingApproval => 'warning',
                        PropertyStatus::Published => 'success',
                        PropertyStatus::Rejected => 'danger',
                        PropertyStatus::Sold => 'info',
                        PropertyStatus::Rented => 'info',
                        PropertyStatus::Archived => 'gray',
                    })
                    ->formatStateUsing(fn (PropertyStatus $state): string => $state->label()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Əlavə Olunma Tarixi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(PropertyStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Bax')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record): string => $propertyResource::getUrl('view', ['record' => $record])),

                Tables\Actions\Action::make('edit')
                    ->label('Düzəliş et')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn ($record): string => $propertyResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
