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

    protected static ?string $navigationGroup = 'Əmlak və Müraciətlər';

    protected static ?string $navigationLabel = 'Otaq Yoldaşı';

    protected static ?string $modelLabel = 'Otaq Yoldaşı Elanı';

    protected static ?string $pluralModelLabel = 'Otaq Yoldaşı Elanları';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Əsas Məlumatlar')
                    ->schema([
                        Forms\Components\Select::make('listing_type')
                            ->label('Elan Növü')
                            ->options([
                                'have_room' => 'Evim var, otaq yoldaşı axtarıram',
                                'need_room' => 'Ev axtarıram, otaq yoldaşı axtarıram',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label('Başlıq')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('price')
                            ->label('Aylıq Ödəniş (₼)')
                            ->numeric()
                            ->required()
                            ->prefix('₼'),

                        Forms\Components\Toggle::make('bills_included')
                            ->label('Kommunal xərclər daxildir'),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'published' => 'Dərc olunub',
                                'pending' => 'Gözləmədə',
                                'rejected' => 'İmtina edilib',
                                'closed' => 'Bağlanıb',
                            ])
                            ->default('published')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Yerləşmə')
                    ->schema([
                        Forms\Components\Select::make('city_id')
                            ->label('Şəhər')
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
                            ->label('Metro / Ünvan / Qeyd')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Tələblər və Qaydalar')
                    ->schema([
                        Forms\Components\Select::make('gender_preference')
                            ->label('Cinsiyyət Tələbi')
                            ->options([
                                'any' => 'Fərqi yoxdur',
                                'female' => 'Yalnız Xanım',
                                'male' => 'Yalnız Bəy',
                            ])
                            ->required(),

                        Forms\Components\Select::make('occupation_preference')
                            ->label('Məşğuliyyət')
                            ->options([
                                'any' => 'Fərqi yoxdur',
                                'student' => 'Yalnız Tələbə',
                                'working' => 'Yalnız İşləyən',
                            ]),

                        Forms\Components\Toggle::make('smoker_allowed')
                            ->label('Siqaret çəkməyə icazə var'),

                        Forms\Components\Toggle::make('pet_allowed')
                            ->label('Ev heyvanı saxlamağa icazə var'),

                        Forms\Components\TextInput::make('stay_duration')
                            ->label('Qalma Müddəti')
                            ->maxLength(100),

                        Forms\Components\DatePicker::make('available_from')
                            ->label('Köçmə Tarixi'),

                        Forms\Components\TextInput::make('total_roommates')
                            ->label('Evdə Ümumi Adam Sayı')
                            ->numeric(),
                    ])->columns(3),

                Forms\Components\Section::make('Ətraflı Təsvir')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Təsvir')
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Əlaqə Məlumatları')
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
                    ->label('Başlıq')
                    ->searchable()
                    ->limit(35),

                Tables\Columns\BadgeColumn::make('listing_type')
                    ->label('Növ')
                    ->formatStateUsing(fn ($state) => $state instanceof RoommateListingType ? $state->badgeLabel() : ($state === 'have_room' ? 'Otaq verilir' : 'Otaq axtarır'))
                    ->colors([
                        'success' => 'have_room',
                        'primary' => 'need_room',
                    ]),

                Tables\Columns\TextColumn::make('price')
                    ->label('Qiymət')
                    ->formatStateUsing(fn ($record) => $record->formatted_price)
                    ->sortable(),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('Şəhər')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['az'] ?? reset($state)) : $state),

                Tables\Columns\BadgeColumn::make('gender_preference')
                    ->label('Cinsiyyət')
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
                    ->label('Elan Növü')
                    ->options([
                        'have_room' => 'Otaq verilir',
                        'need_room' => 'Otaq axtarır',
                    ]),

                Tables\Filters\SelectFilter::make('gender_preference')
                    ->label('Cinsiyyət')
                    ->options([
                        'any' => 'Hamı üçün',
                        'female' => 'Yalnız Xanım',
                        'male' => 'Yalnız Bəy',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'published' => 'Dərc olunub',
                        'pending' => 'Gözləmədə',
                        'rejected' => 'İmtina edilib',
                        'closed' => 'Bağlanıb',
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
