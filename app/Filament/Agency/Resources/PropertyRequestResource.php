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
                Infolists\Components\Section::make(__('admin.customer_and_contact_information'))
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('contact_name')
                                    ->label(__('admin.customer_name_full'))
                                    ->weight('bold')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                                Infolists\Components\TextEntry::make('contact_phone')
                                    ->label(__('admin.contact_number'))
                                    ->weight('bold')
                                    ->icon('heroicon-o-phone')
                                    ->color('primary')
                                    ->url(fn ($record) => $record->contact_phone ? 'tel:' . preg_replace('/[^0-9+]/', '', $record->contact_phone) : null),

                                Infolists\Components\TextEntry::make('contact_whatsapp')
                                    ->label(__('admin.whatsapp_number'))
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

                Infolists\Components\Section::make(__('admin.searched_property_parameters'))
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                                    ->label(__('admin.request_title'))
                                    ->columnSpanFull()
                                    ->weight('bold'),

                                Infolists\Components\TextEntry::make('request_type')
                                    ->label(__('admin.request_type'))
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
                                    ->label(__('admin.property_type'))
                                    ->placeholder(__('admin.any')),

                                Infolists\Components\TextEntry::make('formatted_budget')
                                    ->label(__('admin.customer_budget'))
                                    ->weight('bold')
                                    ->color('success'),

                                Infolists\Components\TextEntry::make('city.name')
                                    ->label(__('admin.city'))
                                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['az'] ?? reset($state)) : $state),

                                Infolists\Components\TextEntry::make('district.name')
                                    ->label(__('admin.district_region'))
                                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['az'] ?? reset($state)) : $state)
                                    ->placeholder(__('admin.all_districts')),

                                Infolists\Components\TextEntry::make('rooms')
                                    ->label(__('admin.room_count'))
                                    ->placeholder(__('admin.any')),

                                Infolists\Components\TextEntry::make('location_note')
                                    ->label(__('admin.region_address_note'))
                                    ->placeholder('—')
                                    ->columnSpan(2),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('admin.request_date'))
                                    ->dateTime('d.m.Y H:i'),
                            ]),
                    ]),

                Infolists\Components\Section::make(__('admin.customer_notes_description'))
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
                    ->label(__('admin.request_type'))
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
                    ->label(__('admin.request_title'))
                    ->searchable()
                    ->weight('bold')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->title),

                Tables\Columns\TextColumn::make('property_type')
                    ->label(__('admin.property_type'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('city.name')
                    ->label(__('admin.city_district'))
                    ->formatStateUsing(function ($record) {
                        $city = is_array($record->city?->name) ? ($record->city->name['az'] ?? reset($record->city->name)) : $record->city?->name;
                        $district = is_array($record->district?->name) ? ($record->district->name['az'] ?? reset($record->district->name)) : $record->district?->name;
                        return $district ? "{$city}, {$district}" : ($city ?? '—');
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('formatted_budget')
                    ->label(__('admin.budget'))
                    ->weight('bold')
                    ->color('success')
                    ->sortable(['budget_max']),

                Tables\Columns\TextColumn::make('contact_name')
                    ->label(__('admin.customer'))
                    ->description(fn ($record) => $record->contact_phone)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('request_type')
                    ->label(__('admin.request_type'))
                    ->options([
                        'buy' => __('admin.wants_to_buy'),
                        'rent_monthly' => __('admin.looking_for_rent'),
                        'rent_daily' => __('admin.looking_for_daily'),
                        'roommate_have' => 'Otaq Verir',
                        'roommate_need' => __('admin.looking_for_room'),
                    ]),

                Tables\Filters\SelectFilter::make('city_id')
                    ->label(__('admin.city'))
                    ->relationship('city', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => is_array($record->name) ? ($record->name['az'] ?? reset($record->name)) : $record->name),

                Tables\Filters\SelectFilter::make('property_type')
                    ->label(__('admin.property_type'))
                    ->options([
                        'Mənzil' => __('admin.apartment'),
                        'Həyət evi' => __('admin.house_villa'),
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
                    ->label(__('admin.call'))
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
