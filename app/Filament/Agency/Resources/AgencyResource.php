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
                Forms\Components\Section::make(__('admin.agency_information'))
                    ->description(__('admin.agency_public_details'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('admin.agency_name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label(__('admin.detailed_about'))
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText(__('admin.agency_activity_hint')),
                    ])->columns(1),

                Forms\Components\Section::make(__('admin.logo_and_banner'))
                    ->description(__('admin.agency_profile_visual'))
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Agentlik Loqosu')
                            ->image()
                            ->imageEditor()
                            ->directory('agencies')
                            ->visibility('public')
                            ->helperText(__('admin.agency_avatar_hint_2'))
                            ->columnSpan(1),

                        Forms\Components\FileUpload::make('banner')
                            ->label(__('admin.banner_image'))
                            ->image()
                            ->imageEditor()
                            ->directory('agencies')
                            ->visibility('public')
                            ->helperText(__('admin.agency_banner_hint_show'))
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make(__('admin.contact_and_address'))
                    ->description(__('admin.contact_info_hint'))
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label(__('admin.phone_number'))
                            ->tel()
                            ->required()
                            ->helperText(__('admin.official_phone_hint')),

                        Forms\Components\TextInput::make('whatsapp')
                            ->label(__('admin.whatsapp_number'))
                            ->tel()
                            ->prefixIcon('heroicon-o-chat-bubble-left-right')
                            ->helperText(__('admin.for_direct_whatsapp_chat')),

                        Forms\Components\TextInput::make('email')
                            ->label(__('admin.official_email'))
                            ->email()
                            ->helperText(__('admin.official_email_hint')),

                        Forms\Components\TextInput::make('website')
                            ->label('Vebsayt')
                            ->url()
                            ->placeholder('https://...'),

                        Forms\Components\TextInput::make('address')
                            ->label(__('admin.office_address'))
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText(__('admin.full_office_address_hint')),
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
                    ->label(__('admin.realtor_count'))
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('properties_count')
                    ->counts('properties')
                    ->label(__('admin.listing_count'))
                    ->badge()
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('admin.update_data'))
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
