<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropertyRequestResource\Pages;
use App\Modules\PropertyRequest\Enums\RequestStatus;
use App\Modules\PropertyRequest\Enums\RequestType;
use App\Modules\PropertyRequest\Models\PropertyRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PropertyRequestResource extends Resource
{
    protected static ?string $model = PropertyRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationGroup(): ?string {
        return __('admin.properties_and_inquiries'); }

    public static function getNavigationLabel(): string {
        return __('admin.property_orders'); }

    public static function getModelLabel(): string {
        return __('admin.property_order'); }

    public static function getPluralModelLabel(): string {
        return __('admin.property_orders'); }

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.basic_information'))
                    ->schema([
                        Forms\Components\Select::make('request_type')
                            ->label(__('admin.request_type'))
                            ->options([
                                'buy' => __('admin.wants_to_buy'),
                                'rent_monthly' => __('admin.looking_for_rent'),
                                'rent_daily' => __('admin.looking_for_daily'),
                                'roommate_have' => 'Otaq Verir',
                                'roommate_need' => __('admin.looking_for_room'),
                            ])
                            ->required(),

                        Forms\Components\Select::make('property_type')
                            ->label(__('admin.property_type'))
                            ->options([
                                'Mənzil' => __('admin.apartment'),
                                'Həyət evi' => __('admin.house_garden'),
                                'Villa' => 'Villa',
                                'Torpaq' => 'Torpaq',
                                'Obyekt' => 'Obyekt',
                                'Ofis' => 'Ofis',
                            ]),

                        Forms\Components\TextInput::make('title')
                            ->label(__('admin.title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('budget_min')
                            ->label(__('admin.min_budget'))
                            ->numeric()
                            ->prefix('₼'),

                        Forms\Components\TextInput::make('budget_max')
                            ->label(__('admin.max_budget'))
                            ->numeric()
                            ->required()
                            ->prefix('₼'),

                        Forms\Components\Select::make('rooms')
                            ->label(__('admin.room_count'))
                            ->options([
                                '1' => __('admin.rooms_1'),
                                '2' => __('admin.rooms_2'),
                                '3' => __('admin.rooms_3'),
                                '4+' => __('admin.rooms_4_plus'),
                            ]),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'published' => __('admin.published'),
                                'pending' => __('admin.pending'),
                                'rejected' => __('admin.rejected'),
                                'closed' => __('admin.closed'),
                            ])
                            ->default('published')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make(__('admin.location_and_requirements'))
                    ->schema([
                        Forms\Components\Select::make('city_id')
                            ->label(__('admin.city'))
                            ->relationship('city', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => is_array($record->name) ? ($record->name['az'] ?? reset($record->name)) : $record->name)
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('district_id')
                            ->label('Rayon')
                            ->relationship('district', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => is_array($record->name) ? ($record->name['az'] ?? reset($record->name)) : $record->name)
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('location_note')
                            ->label(__('admin.metro_address_note'))
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('has_deed')
                            ->label(__('admin.deed_only_kupcha')),

                        Forms\Components\Toggle::make('mortgage_eligible')
                            ->label(__('admin.mortgage_eligible')),

                        Forms\Components\Toggle::make('bills_included')
                            ->label('Kommunal daxil'),
                    ])->columns(2),

                Forms\Components\Section::make(__('admin.description'))
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label(__('admin.detailed_description'))
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make(__('admin.contact'))
                    ->schema([
                        Forms\Components\TextInput::make('contact_name')
                            ->label('Ad')
                            ->required(),

                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Telefon')
                            ->required(),

                        Forms\Components\TextInput::make('contact_whatsapp')
                            ->label('WhatsApp'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('admin.title'))
                    ->searchable()
                    ->limit(35),

                Tables\Columns\BadgeColumn::make('request_type')
                    ->label(__('admin.request_type'))
                    ->formatStateUsing(fn ($state) => $state instanceof RequestType ? $state->badgeLabel() : $state)
                    ->colors([
                        'success' => 'buy',
                        'primary' => 'rent_monthly',
                        'warning' => 'rent_daily',
                        'danger' => 'roommate_have',
                        'secondary' => 'roommate_need',
                    ]),

                Tables\Columns\TextColumn::make('budget_max')
                    ->label(__('admin.budget'))
                    ->formatStateUsing(fn ($record) => $record->formatted_budget)
                    ->sortable(),

                Tables\Columns\TextColumn::make('city.name')
                    ->label(__('admin.city'))
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['az'] ?? reset($state)) : $state),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state instanceof RequestStatus ? $state->label() : $state)
                    ->colors([
                        'success' => 'published',
                        'warning' => 'pending',
                        'danger' => 'rejected',
                        'secondary' => 'closed',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('request_type')
                    ->label(__('admin.request_type'))
                    ->options([
                        'buy' => __('admin.wants_to_buy'),
                        'rent_monthly' => __('admin.looking_for_rent'),
                        'rent_daily' => __('admin.looking_for_daily'),
                        'roommate_have' => 'Otaq Verir',
                        'roommate_need' => __('admin.looking_for_room'),
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'published' => __('admin.published'),
                        'pending' => __('admin.pending'),
                        'rejected' => __('admin.rejected'),
                        'closed' => __('admin.closed'),
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropertyRequests::route('/'),
            'create' => Pages\CreatePropertyRequest::route('/create'),
            'edit' => Pages\EditPropertyRequest::route('/{record}/edit'),
        ];
    }
}
