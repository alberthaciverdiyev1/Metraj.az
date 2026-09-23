<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Agency\Enums\AgencyStatus;
use App\Modules\Agency\Models\Agency;
use App\Filament\Admin\Resources\AgencyResource\Pages;
use App\Filament\Admin\Resources\AgencyResource\RelationManagers\AgentsRelationManager;
use App\Filament\Admin\Resources\AgencyResource\RelationManagers\PropertiesRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AgencyResource extends Resource
{
    protected static ?string $model = Agency::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationGroup(): ?string {
        return __('admin.users_and_agencies'); }

    public static function getNavigationLabel(): string {
        return __('admin.agencies'); }

    protected static ?string $modelLabel = 'Agentlik';

    public static function getPluralModelLabel(): string {
        return __('admin.agencies'); }

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.agency_information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('admin.agency_name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('owner_id')
                            ->label(__('admin.owner_user'))
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(collect(AgencyStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()]))
                            ->default(AgencyStatus::Pending->value)
                            ->required(),

                        Forms\Components\Toggle::make('is_verified')
                            ->label(__('admin.verified_agency_badge'))
                            ->default(false),

                        Forms\Components\Textarea::make('description')
                            ->label(__('admin.detailed_about'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make(__('admin.logo_and_banner'))
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Agentlik Loqosu')
                            ->image()
                            ->imageEditor()
                            ->directory('agencies')
                            ->visibility('public')
                            ->helperText(__('admin.agency_avatar_hint'))
                            ->columnSpan(1),

                        Forms\Components\FileUpload::make('banner')
                            ->label(__('admin.banner_image'))
                            ->image()
                            ->imageEditor()
                            ->directory('agencies')
                            ->visibility('public')
                            ->helperText(__('admin.agency_banner_hint'))
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make(__('admin.contact_and_address'))
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label(__('admin.phone_number'))
                            ->tel()
                            ->required(),

                        Forms\Components\TextInput::make('whatsapp')
                            ->label(__('admin.whatsapp_number'))
                            ->tel()
                            ->helperText(__('admin.for_whatsapp_button_hint'))
                            ->prefixIcon('heroicon-o-chat-bubble-left-right'),

                        Forms\Components\TextInput::make('email')
                            ->label(__('admin.official_email'))
                            ->email(),

                        Forms\Components\TextInput::make('website')
                            ->label('Vebsayt')
                            ->url()
                            ->placeholder('https://...'),

                        Forms\Components\TextInput::make('address')
                            ->label(__('admin.office_address'))
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Loqo')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=' . urlencode('A') . '&background=F97316&color=fff&size=80'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.agency_name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('owner.name')
                    ->label(__('admin.manager_owner'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success'),

                Tables\Columns\TextColumn::make('agents_count')
                    ->counts('agents')
                    ->label(__('admin.agent_count'))
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('properties_count')
                    ->counts('properties')
                    ->label(__('admin.listing_count'))
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (AgencyStatus $state): string => match ($state) {
                        AgencyStatus::Pending => 'warning',
                        AgencyStatus::Active => 'success',
                        AgencyStatus::Suspended => 'danger',
                        AgencyStatus::Inactive => 'gray',
                    })
                    ->formatStateUsing(fn (AgencyStatus $state): string => $state->label()),

                Tables\Columns\IconColumn::make('is_verified')
                    ->label(__('admin.verified'))
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(AgencyStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])),
                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label(__('admin.verification_status')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (\Illuminate\Database\Eloquent\Model $record): string => static::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AgentsRelationManager::class,
            PropertiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgencies::route('/'),
            'create' => Pages\CreateAgency::route('/create'),
            'view' => Pages\ViewAgency::route('/{record}'),
            'edit' => Pages\EditAgency::route('/{record}/edit'),
        ];
    }
}
