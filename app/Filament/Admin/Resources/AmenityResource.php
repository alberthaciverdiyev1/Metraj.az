<?php

namespace App\Filament\Admin\Resources;

use App\Core\Infrastructure\Persistence\Eloquent\Models\Amenity;
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

    protected static ?string $navigationGroup = 'Əmlak İdarəetməsi';

    protected static ?string $navigationLabel = 'Təchizatlar & Xüsusiyyətlər';

    protected static ?string $modelLabel = 'Təchizat';

    protected static ?string $pluralModelLabel = 'Təchizatlar';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Təchizat Məlumatları')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Təchizatın Adı')
                            ->placeholder('Məs: Qaz, Kupça, Lift, Mərkəzi istilik sistemi')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('icon')
                            ->label('İkon Kodu')
                            ->placeholder('flame, home, banknotes, sparkles')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('category')
                            ->label('Kateqoriya')
                            ->placeholder('utilities, document, financial, building, interior, exterior')
                            ->maxLength(255),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

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
