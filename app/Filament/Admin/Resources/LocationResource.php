<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Location\Models\City;
use App\Filament\Admin\Resources\LocationResource\Pages;
use App\Filament\Admin\Resources\LocationResource\RelationManagers\DistrictsRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LocationResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationGroup(): ?string {
        return __('admin.catalog_and_settings'); }

    public static function getNavigationLabel(): string {
        return __('admin.cities_and_districts'); }

    public static function getModelLabel(): string {
        return __('admin.city'); }

    public static function getPluralModelLabel(): string { return __('admin.cities_and_districts_2'); }

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.city_information'))
                    ->description(__('admin.add_city_hint'))
                    ->schema([
                        Forms\Components\TextInput::make('name.az')
                            ->label(__('admin.city_name_az'))
                            ->placeholder(__('admin.example_baku_girne_lefkosa'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if (filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('name.tr')
                            ->label(__('admin.city_name_tr'))
                            ->placeholder(__('admin.example_girne_lefkosa'))
                            ->nullable(),

                        Forms\Components\TextInput::make('name.en')
                            ->label(__('admin.city_name_en'))
                            ->placeholder(__('admin.example_baku_kyrenia'))
                            ->nullable(),

                        Forms\Components\TextInput::make('name.ru')
                            ->label(__('admin.city_name_ru'))
                            ->placeholder(__('admin.example_baku_kyrenia_ru'))
                            ->nullable(),

                        Forms\Components\TextInput::make('slug')
                            ->label(__('admin.slug_key_code'))
                            ->placeholder('baku, girne')
                            ->required(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('admin.sort_order'))
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktivdir')
                            ->default(true),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name.az')
                    ->label(__('admin.city_az'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name.tr')
                    ->label(__('admin.city_tr'))
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('name.en')
                    ->label(__('admin.city_en'))
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('name.ru')
                    ->label(__('admin.city_ru'))
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('districts_count')
                    ->counts('districts')
                    ->label(__('admin.district_count'))
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('admin.sort_order_short'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktivlik'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DistrictsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
