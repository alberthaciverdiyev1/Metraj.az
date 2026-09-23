<?php

namespace App\Filament\Admin\Resources\LocationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DistrictsRelationManager extends RelationManager
{
    protected static string $relationship = 'districts';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string {
        return __('admin.districts_of_this_city'); }

    protected static function getModelLabel(): ?string {
        return __('admin.district_region_variant'); }

    protected static function getPluralModelLabel(): ?string {
        return __('admin.districts_and_regions'); }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name.az')
                    ->label(__('admin.district_name_az'))
                    ->placeholder(__('admin.example_yasamal_alsancak_lapta'))
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        if (filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),

                Forms\Components\TextInput::make('name.tr')
                    ->label(__('admin.district_name_tr'))
                    ->placeholder(__('admin.example_alsancak_lapta'))
                    ->nullable(),

                Forms\Components\TextInput::make('name.en')
                    ->label(__('admin.district_name_en'))
                    ->placeholder(__('admin.example_alsancak'))
                    ->nullable(),

                Forms\Components\TextInput::make('name.ru')
                    ->label(__('admin.district_name_ru'))
                    ->placeholder(__('admin.example_alsancak_ru'))
                    ->nullable(),

                Forms\Components\TextInput::make('slug')
                    ->label(__('admin.slug_value'))
                    ->placeholder('yasamal, lapta')
                    ->required(),

                Forms\Components\TextInput::make('sort_order')
                    ->label(__('admin.sort_order'))
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktivdir')
                    ->default(true),
            ])->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->recordTitleAttribute('name.az')
            ->columns([
                Tables\Columns\TextColumn::make('name.az')
                    ->label(__('admin.district_az'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name.tr')
                    ->label('Ad (TR)')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('name.en')
                    ->label('Ad (EN)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('name.ru')
                    ->label('Ad (RU)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('admin.sort_order_short'))
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('admin.add_new_district')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
