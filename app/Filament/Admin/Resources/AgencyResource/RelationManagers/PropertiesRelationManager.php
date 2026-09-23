<?php

namespace App\Filament\Admin\Resources\AgencyResource\RelationManagers;

use App\Modules\Property\Enums\PropertyStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PropertiesRelationManager extends RelationManager
{
    protected static string $relationship = 'properties';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string {
        return __('admin.agency_listings'); }

    protected static function getModelLabel(): ?string {
        return __('admin.property_listing'); }

    protected static function getPluralModelLabel(): ?string {
        return __('admin.property_listings'); }

    public function table(Table $table): Table
    {
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
                    ->label(__('admin.title'))
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('admin.price'))
                    ->money(fn ($record) => $record->currency ?? 'GBP')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('area')
                    ->label(__('admin.area'))
                    ->suffix(' m²')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rooms')
                    ->label('Otaq')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Elan Sahibi')
                    ->searchable()
                    ->placeholder('—'),

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
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(PropertyStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Bax')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record): string => \App\Filament\Admin\Resources\PropertyResource::getUrl('view', ['record' => $record])),

                Tables\Actions\Action::make('edit')
                    ->label(__('admin.edit'))
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn ($record): string => \App\Filament\Admin\Resources\PropertyResource::getUrl('edit', ['record' => $record])),

                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
