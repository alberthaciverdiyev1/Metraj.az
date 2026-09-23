<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Location\Models\Amenity;
use App\Filament\Admin\Resources\AmenityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AmenityResource extends Resource
{
    protected static ?string $model = Amenity::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    public static function getNavigationGroup(): ?string {
        return __('admin.catalog_and_settings'); }

    public static function getNavigationLabel(): string {
        return __('admin.amenities'); }

    public static function getModelLabel(): string {
        return __('admin.amenity'); }

    public static function getPluralModelLabel(): string {
        return __('admin.amenities'); }

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.amenity_name_multilingual'))
                    ->schema([
                        Forms\Components\TextInput::make('name.az')
                            ->label('Ad (AZ)')
                            ->placeholder(__('admin.example_amenities_az'))
                            ->required(),

                        Forms\Components\TextInput::make('name.tr')
                            ->label('Ad (TR)')
                            ->placeholder(__('admin.example_amenities_tr'))
                            ->nullable(),

                        Forms\Components\TextInput::make('name.en')
                            ->label('Ad (EN)')
                            ->placeholder(__('admin.example_amenities_en'))
                            ->nullable(),

                        Forms\Components\TextInput::make('name.ru')
                            ->label('Ad (RU)')
                            ->placeholder(__('admin.example_amenities_ru'))
                            ->nullable(),
                    ])->columns(4),

                Forms\Components\Section::make(__('admin.additional_information'))
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label(__('admin.icon_code'))
                            ->placeholder('flame, home, banknotes, sparkles')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('category')
                            ->label('Kateqoriya')
                            ->placeholder('utilities, document, financial, building, interior, exterior')
                            ->maxLength(255),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name.az')
                    ->label('Ad (AZ)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name.tr')
                    ->label('Ad (TR)')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kateqoriya')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('icon')
                    ->label(__('admin.icon'))
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('properties_count')
                    ->counts('properties')
                    ->label(__('admin.used_listings'))
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kateqoriya')
                    ->options([
                        'utilities' => __('admin.utilities_services'),
                        'document' => __('admin.document'),
                        'financial' => __('admin.finance'),
                        'building' => 'Bina infrastrukturu',
                        'interior' => __('admin.internal_amenity'),
                        'exterior' => 'Xarici / Balkon',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAmenities::route('/'),
            'create' => Pages\CreateAmenity::route('/create'),
            'edit' => Pages\EditAmenity::route('/{record}/edit'),
        ];
    }
}
