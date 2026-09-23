<?php

namespace App\Filament\Admin\Resources\AgencyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AgentsRelationManager extends RelationManager
{
    protected static string $relationship = 'agents';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string {
        return __('admin.agency_realtors'); }

    protected static ?string $modelLabel = 'Rieltor';

    protected static ?string $pluralModelLabel = 'Rieltorlar';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(__('admin.user_account'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

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
                    ->label(__('admin.profile_image'))
                    ->image()
                    ->imageEditor()
                    ->directory('agents')
                    ->visibility('public'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktivdir')
                    ->default(true),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('admin.image'))
                    ->circular(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('position')
                    ->label(__('admin.position'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->color('success'),

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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktivlik'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Yeni Rieltor'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
