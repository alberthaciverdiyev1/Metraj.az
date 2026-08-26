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

    protected static ?string $navigationGroup = 'Kataloq və Tənzimləmələr';

    protected static ?string $navigationLabel = 'Təchizatlar';

    protected static ?string $modelLabel = 'Təchizat';

    protected static ?string $pluralModelLabel = 'Təchizatlar';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Təchizat Adı (Çoxdilli)')
                    ->schema([
                        Forms\Components\TextInput::make('name.az')
                            ->label('Ad (AZ)')
                            ->placeholder('Məs: Qaz, Lift, Parkinq')
                            ->required(),

                        Forms\Components\TextInput::make('name.tr')
                            ->label('Ad (TR)')
                            ->placeholder('Məs: Doğalgaz, Asansör, Otopark')
                            ->nullable(),

                        Forms\Components\TextInput::make('name.en')
                            ->label('Ad (EN)')
                            ->placeholder('Məs: Gas Supply, Elevator, Parking')
                            ->nullable(),

                        Forms\Components\TextInput::make('name.ru')
                            ->label('Ad (RU)')
                            ->placeholder('Məs: Газ, Лифт, Парковка')
                            ->nullable(),
                    ])->columns(4),

                Forms\Components\Section::make('Əlavə Məlumatlar')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('İkon Kodu')
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
                    ->label('İkon')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('properties_count')
                    ->counts('properties')
                    ->label('İstifadə Olunan Elanlar')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kateqoriya')
                    ->options([
                        'utilities' => 'Kommunal / Xidmətlər',
                        'document' => 'Sənəd',
                        'financial' => 'Maliyyə',
                        'building' => 'Bina infrastrukturu',
                        'interior' => 'Daxili təchizat',
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
