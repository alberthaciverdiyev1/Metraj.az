<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Location\Enums\FilterKey;
use App\Modules\Location\Models\Filter;
use App\Filament\Admin\Resources\FilterResource\Pages;
use App\Filament\Admin\Resources\FilterResource\RelationManagers\FilterOptionsRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FilterResource extends Resource
{
    protected static ?string $model = Filter::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Sistem & İstifadəçilər';

    protected static ?string $navigationLabel = 'Dinamik Filtrlər';

    protected static ?string $modelLabel = 'Filtr';

    protected static ?string $pluralModelLabel = 'Dinamik Filtrlər';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Filtr Parametrləri')
                    ->description('Sayt və admin paneldə istifadə olunacaq dinamik parametr və filtr qrupu')
                    ->schema([
                        Forms\Components\Select::make('key')
                            ->label('Unikal Açar Söz (Enum Key)')
                            ->options(FilterKey::options())
                            ->searchable()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktivdir')
                            ->default(true),

                        Forms\Components\Toggle::make('is_searchable')
                            ->label('Ön Axtarış Blokunda Göstərilsin')
                            ->default(true),
                    ])->columns(4),

                Forms\Components\Section::make('Filtr Adı (Çoxdilli)')
                    ->schema([
                        Forms\Components\TextInput::make('name.az')
                            ->label('Filtr Adı (AZ)')
                            ->placeholder('Məs: Yerləşmə, Əmlakın növü')
                            ->required(),

                        Forms\Components\TextInput::make('name.ru')
                            ->label('Filtr Adı (RU)')
                            ->placeholder('Məs: Расположение')
                            ->nullable(),

                        Forms\Components\TextInput::make('name.en')
                            ->label('Filtr Adı (EN)')
                            ->placeholder('Məs: Location')
                            ->nullable(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Açar Söz (Key)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name.az')
                    ->label('Filtr Adı (AZ)')
                    ->searchable(),

                Tables\Columns\TextColumn::make('options_count')
                    ->counts('options')
                    ->label('Seçim Sayı')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_searchable')
                    ->label('Axtarışda')
                    ->boolean(),

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
                Tables\Filters\TernaryFilter::make('is_searchable')
                    ->label('Axtarışda göstərilən'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            FilterOptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFilters::route('/'),
            'create' => Pages\CreateFilter::route('/create'),
            'edit' => Pages\EditFilter::route('/{record}/edit'),
        ];
    }
}
