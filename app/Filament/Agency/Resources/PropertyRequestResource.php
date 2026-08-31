<?php

namespace App\Filament\Agency\Resources;

use App\Filament\Agency\Resources\PropertyRequestResource\Pages;
use App\Modules\PropertyRequest\Enums\RequestStatus;
use App\Modules\PropertyRequest\Enums\RequestType;
use App\Modules\PropertyRequest\Models\PropertyRequest;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PropertyRequestResource extends Resource
{
    protected static ?string $model = PropertyRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    public static function getNavigationLabel(): string
    {
        return __('panel.property_requests');
    }

    public static function getModelLabel(): string
    {
        return __('panel.property_requests');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel.property_requests');
    }

    protected static ?int $navigationSort = 2;

    /**
     * Arıyorum (Müştəri Tələbləri) yalnız Adminlərə, Agentlik sahiblərinə və Rieltorlara göstərilir.
     * Adi istifadəçilər üçün tamamilə gizlidir.
     */
    public static function canViewAny(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (! $user) {
            return false;
        }

        return $user->isAdmin() || $user->isTenantOwner() || $user->agent()->exists();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', RequestStatus::Published)
            ->latest();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Müştəri və Əlaqə Məlumatları')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('contact_name')
                                    ->label('Müştəri Adı')
                                    ->weight('bold')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                                Infolists\Components\TextEntry::make('contact_phone')
                                    ->label('Əlaqə Nömrəsi')
                                    ->weight('bold')
                                    ->icon('heroicon-o-phone')
                                    ->color('primary')
                                    ->url(fn ($record) => $record->contact_phone ? 'tel:' . preg_replace('/[^0-9+]/', '', $record->contact_phone) : null),

                                Infolists\Components\TextEntry::make('contact_whatsapp')
                                    ->label('WhatsApp Nömrəsi')
                                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                                    ->color('success')
                                    ->placeholder('—')
                                    ->url(fn ($record) => ! empty($record->contact_whatsapp ?? $record->contact_phone)
                                        ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->contact_whatsapp ?? $record->contact_phone)
                                        : null
                                    )
                                    ->openUrlInNewTab(),
                            ]),
                    ]),

                Infolists\Components\Section::make('Axtarılan Əmlak Parametrləri')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                                    ->label('Tələb Başlığı')
                                    ->columnSpanFull()
                                    ->weight('bold'),

                                Infolists\Components\TextEntry::make('request_type')
                                    ->label('Tələb Növü')
                                    ->badge()
                                    ->formatStateUsing(fn ($state) => $state instanceof RequestType ? $state->badgeLabel() : $state)
                                    ->color(fn ($state) => match ($state instanceof RequestType ? $state->value : $state) {
                                        'buy' => 'success',
                                        'rent_monthly' => 'primary',
                                        'rent_daily' => 'warning',
                                        'roommate_have' => 'danger',
                                        'roommate_need' => 'gray',
                                        default => 'primary',
                                    }),

                                Infolists\Components\TextEntry::make('property_type')
                                    ->label('Əmlak Növü')
                                    ->placeholder('Fərq etməz'),

                                Infolists\Components\TextEntry::make('formatted_budget')
                                    ->label('Müştərinin Büdcəsi')
                                    ->weight('bold')
                                    ->color('success'),

                                Infolists\Components\TextEntry::make('city.name')
                                    ->label('Şəhər')
                                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['az'] ?? reset($state)) : $state),

                                Infolists\Components\TextEntry::make('district.name')
                                    ->label('Rayon / Bölgə')
                                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['az'] ?? reset($state)) : $state)
                                    ->placeholder('Bütün rayonlar'),

                                Infolists\Components\TextEntry::make('rooms')
                                    ->label('Otaq Sayı')
                                    ->placeholder('Fərq etməz'),

                                Infolists\Components\TextEntry::make('location_note')
                                    ->label('Bölgə / Ünvan Qeydi')
                                    ->placeholder('—')
                                    ->columnSpan(2),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Tələb Tarixi')
                                    ->dateTime('d.m.Y H:i'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Müştərinin Əlavə Qeydi və Təsviri')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->label('')
                            ->columnSpanFull()
                            ->prose(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('request_type')
                    ->label('Tələb Növü')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof RequestType ? $state->badgeLabel() : $state)
                    ->color(fn ($state) => match ($state instanceof RequestType ? $state->value : $state) {
                        'buy' => 'success',
                        'rent_monthly' => 'primary',
                        'rent_daily' => 'warning',
                        'roommate_have' => 'danger',
                        'roommate_need' => 'gray',
                        default => 'primary',
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tələb Başlığı')
                    ->searchable()
                    ->weight('bold')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->title),

                Tables\Columns\TextColumn::make('property_type')
                    ->label('Əmlak Növü')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('Şəhər / Rayon')
                    ->formatStateUsing(function ($record) {
                        $city = is_array($record->city?->name) ? ($record->city->name['az'] ?? reset($record->city->name)) : $record->city?->name;
                        $district = is_array($record->district?->name) ? ($record->district->name['az'] ?? reset($record->district->name)) : $record->district?->name;
                        return $district ? "{$city}, {$district}" : ($city ?? '—');
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('formatted_budget')
                    ->label('Büdcə')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(['budget_max']),

                Tables\Columns\TextColumn::make('contact_name')
                    ->label('Müştəri')
                    ->description(fn ($record) => $record->contact_phone)
                    ->searchable(),

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

                Tables\Filters\SelectFilter::make('city_id')
                    ->label('Şəhər')
                    ->relationship('city', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => is_array($record->name) ? ($record->name['az'] ?? reset($record->name)) : $record->name),

                Tables\Filters\SelectFilter::make('property_type')
                    ->label('Əmlak Növü')
                    ->options([
                        'Mənzil' => 'Mənzil',
                        'Həyət evi' => 'Həyət evi / Villa',
                        'Torpaq' => 'Torpaq',
                        'Obyekt' => 'Obyekt',
                        'Ofis' => 'Ofis',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Bax')
                    ->icon('heroicon-o-eye'),

                Tables\Actions\Action::make('call')
                    ->label('Zəng et')
                    ->icon('heroicon-o-phone')
                    ->color('warning')
                    ->url(fn ($record) => $record->contact_phone ? 'tel:' . preg_replace('/[^0-9+]/', '', $record->contact_phone) : null)
                    ->visible(fn ($record) => ! empty($record->contact_phone)),

                Tables\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(fn ($record) => ! empty($record->contact_whatsapp ?? $record->contact_phone)
                        ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->contact_whatsapp ?? $record->contact_phone) . '?text=' . urlencode('Salam ' . $record->contact_name . ', KibrisKare-dəki "' . $record->title . '" tələbinizlə bağlı əlaqə saxlayıram.')
                        : null
                    )
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => ! empty($record->contact_whatsapp ?? $record->contact_phone)),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropertyRequests::route('/'),
            'view' => Pages\ViewPropertyRequest::route('/{record}'),
        ];
    }
}
