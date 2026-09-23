<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Agency\Models\Agent;
use App\Filament\Admin\Resources\AgentResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function getNavigationGroup(): ?string {
        return __('admin.users_and_agencies'); }

    public static function getNavigationLabel(): string {
        return __('admin.agents_realtors'); }

    protected static ?string $modelLabel = 'Agent';

    public static function getPluralModelLabel(): string {
        return __('admin.agents'); }

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.realtor_information'))
                    ->schema([
                        Forms\Components\Select::make('agency_id')
                            ->label(__('admin.affiliated_agency'))
                            ->relationship('agency', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder(__('admin.independent_realtor_no_agency'))
                            ->helperText(__('admin.independent_realtor_hint')),

                        Forms\Components\Select::make('user_id')
                            ->label(__('admin.user_account'))
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('position')
                            ->label(__('admin.position_title'))
                            ->placeholder(__('admin.position_placeholder'))
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label(__('admin.contact_number'))
                            ->tel()
                            ->required(),

                        Forms\Components\TextInput::make('whatsapp')
                            ->label(__('admin.whatsapp_number'))
                            ->tel()
                            ->helperText(__('admin.for_whatsapp_messaging'))
                            ->prefixIcon('heroicon-o-chat-bubble-left-right'),

                        Forms\Components\FileUpload::make('avatar')
                            ->label(__('admin.profile_image_avatar'))
                            ->image()
                            ->imageEditor()
                            ->directory('agents')
                            ->visibility('public')
                            ->helperText(__('admin.realtor_avatar_hint'))
                            ->columnSpan(1),

                        Forms\Components\FileUpload::make('banner')
                            ->label(__('admin.banner_image_cover'))
                            ->image()
                            ->imageEditor()
                            ->directory('agents/banners')
                            ->visibility('public')
                            ->helperText(__('admin.realtor_banner_hint'))
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktivdir')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('admin.image'))
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user?->name ?? 'R') . '&background=F97316&color=fff&size=80'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('agency.name')
                    ->label('Agentlik')
                    ->searchable()
                    ->sortable()
                    ->placeholder(__('admin.independent'))
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : 'gray')
                    ->icon(fn ($state) => $state ? 'heroicon-o-building-office-2' : 'heroicon-o-user'),

                Tables\Columns\TextColumn::make('position')
                    ->label(__('admin.position'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->color('success')
                    ->icon('heroicon-o-chat-bubble-left-right'),

                Tables\Columns\TextColumn::make('properties_count')
                    ->counts('properties')
                    ->label(__('admin.listing_count'))
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('agency_id')
                    ->label('Agentlik')
                    ->relationship('agency', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktivlik'),
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
            \App\Filament\Agency\Resources\AgentResource\RelationManagers\PropertiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
