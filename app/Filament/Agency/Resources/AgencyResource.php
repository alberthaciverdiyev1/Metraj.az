<?php

namespace App\Filament\Agency\Resources;

use App\Modules\Agency\Models\Agency;
use App\Filament\Admin\Resources\AgencyResource\RelationManagers\AgentsRelationManager;
use App\Filament\Admin\Resources\AgencyResource\RelationManagers\PropertiesRelationManager;
use App\Filament\Agency\Resources\AgencyResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AgencyResource extends Resource
{
    protected static ?string $model = Agency::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationLabel(): string
    {
        return __('panel.agency_info');
    }

    public static function getModelLabel(): string
    {
        return __('panel.agency_info');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel.agency_info');
    }

    protected static ?int $navigationSort = 1;

    /**
     * Yalnız agentlik sahiblərinə göstərilir.
     */
    public static function canViewAny(): bool
    {
        return (bool) Auth::user()?->isTenantOwner();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    /**
     * Scoping: Yalnız daxil olmuş istifadəçinin öz agentliyi.
     */
    public static function getEloquentQuery(): Builder
    {
        $tenantAgency = Auth::user()?->tenantAgency();

        return parent::getEloquentQuery()
            ->when($tenantAgency, fn (Builder $q) => $q->where('id', $tenantAgency->id))
            ->when(! $tenantAgency, fn (Builder $q) => $q->whereRaw('1 = 0'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Agentlik Məlumatları')
                    ->description('Veb saytında və elanlarınızda görünən rəsmi agentlik detalları.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Agentliyin Adı')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Haqqında Ətraflı Məlumat')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Agentliyinizin fəaliyyəti, təcrübəsi və xidmətləri haqqında ətraflı məlumat.'),
                    ])->columns(1),

                Forms\Components\Section::make('Logo və Banner')
                    ->description('Agentlik profilinizin vizual tərtibatı.')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Agentlik Loqosu')
                            ->image()
                            ->imageEditor()
                            ->directory('agencies')
                            ->visibility('public')
                            ->helperText('Dairəvi profil şəkli. Web saytında agentlik kartında və elanlarda görünür.')
                            ->columnSpan(1),

                        Forms\Components\FileUpload::make('banner')
                            ->label('Banner Şəkli')
                            ->image()
                            ->imageEditor()
                            ->directory('agencies')
                            ->visibility('public')
                            ->helperText('Agentlik detal səhifəsinin üst hissəsindəki geniş banner şəkli.')
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make('Əlaqə və Ünvan')
                    ->description('Müştərilərin sizinlə əlaqə saxlaması üçün istifadə olunacaq məlumatlar.')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon Nömrəsi')
                            ->tel()
                            ->required()
                            ->helperText('Rəsmi əlaqə telefonu.'),

                        Forms\Components\TextInput::make('whatsapp')
                            ->label('WhatsApp Nömrəsi')
                            ->tel()
                            ->prefixIcon('heroicon-o-chat-bubble-left-right')
                            ->helperText('WhatsApp vasitəsilə birbaşa yazışma üçün.'),

                        Forms\Components\TextInput::make('email')
                            ->label('Rəsmi E-poçt')
                            ->email()
                            ->helperText('Rəsmi müraciətlər və sorğular üçün e-poçt ünvanı.'),

                        Forms\Components\TextInput::make('website')
                            ->label('Vebsayt')
                            ->url()
                            ->placeholder('https://...'),

                        Forms\Components\TextInput::make('address')
                            ->label('Ofis Ünvanı')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Fiziki ofisinizin yerləşdiyi tam ünvan.'),
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
                    ->label('Agentliyin Adı')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success'),

                Tables\Columns\TextColumn::make('agents_count')
                    ->counts('agents')
                    ->label('Rieltor Sayı')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('properties_count')
                    ->counts('properties')
                    ->label('Elan Sayı')
                    ->badge()
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Məlumatları Yenilə')
                    ->icon('heroicon-o-pencil-square'),
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
            'edit' => Pages\EditAgency::route('/{record}/edit'),
        ];
    }
}
