<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\QuickSearchResource\Pages;
use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Property\Enums\BuildingType;
use App\Modules\Property\Enums\DealType;
use App\Modules\Property\Enums\PropertyType;
use App\Modules\Property\Enums\RepairType;
use App\Modules\Property\Models\QuickSearch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class QuickSearchResource extends Resource
{
    protected static ?string $model = QuickSearch::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationGroup = 'Məzmun və Axtarış';

    protected static ?string $navigationLabel = 'Sürətli Axtarışlar';

    protected static ?string $modelLabel = 'Axtarış Şablonu';

    protected static ?string $pluralModelLabel = 'Sürətli Axtarışlar';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Başlıq və SEO Linki')
                    ->description('İstifadəçilərin saytda və axtarış sistemlərində görəcəyi başlıq')
                    ->schema([
                        Forms\Components\TextInput::make('title.az')
                            ->label('Başlıq (AZ)')
                            ->placeholder('Məs: Girnədə yeni tikili 2+1 mənzillər')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (! $get('slug') && $state) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('title.tr')
                            ->label('Başlıq (TR)')
                            ->placeholder('Örn: Girne yeni bina 2+1 daireler')
                            ->nullable(),

                        Forms\Components\TextInput::make('title.en')
                            ->label('Başlıq (EN)')
                            ->placeholder('e.g. New building 2+1 apartments in Kyrenia')
                            ->nullable(),

                        Forms\Components\TextInput::make('title.ru')
                            ->label('Başlıq (RU)')
                            ->placeholder('Напр: Новостройки 2+1 квартиры в Гирне')
                            ->nullable(),

                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug (Link teqi)')
                            ->placeholder('girnede-yeni-tikili-2-1-menziller')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Sayt linki: /axtaris/slug-adi olacaq')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Məkan və Əmlak Parametrləri')
                    ->description('Bu teqə kliklədikdə avtomatik tətbiq olunacaq filtrlər')
                    ->schema([
                        Forms\Components\Select::make('city_id')
                            ->label('Şəhər')
                            ->options(City::query()->pluck('name', 'id')->map(function ($name) {
                                return is_array($name) ? ($name['az'] ?? reset($name)) : $name;
                            }))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('district_id', null))
                            ->nullable(),

                        Forms\Components\Select::make('district_id')
                            ->label('Rayon / Qəsəbə')
                            ->options(function (Get $get) {
                                $cityId = $get('city_id');
                                if (! $cityId) {
                                    return [];
                                }
                                return District::query()
                                    ->where('city_id', $cityId)
                                    ->pluck('name', 'id')
                                    ->map(function ($name) {
                                        return is_array($name) ? ($name['az'] ?? reset($name)) : $name;
                                    });
                            })
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Forms\Components\Select::make('deal_type')
                            ->label('Alqı-Satqı Növü')
                            ->options([
                                DealType::Sale->value => 'Satış',
                                DealType::RentMonthly->value => 'Aylıq Kirayə',
                                DealType::RentDaily->value => 'Günlük Kirayə',
                            ])
                            ->nullable(),

                        Forms\Components\Select::make('property_type')
                            ->label('Əmlak Növü')
                            ->options(collect(PropertyType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->label()]))
                            ->nullable(),

                        Forms\Components\Select::make('building_type')
                            ->label('Bina Növü (Tikili)')
                            ->options(collect(BuildingType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->label()]))
                            ->nullable(),

                        Forms\Components\Select::make('repair_type')
                            ->label('Təmir Vəziyyəti')
                            ->options(collect(RepairType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->label()]))
                            ->nullable(),

                        Forms\Components\Select::make('rooms')
                            ->label('Otaq Sayı')
                            ->options([
                                1 => '1 otaqlı',
                                2 => '2 otaqlı',
                                3 => '3 otaqlı',
                                4 => '4 otaqlı',
                                5 => '5+ otaqlı',
                            ])
                            ->nullable(),
                    ])->columns(3),

                Forms\Components\Section::make('Qiymət, Sahə və Sənəd Şərtləri')
                    ->schema([
                        Forms\Components\TextInput::make('min_price')
                            ->label('Min. Qiymət')
                            ->numeric()
                            ->prefix('£')
                            ->nullable(),

                        Forms\Components\TextInput::make('max_price')
                            ->label('Maks. Qiymət')
                            ->numeric()
                            ->prefix('£')
                            ->nullable(),

                        Forms\Components\TextInput::make('min_area')
                            ->label('Min. Sahə (m²)')
                            ->numeric()
                            ->nullable(),

                        Forms\Components\TextInput::make('max_area')
                            ->label('Maks. Sahə (m²)')
                            ->numeric()
                            ->nullable(),

                        Forms\Components\Toggle::make('has_document')
                            ->label('Çıxarış var (Kupçalı)')
                            ->nullable(),

                        Forms\Components\Toggle::make('has_mortgage')
                            ->label('İpotekaya yararlı')
                            ->nullable(),
                    ])->columns(4),

                Forms\Components\Section::make('Görünüş və Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_popular')
                            ->label('Populyar Axtarış Teqi Kimi Göstərilsin')
                            ->default(true)
                            ->helperText('Aktiv olduqda ana səhifə və list səhifəsində teq olaraq çıxacaq'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktivdir')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('title.az')
                    ->label('Başlıq (AZ)')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Link')
                    ->icon('heroicon-m-link')
                    ->color('primary')
                    ->formatStateUsing(fn ($state) => '/axtaris/' . $state)
                    ->copyable()
                    ->copyMessage('Link kopyalandı'),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('Şəhər')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['az'] ?? reset($state)) : $state)
                    ->placeholder('Bütün şəhərlər'),

                Tables\Columns\TextColumn::make('rooms')
                    ->label('Otaq')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' otaqlı' : '—'),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Baxış Sayı')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_popular')
                    ->label('Populyar Teq')
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
                Tables\Filters\TernaryFilter::make('is_popular')
                    ->label('Populyar teqlər'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuickSearches::route('/'),
            'create' => Pages\CreateQuickSearch::route('/create'),
            'edit' => Pages\EditQuickSearch::route('/{record}/edit'),
        ];
    }
}
