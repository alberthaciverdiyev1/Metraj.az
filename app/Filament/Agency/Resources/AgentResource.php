<?php

namespace App\Filament\Agency\Resources;

use App\Modules\Agency\Models\Agent;
use App\Filament\Agency\Resources\AgentResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getNavigationLabel(): string
    {
        return __('panel.my_agents');
    }

    public static function getModelLabel(): string
    {
        return __('panel.my_agents');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel.my_agents');
    }

    protected static ?int $navigationSort = 2;

    /**
     * Tenant scoping: yalnız istifadəçinin öz agentliyinə aid rieltorlar.
     * Rieltorların idarəsi yalnız agentlik sahibinə açıqdır.
     */
    public static function canViewAny(): bool
    {
        return (bool) Auth::user()?->isTenantOwner();
    }

    public static function canCreate(): bool
    {
        return (bool) Auth::user()?->isTenantOwner();
    }

    public static function getEloquentQuery(): Builder
    {
        $tenantAgency = Auth::user()?->tenantAgency();

        return parent::getEloquentQuery()
            ->when($tenantAgency, fn (Builder $q) => $q->where('agency_id', $tenantAgency->id))
            ->when(! $tenantAgency, fn (Builder $q) => $q->whereRaw('1 = 0'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. Create Mode User Account
                Forms\Components\Section::make('Yeni İstifadəçi Hesabı')
                    ->description('Hər yeni rieltor üçün yeni istifadəçi hesabı yaradılır.')
                    ->visibleOn('create')
                    ->schema([
                        Forms\Components\TextInput::make('new_user_name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('new_user_email')
                            ->label('E-poçt')
                            ->email()
                            ->required()
                            ->unique(table: 'users', column: 'email')
                            ->maxLength(255)
                            ->helperText('Bu istifadəçi agentlik paneline daxil ola biləcək.'),

                        Forms\Components\TextInput::make('new_user_password')
                            ->label('Şifrə')
                            ->password()
                            ->required()
                            ->revealable()
                            ->minLength(6),
                    ])->columns(3),

                // 2. Edit Mode User Account
                Forms\Components\Section::make('İstifadəçi Hesabı Məlumatları')
                    ->description('Rieltorun sistemə giriş hesabı və əlaqə detalları.')
                    ->visibleOn('edit')
                    ->schema([
                        Forms\Components\TextInput::make('user_name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('user_email')
                            ->label('E-poçt')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('new_password')
                            ->label('Yeni Şifrə (Dəyişmək istəmirsinizsə boş qoyun)')
                            ->password()
                            ->revealable()
                            ->nullable()
                            ->minLength(6),
                    ])->columns(3),

                // 3. Realtor Details
                Forms\Components\Section::make('Rieltor Məlumatları')
                    ->description('Veb saytında və elanlarda görünən profil məlumatları.')
                    ->schema([
                        Forms\Components\TextInput::make('position')
                            ->label('Vəzifə / Titul')
                            ->placeholder('Məs: Baş rieltor, Satış meneceri')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Əlaqə Nömrəsi')
                            ->tel()
                            ->required(),

                        Forms\Components\TextInput::make('whatsapp')
                            ->label('WhatsApp Nömrəsi')
                            ->tel()
                            ->prefixIcon('heroicon-o-chat-bubble-left-right'),

                        Forms\Components\FileUpload::make('avatar')
                            ->label('Profil Şəkli')
                            ->image()
                            ->imageEditor()
                            ->directory('agents')
                            ->visibility('public')
                            ->columnSpan(1),

                        Forms\Components\FileUpload::make('banner')
                            ->label('Banner Şəkli (Üzlük)')
                            ->image()
                            ->imageEditor()
                            ->directory('agents/banners')
                            ->visibility('public')
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktivdir')
                            ->default(true),

                        Forms\Components\Hidden::make('agency_id'),
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
            'view' => Pages\ViewAgent::route('/{record}'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
