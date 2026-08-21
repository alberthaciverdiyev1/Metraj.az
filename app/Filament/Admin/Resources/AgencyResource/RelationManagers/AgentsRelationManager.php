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

    protected static ?string $title = 'Agentliyin Rieltorları';

    protected static ?string $modelLabel = 'Rieltor';

    protected static ?string $pluralModelLabel = 'Rieltorlar';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('İstifadəçi Hesabı')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('position')
                    ->label('Vəzifəsi / Titul')
                    ->placeholder('Məs: Baş rieltor, Satış meneceri')
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('Əlaqə Nömrəsi')
                    ->tel()
                    ->required(),

                Forms\Components\TextInput::make('whatsapp')
                    ->label('WhatsApp Nömrəsi')
                    ->tel()
                    ->helperText('WhatsApp mesajlaşma üçün.')
                    ->prefixIcon('heroicon-o-chat-bubble-left-right'),

                Forms\Components\FileUpload::make('avatar')
                    ->label('Profil Şəkli')
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
                    ->label('Şəkil')
                    ->circular(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('position')
                    ->label('Vəzifə')
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
                    ->label('Elan Sayı')
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
