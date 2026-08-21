<?php

namespace App\Filament\Admin\Resources;

use App\Core\Infrastructure\Persistence\Eloquent\Models\City;
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

    protected static ?string $navigationGroup = 'Yerləşmə & Ərazi';

    protected static ?string $navigationLabel = 'Yerləşmələr (Şəhərlər & Rayonlar)';

    protected static ?string $modelLabel = 'Şəhər';

    protected static ?string $pluralModelLabel = 'Şəhərlər və Rayonlar';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Şəhər Məlumatları')
                    ->description('Yeni şəhər əlavə edin. Şəhəri yadda saxladıqdan sonra ona aid rayon və bölqələri idarə edə bilərsiniz.')
                    ->schema([
                        Forms\Components\TextInput::make('name.az')
                            ->label('Şəhər Adı (AZ)')
                            ->placeholder('Məs: Bakı, Girne, Lefkoşa')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if (filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('name.ru')
                            ->label('Şəhər Adı (RU)')
                            ->placeholder('Məs: Баку, Кирения')
                            ->nullable(),

                        Forms\Components\TextInput::make('name.en')
                            ->label('Şəhər Adı (EN)')
                            ->placeholder('Məs: Baku, Kyrenia')
                            ->nullable(),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug / Açar Kod')
                            ->placeholder('baku, girne')
                            ->required(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktivdir')
                            ->default(true),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name.az')
                    ->label('Şəhər (AZ)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name.ru')
                    ->label('Şəhər (RU)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('name.en')
                    ->label('Şəhər (EN)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('districts_count')
                    ->counts('districts')
                    ->label('Rayon / Bölqə Sayı')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
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
