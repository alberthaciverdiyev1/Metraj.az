<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Location\Enums\FilterKey;
use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Enums\SellerType;
use App\Modules\Location\Models\Filter;
use App\Modules\Location\Models\FilterOption;
use App\Modules\Property\Models\Property;
use App\Filament\Admin\Resources\PropertyResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Component;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function getNavigationGroup(): ?string {
        return __('admin.properties_and_inquiries'); }

    public static function getNavigationLabel(): string
    {
        return __('panel.all_properties');
    }

    public static function getModelLabel(): string
    {
        return __('panel.all_properties');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel.all_properties');
    }

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', PropertyStatus::PendingApproval)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Təsdiq gözləyən elanlar';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormSchema(true))
            ->columns(2);
    }

    /**
     * Tam form sxemi — səliqəli, bölmələrə ayrılmış tək səhifə forması.
     */
    public static function getFormSchema(bool $isAdmin = true): array
    {
        return [
            self::sectionOverview($isAdmin),
            self::sectionLocation(),
            self::sectionPricing(),
            self::sectionDimensions(),
            self::sectionFeatures(),
            self::sectionDocuments($isAdmin),
            self::sectionAmenities(),
            self::sectionDescription(),
            self::sectionImages(),
            $isAdmin ? self::sectionOwnership() : self::sectionSubmission(),
        ];
    }

    /**
     * 1) Əmlak növü, əməliyyat və satıcı (satıcı yalnız admin üçün)
     */
    protected static function sectionOverview(bool $isAdmin = true): Forms\Components\Section
    {
        $schema = [
            self::filterField(FilterKey::PropertyType, 'toggle', ['columns' => 3])
                ->columnSpanFull(),

            self::filterField(FilterKey::DealType, 'toggle', [
                'columns' => 3,
                // Kirayə seçiləndə sənəd / kredit sahələri təmizlənir (frontend JS-dəki davranışa uyğun)
                'afterStateUpdated' => function ($component, Forms\Set $set, Forms\Get $get, $filter): void {
                    if (static::isRental($get)) {
                        $set('has_document', false);
                        $set('has_mortgage', false);
                        $set('has_internal_credit', false);
                    }
                },
            ])->columnSpanFull(),
        ];

        if ($isAdmin) {
            $schema[] = Forms\Components\ToggleButtons::make('seller_type')
                ->label(__('admin.seller_type'))
                ->options(SellerType::options())
                ->default(SellerType::Owner->value)
                ->inline()
                ->helperText(__('admin.on_behalf_of_whom'))
                ->columnSpanFull();
        }

        return Forms\Components\Section::make(__('admin.property_and_deal'))
            ->description(__('admin.select_property_type_and_deal'))
            ->icon('heroicon-o-home-modern')
            ->columnSpan(2)
            ->schema($schema);
    }

    /**
     * 2) Yerləşmə — Şəhər ➔ Rayon və OpenStreetMap interaktiv xəritə
     */
    protected static function sectionLocation(): Forms\Components\Section
    {
        return Forms\Components\Section::make(__('admin.location_and_exact_map'))
            ->description(__('admin.city_district_map_hint'))
            ->icon('heroicon-o-map-pin')
            ->columnSpan(2)
            ->columns(2)
            ->schema([
                Forms\Components\Select::make('city_id')
                    ->label(__('admin.city'))
                    ->options(fn () => \App\Modules\Location\Models\City::where('is_active', true)->orderBy('sort_order')->get()->mapWithKeys(fn ($c) => [$c->id => $c->name['az'] ?? $c->slug]))
                    ->searchable()
                    ->preload()
                    ->placeholder(__('admin.select_city'))
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('district_id', null))
                    ->required(),

                Forms\Components\Select::make('district_id')
                    ->label(__('admin.district_region_variant'))
                    ->options(fn (Forms\Get $get) =>
                        $get('city_id')
                            ? \App\Modules\Location\Models\District::where('city_id', $get('city_id'))
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->get()
                                ->mapWithKeys(fn ($d) => [$d->id => $d->name['az'] ?? $d->slug])
                            : []
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder(fn (Forms\Get $get) => $get('city_id') ? 'Rayon / bölqə seçin' : 'Əvvəlcə şəhər seçin')
                    ->disabled(fn (Forms\Get $get) => blank($get('city_id')))
                    ->nullable(),

                Forms\Components\TextInput::make('address')
                    ->label(__('admin.exact_address'))
                    ->placeholder(__('admin.example_address'))
                    ->helperText(__('admin.map_address_sync_hint'))
                    ->maxLength(255)
                    ->live(debounce: 500)
                    ->columnSpan(1),

                Forms\Components\TextInput::make('landmark')
                    ->label(__('admin.landmark'))
                    ->placeholder(__('admin.example_near_sea_university'))
                    ->maxLength(255)
                    ->columnSpan(1),

                Forms\Components\View::make('filament.forms.components.map-picker')
                    ->columnSpanFull(),

                Forms\Components\Hidden::make('latitude')
                    ->default(40.409264),

                Forms\Components\Hidden::make('longitude')
                    ->default(49.867092),
            ]);
    }

    public static function isLand(Forms\Get $get): bool
    {
        $propertyTypeFilter = Filter::where('key', FilterKey::PropertyType->value)->first();
        if (! $propertyTypeFilter) {
            return false;
        }

        $landOptionId = FilterOption::where('filter_id', $propertyTypeFilter->id)
            ->where(function ($q) {
                $q->where('value', 'land')->orWhere('name->az', 'like', '%Torpaq%');
            })
            ->value('id');

        if (! $landOptionId) {
            return false;
        }

        return (int) $get('filter_' . $propertyTypeFilter->id) === (int) $landOptionId;
    }

    /**
     * Alqı-satqı növü "Kirayə" seçilibsə true qaytarır.
     * Bu zaman sənəd və kredit şərtləri (kupça, ipoteka, daxili kredit) göstərilmir.
     */
    public static function isRental(Forms\Get $get): bool
    {
        $dealTypeFilter = Filter::where('key', FilterKey::DealType->value)->first();
        if (! $dealTypeFilter) {
            return false;
        }

        $selectedOptionId = $get('filter_' . $dealTypeFilter->id);
        if (blank($selectedOptionId)) {
            return false;
        }

        $option = FilterOption::find((int) $selectedOptionId);
        if (! $option) {
            return false;
        }

        $value = mb_strtolower((string) $option->value);
        $azName = mb_strtolower((string) ($option->name['az'] ?? ''));

        return str_contains($value, 'rent') || str_contains($azName, 'kirayə');
    }

    /**
     * 3) Qiymət və Valyutalar (Çoxvalyutalı qiymət və avtomatik məzənnə konvertasiyası)
     */
    protected static function sectionPricing(): Forms\Components\Section
    {
        $recalculateCurrencies = function (Forms\Get $get, Forms\Set $set) {
            $cur = $get('currency') ?? 'GBP';
            $entered = (float) ($get('price_input') ?? $get('price_gbp') ?? 0);
            if ($entered <= 0) return;

            $currencyService = app(\App\Modules\Shared\Services\CurrencyService::class);
            $baseGbp = $currencyService->getBaseGbp($entered, $cur);
            $set('price', $baseGbp);

            if ($get('auto_convert_currency')) {
                $converted = $currencyService->convertFromCurrency($entered, $cur);
                $set('price_gbp', $converted['GBP'] ?? null);
                $set('price_usd', $converted['USD'] ?? null);
                $set('price_eur', $converted['EUR'] ?? null);
                $set('price_azn', $converted['AZN'] ?? null);
                $set('price_try', $converted['TRY'] ?? null);
                $set('price_rub', $converted['RUB'] ?? null);
                $set('price_aed', $converted['AED'] ?? null);
            }
        };

        return Forms\Components\Section::make(__('admin.price_and_currencies'))
            ->description(__('admin.main_currency_price_hint'))
            ->icon('heroicon-o-banknotes')
            ->columnSpan(2)
            ->columns(4)
            ->schema([
                Forms\Components\Toggle::make('auto_convert_currency')
                    ->label(__('admin.auto_convert_by_rate'))
                    ->helperText(__('admin.auto_convert_hint'))
                    ->default(true)
                    ->live()
                    ->afterStateUpdated(fn (bool $state, Forms\Get $get, Forms\Set $set) => $recalculateCurrencies($get, $set))
                    ->columnSpanFull(),

                // Əsas Valyuta Seçimi (Default: GBP)
                Forms\Components\Select::make('currency')
                    ->label(__('admin.main_currency'))
                    ->options([
                        'GBP' => 'Pound (£ GBP)',
                        'AZN' => 'Manat (₼ AZN)',
                        'USD' => 'Dollar ($ USD)',
                        'EUR' => 'Avro (€ EUR)',
                        'TRY' => __('admin.try_lira_short'),
                        'RUB' => 'Rusiya Rublu (₽ RUB)',
                        'AED' => __('admin.aed_dirham'),
                    ])
                    ->default('GBP')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, Forms\Get $get, Forms\Set $set) => $recalculateCurrencies($get, $set))
                    ->columnSpan(2),

                // Əsas Qiymət Daxil Edilməsi
                Forms\Components\TextInput::make('price_input')
                    ->label(fn (Forms\Get $get) => 'Əsas Qiymət (' . ($get('currency') ?? 'GBP') . ')')
                    ->numeric()
                    ->prefix(fn (Forms\Get $get) => match ($get('currency') ?? 'GBP') {
                        'GBP' => '£',
                        'AZN' => '₼',
                        'USD' => '$',
                        'EUR' => '€',
                        'TRY' => '₺',
                        'RUB' => '₽',
                        'AED' => 'د.إ',
                        default => '£'
                    })
                    ->required()
                    ->placeholder(__('admin.price_placeholder'))
                    ->live(debounce: 350)
                    ->afterStateUpdated(fn ($state, Forms\Get $get, Forms\Set $set) => $recalculateCurrencies($get, $set))
                    ->afterStateHydrated(function ($component, $record) {
                        if (! $record) return;
                        $cur = $record->currency ?? 'GBP';
                        $component->state($record->prices[$cur] ?? $record->price ?? null);
                    })
                    ->columnSpan(2),

                // 1) POUND (GBP £)
                Forms\Components\TextInput::make('price_gbp')
                    ->label('Pound (£ GBP)')
                    ->numeric()
                    ->prefix('£')
                    ->disabled(fn (Forms\Get $get) => (bool) $get('auto_convert_currency'))
                    ->dehydrated()
                    ->placeholder(__('admin.price_placeholder'))
                    ->afterStateHydrated(function ($component, $record) {
                        if (! $record) return;
                        $component->state($record->prices['GBP'] ?? $record->price ?? null);
                    })
                    ->columnSpan(1),

                // 2) DOLLAR (USD $)
                Forms\Components\TextInput::make('price_usd')
                    ->label('Dollar ($ USD)')
                    ->numeric()
                    ->prefix('$')
                    ->disabled(fn (Forms\Get $get) => (bool) $get('auto_convert_currency'))
                    ->dehydrated()
                    ->placeholder(__('admin.example_195000'))
                    ->afterStateHydrated(function ($component, $record) {
                        if (! $record) return;
                        $component->state($record->prices['USD'] ?? null);
                    })
                    ->columnSpan(1),

                // 3) EVRO (EUR €)
                Forms\Components\TextInput::make('price_eur')
                    ->label('Avro (€ EUR)')
                    ->numeric()
                    ->prefix('€')
                    ->disabled(fn (Forms\Get $get) => (bool) $get('auto_convert_currency'))
                    ->dehydrated()
                    ->placeholder(__('admin.example_177000'))
                    ->afterStateHydrated(function ($component, $record) {
                        if (! $record) return;
                        $component->state($record->prices['EUR'] ?? null);
                    })
                    ->columnSpan(1),

                // 4) MANAT (AZN ₼)
                Forms\Components\TextInput::make('price_azn')
                    ->label('Manat (₼ AZN)')
                    ->numeric()
                    ->prefix('₼')
                    ->disabled(fn (Forms\Get $get) => (bool) $get('auto_convert_currency'))
                    ->dehydrated()
                    ->placeholder(__('admin.example_331500'))
                    ->afterStateHydrated(function ($component, $record) {
                        if (! $record) return;
                        $component->state($record->prices['AZN'] ?? null);
                    })
                    ->columnSpan(1),

                // 5) TÜRK LİRƏSİ (TRY ₺)
                Forms\Components\TextInput::make('price_try')
                    ->label(__('admin.try_lira'))
                    ->numeric()
                    ->prefix('₺')
                    ->disabled(fn (Forms\Get $get) => (bool) $get('auto_convert_currency'))
                    ->dehydrated()
                    ->placeholder(__('admin.example_6675000'))
                    ->afterStateHydrated(function ($component, $record) {
                        if (! $record) return;
                        $component->state($record->prices['TRY'] ?? null);
                    })
                    ->columnSpan(1),

                // 6) RUSİYA RUBLU (RUB ₽)
                Forms\Components\TextInput::make('price_rub')
                    ->label('Rusiya Rublu (₽ RUB)')
                    ->numeric()
                    ->prefix('₽')
                    ->disabled(fn (Forms\Get $get) => (bool) $get('auto_convert_currency'))
                    ->dehydrated()
                    ->placeholder(__('admin.example_18000000'))
                    ->afterStateHydrated(function ($component, $record) {
                        if (! $record) return;
                        $component->state($record->prices['RUB'] ?? null);
                    })
                    ->columnSpan(1),

                // 7) BƏƏ DİRHƏMİ (AED د.إ)
                Forms\Components\TextInput::make('price_aed')
                    ->label(__('admin.aed_dirham_uae'))
                    ->numeric()
                    ->prefix('د.إ')
                    ->disabled(fn (Forms\Get $get) => (bool) $get('auto_convert_currency'))
                    ->dehydrated()
                    ->placeholder(__('admin.example_715500'))
                    ->afterStateHydrated(function ($component, $record) {
                        if (! $record) return;
                        $component->state($record->prices['AED'] ?? null);
                    })
                    ->columnSpan(1),

                Forms\Components\Hidden::make('price')
                    ->default(0),
            ]);
    }

    /**
     * 4) Ölçülər və Mərtəbə
     */
    protected static function sectionDimensions(): Forms\Components\Section
    {
        return Forms\Components\Section::make(__('admin.sizes_and_floor'))
            ->description(__('admin.property_size_details_hint'))
            ->icon('heroicon-o-arrows-pointing-out')
            ->columnSpan(2)
            ->columns(4)
            ->schema([
                Forms\Components\TextInput::make('area')
                    ->label(__('admin.area_sqm'))
                    ->numeric()
                    ->suffix('m²')
                    ->placeholder(__('admin.example_120'))
                    ->hidden(fn (Forms\Get $get): bool => static::isLand($get))
                    ->columnSpan(1),

                Forms\Components\TextInput::make('land_area')
                    ->label('Torpaq (sot)')
                    ->numeric()
                    ->suffix('sot')
                    ->placeholder(__('admin.example_10'))
                    ->visible(fn (Forms\Get $get): bool => static::isLand($get))
                    ->columnSpan(1),

                Forms\Components\TextInput::make('rooms')
                    ->label(__('admin.room_count'))
                    ->numeric()
                    ->placeholder(__('admin.example_3'))
                    ->hidden(fn (Forms\Get $get): bool => static::isLand($get))
                    ->columnSpan(1),

                Forms\Components\TextInput::make('floor')
                    ->label(__('admin.floor'))
                    ->numeric()
                    ->placeholder(__('admin.example_4'))
                    ->hidden(fn (Forms\Get $get): bool => static::isLand($get))
                    ->columnSpan(1),

                Forms\Components\TextInput::make('total_floors')
                    ->label(__('admin.building_floor'))
                    ->numeric()
                    ->placeholder(__('admin.example_9'))
                    ->hidden(fn (Forms\Get $get): bool => static::isLand($get))
                    ->columnSpan(1),
            ]);
    }

    /**
     * 5) Əlavə xüsusiyyətlər: tikili, təmir, istilik, mənzərə
     */
    protected static function sectionFeatures(): Forms\Components\Section
    {
        return Forms\Components\Section::make(__('admin.additional_features'))
            ->description(__('admin.building_repair_heating_view_hint'))
            ->icon('heroicon-o-sparkles')
            ->columnSpan(2)
            ->columns(2)
            ->hidden(fn (Forms\Get $get): bool => static::isLand($get))
            ->schema([
                self::filterField(FilterKey::BuildingType, 'toggle', ['columns' => 3])
                    ->columnSpan(1),

                self::filterField(FilterKey::RepairType, 'toggle', ['columns' => 3])
                    ->columnSpan(1),

                self::filterField(FilterKey::HeatingSystem, 'select')
                    ->columnSpan(1),

                self::filterField(FilterKey::WindowView, 'select')
                    ->columnSpan(1),
            ]);
    }

    /**
     * 6) Sənədlər və İşarələr (Kupça, İpoteka, Daxili kredit, VIP, Seçilmiş)
     */
    protected static function sectionDocuments(bool $isAdmin = true): Forms\Components\Section
    {
        return Forms\Components\Section::make(__('admin.documents_and_markers'))
            ->description(__('admin.deed_status_hint'))
            ->icon('heroicon-o-document-check')
            ->columnSpan(2)
            ->columns(3)
            ->hidden(fn (Forms\Get $get): bool => static::isRental($get))
            ->schema([
                Forms\Components\Toggle::make('has_document')
                    ->label(__('admin.deed_available_kupcha'))
                    ->default(false)
                    ->columnSpan(1),

                Forms\Components\Toggle::make('has_mortgage')
                    ->label(__('admin.mortgage_eligible'))
                    ->default(false)
                    ->columnSpan(1),

                Forms\Components\Toggle::make('has_internal_credit')
                    ->label('Daxili kredit var')
                    ->default(false)
                    ->columnSpan(1),

                Forms\Components\Toggle::make('is_vip')
                    ->label('VIP Elan')
                    ->default(false)
                    ->visible($isAdmin)
                    ->columnSpan(1),

                Forms\Components\Toggle::make('is_featured')
                    ->label(__('admin.featured_listing'))
                    ->default(false)
                    ->visible($isAdmin)
                    ->columnSpan(1),
            ]);
    }

    /**
     * 6) Təchizatlar: qaz, lift, parkinq və s.
     */
    protected static function sectionAmenities(): Forms\Components\Section
    {
        return Forms\Components\Section::make(__('admin.amenities'))
            ->description(__('admin.select_available_amenities'))
            ->icon('heroicon-o-check-circle')
            ->columnSpan(2)
            ->schema([
                Forms\Components\CheckboxList::make('amenities')
                    ->relationship('amenities', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->localized_name)
                    ->label('')
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * 7) Təsvir
     */
    protected static function sectionDescription(): Forms\Components\Section
    {
        return Forms\Components\Section::make(__('admin.description'))
            ->description(__('admin.listing_description_hint'))
            ->icon('heroicon-o-pencil-square')
            ->columnSpan(2)
            ->schema([
                Forms\Components\RichEditor::make('description')
                    ->label(__('admin.listing_description'))
                    ->columnSpanFull(),
            ]);
    }

    /**
     * 8) Media: Şəkillər və Video (Multi-select və Drag-and-Drop sıralama)
     */
    protected static function sectionImages(): Forms\Components\Section
    {
        return Forms\Components\Section::make(__('admin.media_images_video'))
            ->description(__('admin.media_upload_hint'))
            ->icon('heroicon-o-photo')
            ->columnSpan(2)
            ->schema([
                Forms\Components\FileUpload::make('uploaded_images')
                    ->label(__('admin.photos'))
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->disk('public')
                    ->directory('properties')
                    ->visibility('public')
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull()
                    ->afterStateHydrated(function ($component, $record) {
                        if (! $record) return;
                        $component->state($record->images()->orderBy('sort_order', 'asc')->get()->map(fn ($img) => $img->getRawOriginal('url'))->filter()->values()->toArray());
                    })
                    ->dehydrated(false),

                Forms\Components\FileUpload::make('video')
                    ->label(__('admin.video_optional'))
                    ->helperText(__('admin.upload_property_video_hint'))
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo', 'video/ogg'])
                    ->maxSize(51200)
                    ->disk('public')
                    ->directory('properties/videos')
                    ->visibility('public')
                    ->openable()
                    ->downloadable()
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    /**
     * 9) Sahiblik və status (yalnız admin)
     */
    protected static function sectionOwnership(): Forms\Components\Section
    {
        return Forms\Components\Section::make(__('admin.ownership_and_status'))
            ->description(__('admin.set_owner_and_status'))
            ->icon('heroicon-o-user-group')
            ->columnSpan(2)
            ->columns(3)
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Elan Kodu')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Avtomatik generasiya olunur'),

                Forms\Components\Select::make('agency_id')
                    ->label('Agentlik')
                    ->relationship('agency', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('agent_id')
                    ->label('Agent / Rieltor')
                    ->relationship('agent', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user?->name . ' (' . ($record->position ?? 'Agent') . ')')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(collect(PropertyStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()]))
                    ->default(PropertyStatus::PendingApproval->value)
                    ->required(),
            ]);
    }

    /**
     * 9) Yekun məlumat (agentlik paneli)
     */
    protected static function sectionSubmission(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Yekun')
            ->description(__('admin.review_before_create'))
            ->icon('heroicon-o-check-badge')
            ->columnSpan(2)
            ->schema([
                Forms\Components\Placeholder::make('submission_info')
                    ->label(__('admin.next_step'))
                    ->content('Elan yaradıldıqdan sonra status "Təsdiq gözləyir" olaraq təyin ediləcək. Admin tərəfindən təsdiqləndikdən sonra elan dərc olunacaq.'),
            ]);
    }

    /**
     * Dinamik filtr sahəsi qurur (filter_{id} adı ilə, sync trait üçün dehydrated=false).
     */
    protected static function filterField(FilterKey $key, string $type = 'select', array $options = []): Component
    {
        $filter = Filter::with('options')->where('key', $key->value)->first();

        if (! $filter || $filter->options->isEmpty()) {
            return Forms\Components\Placeholder::make('filter_empty_' . $key->value)
                ->label($key->label())
                ->content('Bu filtr üçün hələ seçim mövcud deyil.');
        }

        $fieldName = 'filter_' . $filter->id;
        $optionList = $filter->options
            ->mapWithKeys(fn ($opt) => [$opt->id => $opt->name['az'] ?? $opt->value])
            ->toArray();

        $component = match ($type) {
            'toggle' => Forms\Components\ToggleButtons::make($fieldName)
                ->options($optionList)
                ->inline()
                ->columns($options['columns'] ?? 3)
                ->default($options['default'] ?? null),
            default => Forms\Components\Select::make($fieldName)
                ->options($optionList)
                ->searchable()
                ->preload()
                ->placeholder(__('admin.select_placeholder')),
        };

        return $component
            ->label($filter->name['az'] ?? $key->label())
            ->helperText($options['helperText'] ?? null)
            ->live()
            ->afterStateUpdated(function ($component, Forms\Set $set, Forms\Get $get) use ($filter, $options) {
                // Xüsusi callback verilibsə onu çağırırıq (Məs: deal_type dəyişəndə kredit sahələrini təmizləmək)
                if (isset($options['afterStateUpdated']) && is_callable($options['afterStateUpdated'])) {
                    ($options['afterStateUpdated'])($component, $set, $get, $filter);
                }
            })
            ->afterStateHydrated(function ($component, $record) use ($filter) {
                if ($record) {
                    $selected = $record->filterOptions->where('filter_id', $filter->id)->first();
                    $component->state($selected?->id);
                }
            })
            ->dehydrated(false);
    }

    public static function table(Table $table): Table
    {
        $isGrid = session('properties_table_layout', 'table') === 'grid';

        if ($isGrid) {
            return $table
                ->defaultSort('id', 'desc')
                ->contentGrid([
                    'sm' => 1,
                    'md' => 2,
                    'lg' => 3,
                    'xl' => 4,
                    '2xl' => 4,
                ])
                ->columns([
                    Tables\Columns\Layout\View::make('filament.tables.components.property-grid-card'),
                ])
                ->filters([
                    Tables\Filters\SelectFilter::make('seller_type')
                        ->label(__('admin.seller_type'))
                        ->options(SellerType::options()),

                    Tables\Filters\SelectFilter::make('status')
                        ->label('Status')
                        ->options(collect(PropertyStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])),
                ])
                ->actions([
                    Tables\Actions\ViewAction::make()
                        ->url(fn (\Illuminate\Database\Eloquent\Model $record): string => static::getUrl('view', ['record' => $record])),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->bulkActions([]);
        }

        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('first_image_url')
                    ->label(__('admin.image'))
                    ->state(fn (Property $record) => $record->first_image_url)
                    ->extraImgAttributes([
                        'class' => 'w-12 h-12 object-cover rounded-lg shadow-sm',
                    ])
                    ->size(48)
                    ->square(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('admin.title'))
                    ->limit(35)
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('admin.price'))
                    ->formatStateUsing(fn ($record) => ($record->currency === 'GBP' || empty($record->currency) ? '£ ' : $record->currency . ' ') . number_format($record->price, 0, '.', ' '))
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('views_count')
                    ->label(__('admin.view_count'))
                    ->icon('heroicon-o-eye')
                    ->numeric()
                    ->default(0)
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('inquiries_count')
                    ->counts('inquiries')
                    ->label(__('admin.inquiry'))
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('seller_type')
                    ->label(__('admin.seller'))
                    ->badge()
                    ->formatStateUsing(fn (?SellerType $state): string => $state?->label() ?? '—'),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options(collect(PropertyStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()]))
                    ->selectablePlaceholder(false)
                    ->afterStateUpdated(function (Property $record, $state) {
                        \Filament\Notifications\Notification::make()
                            ->title(__('admin.status_updated'))
                            ->body(__('admin.status_changed_for_listing', ['code' => $record->code]))
                            ->success()
                            ->send();
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('seller_type')
                    ->label(__('admin.seller_type'))
                    ->options(SellerType::options()),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(PropertyStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->url(fn (\Illuminate\Database\Eloquent\Model $record): string => static::getUrl('view', ['record' => $record])),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('changeStatus')
                        ->label(__('admin.change_status'))
                        ->icon('heroicon-m-arrow-path')
                        ->color('warning')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Yeni Status')
                                ->options(collect(PropertyStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                                ->default(fn (Property $record) => $record->status->value)
                                ->required(),
                        ])
                        ->action(function (Property $record, array $data) {
                            $record->update(['status' => $data['status']]);
                            \Filament\Notifications\Notification::make()
                                ->title(__('admin.status_updated'))
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('quickPublish')
                        ->label(__('admin.publish_approve'))
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->visible(fn (Property $record) => $record->status !== PropertyStatus::Published)
                        ->requiresConfirmation()
                        ->modalHeading(__('admin.publish_listing'))
                        ->modalDescription(__('admin.confirm_publish_listing'))
                        ->action(function (Property $record) {
                            $record->update(['status' => PropertyStatus::Published]);
                            \Filament\Notifications\Notification::make()
                                ->title(__('admin.listing_published'))
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('quickReject')
                        ->label(__('admin.reject'))
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->visible(fn (Property $record) => $record->status !== PropertyStatus::Rejected)
                        ->requiresConfirmation()
                        ->modalHeading(__('admin.reject_listing'))
                        ->modalDescription(__('admin.confirm_reject_listing'))
                        ->action(function (Property $record) {
                            $record->update(['status' => PropertyStatus::Rejected]);
                            \Filament\Notifications\Notification::make()
                                ->title('Elan imtina edildi')
                                ->warning()
                                ->send();
                        }),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulkPublish')
                        ->label(__('admin.publish_selected'))
                        ->icon('heroicon-m-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each->update(['status' => PropertyStatus::Published]);
                            \Filament\Notifications\Notification::make()
                                ->title(__('admin.selected_listings_published'))
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('bulkPending')
                        ->label(__('admin.move_selected_to_pending'))
                        ->icon('heroicon-m-clock')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each->update(['status' => PropertyStatus::PendingApproval]);
                            \Filament\Notifications\Notification::make()
                                ->title(__('admin.selected_listings_pending'))
                                ->warning()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('bulkArchive')
                        ->label(__('admin.archive_selected'))
                        ->icon('heroicon-m-archive-box')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each->update(['status' => PropertyStatus::Archived]);
                            \Filament\Notifications\Notification::make()
                                ->title(__('admin.selected_listings_archived'))
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Admin\Resources\PropertyResource\RelationManagers\InquiriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'view' => Pages\ViewProperty::route('/{record}'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
