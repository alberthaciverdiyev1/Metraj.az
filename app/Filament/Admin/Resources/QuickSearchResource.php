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

    public static function getNavigationGroup(): ?string {
        return __('admin.content_and_search'); }

    public static function getNavigationLabel(): string {
        return __('admin.quick_searches'); }

    public static function getModelLabel(): string {
        return __('admin.search_template'); }

    public static function getPluralModelLabel(): string {
        return __('admin.quick_searches'); }

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.title_and_seo_link'))
                    ->description(__('admin.title_seen_by_users_hint'))
                    ->schema([
                        Forms\Components\TextInput::make('title.az')
                            ->label(__('admin.title_az_label'))
                            ->placeholder(__('admin.example_girne_new_build'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (! $get('slug') && $state) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('title.tr')
                            ->label(__('admin.title_tr_label'))
                            ->placeholder(__('admin.example_title_tr'))
                            ->nullable(),

                        Forms\Components\TextInput::make('title.en')
                            ->label(__('admin.title_en_label'))
                            ->placeholder('e.g. New building 2+1 apartments in Kyrenia')
                            ->nullable(),

                        Forms\Components\TextInput::make('title.ru')
                            ->label(__('admin.title_ru_label'))
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

                Forms\Components\Section::make(__('admin.location_and_property_parameters'))
                    ->description(__('admin.filters_applied_on_click'))
                    ->schema([
                        Forms\Components\Select::make('city_id')
                            ->label(__('admin.city'))
                            ->options(City::query()->pluck('name', 'id')->map(function ($name) {
                                return is_array($name) ? ($name['az'] ?? reset($name)) : $name;
                            }))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('district_id', null))
                            ->nullable(),

                        Forms\Components\Select::make('district_id')
                            ->label(__('admin.district_town'))
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
                            ->label(__('admin.deal_type'))
                            ->options([
                                DealType::Sale->value => __('admin.sale'),
                                DealType::RentMonthly->value => __('admin.monthly_rent'),
                                DealType::RentDaily->value => __('admin.daily_rent'),
                            ])
                            ->nullable(),

                        Forms\Components\Select::make('property_type')
                            ->label(__('admin.property_type'))
                            ->options(collect(PropertyType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->label()]))
                            ->nullable(),

                        Forms\Components\Select::make('building_type')
                            ->label(__('admin.building_type'))
                            ->options(collect(BuildingType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->label()]))
                            ->nullable(),

                        Forms\Components\Select::make('repair_type')
                            ->label(__('admin.renovation_status'))
                            ->options(collect(RepairType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->label()]))
                            ->nullable(),

                        Forms\Components\Select::make('rooms')
                            ->label(__('admin.room_count'))
                            ->options([
                                1 => __('admin.rooms_1'),
                                2 => __('admin.rooms_2'),
                                3 => __('admin.rooms_3'),
                                4 => '4 otaqlı',
                                5 => '5+ otaqlı',
                            ])
                            ->nullable(),
                    ])->columns(3),

                Forms\Components\Section::make(__('admin.price_area_deed_terms'))
                    ->schema([
                        Forms\Components\TextInput::make('min_price')
                            ->label(__('admin.min_price'))
                            ->numeric()
                            ->prefix('£')
                            ->nullable(),

                        Forms\Components\TextInput::make('max_price')
                            ->label(__('admin.max_price'))
                            ->numeric()
                            ->prefix('£')
                            ->nullable(),

                        Forms\Components\TextInput::make('min_area')
                            ->label(__('admin.min_area'))
                            ->numeric()
                            ->nullable(),

                        Forms\Components\TextInput::make('max_area')
                            ->label(__('admin.max_area'))
                            ->numeric()
                            ->nullable(),

                        Forms\Components\Toggle::make('has_document')
                            ->label(__('admin.deed_available_kupchali'))
                            ->nullable(),

                        Forms\Components\Toggle::make('has_mortgage')
                            ->label(__('admin.mortgage_eligible'))
                            ->nullable(),
                    ])->columns(4),

                Forms\Components\Section::make(__('admin.appearance_and_status'))
                    ->schema([
                        Forms\Components\Toggle::make('is_popular')
                            ->label(__('admin.show_as_popular_search_tag'))
                            ->default(true)
                            ->helperText(__('admin.shown_as_tag_hint')),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktivdir')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('admin.sort_order'))
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
                    ->label(__('admin.title_az_label'))
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
                    ->label(__('admin.city'))
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['az'] ?? reset($state)) : $state)
                    ->placeholder(__('admin.all_cities')),

                Tables\Columns\TextColumn::make('rooms')
                    ->label('Otaq')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' otaqlı' : '—'),

                Tables\Columns\TextColumn::make('view_count')
                    ->label(__('admin.view_count'))
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
                    ->label(__('admin.sort_order_short'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktivlik'),
                Tables\Filters\TernaryFilter::make('is_popular')
                    ->label(__('admin.popular_tags')),
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
