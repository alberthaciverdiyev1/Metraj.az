<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RoommateListingResource\Pages;
use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Roommate\Enums\GenderPreference;
use App\Modules\Roommate\Enums\OccupationPreference;
use App\Modules\Roommate\Enums\RoommateListingType;
use App\Modules\Roommate\Enums\RoommateStatus;
use App\Modules\Roommate\Models\RoommateListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoommateListingResource extends Resource
{
    protected static ?string $model = RoommateListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getNavigationGroup(): ?string {
        return __('admin.properties_and_inquiries'); }

    public static function getNavigationLabel(): string {
        return __('admin.roommate'); }

    public static function getModelLabel(): string {
        return __('admin.roommate_listing'); }

    public static function getPluralModelLabel(): string {
        return __('admin.roommate_listings'); }

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.basic_information'))
                    ->schema([
                        Forms\Components\Select::make('listing_type')
                            ->label(__('admin.listing_type'))
                            ->options([
                                'have_room' => __('admin.have_room_looking_for_roommate'),
                                'need_room' => __('admin.looking_for_flat_and_roommate'),
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label(__('admin.title'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('price')
                            ->label(__('admin.monthly_payment'))
                            ->numeric()
                            ->required()
                            ->prefix('₼'),

                        Forms\Components\Toggle::make('bills_included')
                            ->label(__('admin.utilities_included')),

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

                Forms\Components\Section::make(__('admin.location'))
                    ->schema([
                        Forms\Components\Select::make('city_id')
                            ->label(__('admin.city'))
                            ->relationship('city', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => is_array($record->name) ? ($record->name['az'] ?? reset($record->name)) : $record->name)
                            ->searchable()
                            ->preload()
                            ->reactive()
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
                    ])->columns(2),

                Forms\Components\Section::make(__('admin.requirements_and_rules'))
                    ->schema([
                        Forms\Components\Select::make('gender_preference')
                            ->label(__('admin.gender_requirement'))
                            ->options([
                                'any' => __('admin.no_preference'),
                                'female' => __('admin.female_only'),
                                'male' => __('admin.male_only'),
                            ])
                            ->required(),

                        Forms\Components\Select::make('occupation_preference')
                            ->label(__('admin.occupation'))
                            ->options([
                                'any' => __('admin.no_preference'),
                                'student' => __('admin.students_only'),
                                'working' => __('admin.working_only'),
                            ]),

                        Forms\Components\Toggle::make('smoker_allowed')
                            ->label(__('admin.smoking_allowed')),

                        Forms\Components\Toggle::make('pet_allowed')
                            ->label(__('admin.pets_allowed')),

                        Forms\Components\TextInput::make('stay_duration')
                            ->label(__('admin.stay_duration'))
                            ->maxLength(100),

                        Forms\Components\DatePicker::make('available_from')
                            ->label(__('admin.move_in_date')),

                        Forms\Components\TextInput::make('total_roommates')
                            ->label(__('admin.total_people_in_house'))
                            ->numeric(),
                    ])->columns(3),

                Forms\Components\Section::make(__('admin.detailed_description'))
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label(__('admin.description'))
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make(__('admin.contact_information'))
                    ->schema([
                        Forms\Components\TextInput::make('contact_name')
                            ->label('Ad')
                            ->required(),

                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Telefon')
                            ->required(),

                        Forms\Components\TextInput::make('contact_whatsapp')
                            ->label('WhatsApp'),

                        Forms\Components\TextInput::make('contact_email')
                            ->label('Email')
                            ->email(),
                    ])->columns(2),
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

                Tables\Columns\BadgeColumn::make('listing_type')
                    ->label(__('admin.type'))
                    ->formatStateUsing(fn ($state) => $state instanceof RoommateListingType ? $state->badgeLabel() : ($state === 'have_room' ? 'Otaq verilir' : 'Otaq axtarır'))
                    ->colors([
                        'success' => 'have_room',
                        'primary' => 'need_room',
                    ]),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('admin.price'))
                    ->formatStateUsing(fn ($record) => $record->formatted_price)
                    ->sortable(),

                Tables\Columns\TextColumn::make('city.name')
                    ->label(__('admin.city'))
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['az'] ?? reset($state)) : $state),

                Tables\Columns\BadgeColumn::make('gender_preference')
                    ->label(__('admin.gender'))
                    ->formatStateUsing(fn ($state) => $state instanceof GenderPreference ? $state->badgeLabel() : $state)
                    ->colors([
                        'danger' => 'female',
                        'primary' => 'male',
                        'secondary' => 'any',
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state instanceof RoommateStatus ? $state->label() : $state)
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
                Tables\Filters\SelectFilter::make('listing_type')
                    ->label(__('admin.listing_type'))
                    ->options([
                        'have_room' => 'Otaq verilir',
                        'need_room' => __('admin.looking_for_room_short'),
                    ]),

                Tables\Filters\SelectFilter::make('gender_preference')
                    ->label(__('admin.gender'))
                    ->options([
                        'any' => __('admin.for_everyone'),
                        'female' => __('admin.female_only'),
                        'male' => __('admin.male_only'),
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
            'index' => Pages\ListRoommateListings::route('/'),
            'create' => Pages\CreateRoommateListing::route('/create'),
            'edit' => Pages\EditRoommateListing::route('/{record}/edit'),
        ];
    }
}
