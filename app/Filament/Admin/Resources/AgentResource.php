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

    protected static ?string $navigationGroup = 'İstifadəçilər və Agentliklər';

    protected static ?string $navigationLabel = 'Agentlər / Rieltorlar';

    protected static ?string $modelLabel = 'Agent';

    protected static ?string $pluralModelLabel = 'Agentlər';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rieltor Məlumatları')
                    ->schema([
                        Forms\Components\Select::make('agency_id')
                            ->label('Aid Olduğu Agentlik')
                            ->relationship('agency', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Müstəqil Rieltor (Agentliksiz)')
                            ->helperText('Boş buraxılarsa rieltor "müstəqil" sayılır və /agencies səhifəsində "Müstəqil Rieltorlar" bölümündə görünür.'),

                        Forms\Components\Select::make('user_id')
                            ->label('İstifadəçi Hesabı')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

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
                            ->visibility('public')
                            ->helperText('Web saytındakı rieltor kartında və detal səhifəsində görünür.')
                            ->columnSpanFull(),

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
                    ->label('Şəkil')
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
                    ->placeholder('Müstəqil')
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : 'gray')
                    ->icon(fn ($state) => $state ? 'heroicon-o-building-office-2' : 'heroicon-o-user'),

                Tables\Columns\TextColumn::make('position')
                    ->label('Vəzifə')
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
                    ->label('Elan Sayı')
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
