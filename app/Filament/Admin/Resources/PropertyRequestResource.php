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

    protected static ?string $navigationGroup = 'Əmlak və Müraciətlər';

    protected static ?string $navigationLabel = 'Əmlak Sifarişləri';

    protected static ?string $modelLabel = 'Əmlak Sifarişi';

    protected static ?string $pluralModelLabel = 'Əmlak Sifarişləri';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Əsas Məlumatlar')
                    ->schema([
                        Forms\Components\Select::make('request_type')
                            ->label('Tələb Növü')
                            ->options([
                                'buy' => 'Almaq İstəyir',
                                'rent_monthly' => 'Kirayə Axtarır',
                                'rent_daily' => 'Günlük Axtarır',
                                'roommate_have' => 'Otaq Verir',
                                'roommate_need' => 'Otaq Axtarır',
                            ])
                            ->required(),

                        Forms\Components\Select::make('property_type')
                            ->label('Əmlak Növü')
                            ->options([
                                'Mənzil' => 'Mənzil',
                                'Həyət evi' => 'Həyət evi / Bağ',
                                'Villa' => 'Villa',
                                'Torpaq' => 'Torpaq',
                                'Obyekt' => 'Obyekt',
                                'Ofis' => 'Ofis',
                            ]),

                        Forms\Components\TextInput::make('title')
                            ->label('Başlıq')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('budget_min')
                            ->label('Min. Büdcə (₼)')
                            ->numeric()
                            ->prefix('₼'),

                        Forms\Components\TextInput::make('budget_max')
                            ->label('Maks. Büdcə (₼)')
                            ->numeric()
                            ->required()
                            ->prefix('₼'),

                        Forms\Components\Select::make('rooms')
                            ->label('Otaq Sayı')
                            ->options([
                                '1' => '1 otaqlı',
                                '2' => '2 otaqlı',
                                '3' => '3 otaqlı',
                                '4+' => '4+ otaqlı',
                            ]),

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

                Forms\Components\Section::make('Yerləşmə və Tələblər')
                    ->schema([
                        Forms\Components\Select::make('city_id')
                            ->label('Şəhər')
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
                            ->label('Metro / Ünvan / Qeyd')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('has_deed')
                            ->label('Yalnız Kupçalı'),

                        Forms\Components\Toggle::make('mortgage_eligible')
                            ->label('İpotekaya yararlı'),

                        Forms\Components\Toggle::make('bills_included')
                            ->label('Kommunal daxil'),
                    ])->columns(2),

                Forms\Components\Section::make('Təsvir')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Ətraflı Təsvir')
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Əlaqə')
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
                    ->label('Başlıq')
                    ->searchable()
                    ->limit(35),

                Tables\Columns\BadgeColumn::make('request_type')
                    ->label('Tələb Növü')
                    ->formatStateUsing(fn ($state) => $state instanceof RequestType ? $state->badgeLabel() : $state)
                    ->colors([
                        'success' => 'buy',
                        'primary' => 'rent_monthly',
                        'warning' => 'rent_daily',
                        'danger' => 'roommate_have',
                        'secondary' => 'roommate_need',
                    ]),

                Tables\Columns\TextColumn::make('budget_max')
                    ->label('Büdcə')
                    ->formatStateUsing(fn ($record) => $record->formatted_budget)
                    ->sortable(),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('Şəhər')
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
                    ->label('Tələb Növü')
                    ->options([
                        'buy' => 'Almaq İstəyir',
                        'rent_monthly' => 'Kirayə Axtarır',
                        'rent_daily' => 'Günlük Axtarır',
                        'roommate_have' => 'Otaq Verir',
                        'roommate_need' => 'Otaq Axtarır',
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
            'index' => Pages\ListPropertyRequests::route('/'),
            'create' => Pages\CreatePropertyRequest::route('/create'),
            'edit' => Pages\EditPropertyRequest::route('/{record}/edit'),
        ];
    }
}
