<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ActivityLogResource\Pages;
use App\Modules\Shared\Models\ActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    public static function getNavigationGroup(): ?string {
        return __('admin.system_and_monitoring'); }

    public static function getNavigationLabel(): string {
        return __('admin.activity_history'); }

    protected static ?string $modelLabel = 'Aktivlik Qeydi';

    public static function getPluralModelLabel(): string {
        return __('admin.activity_history'); }

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('LogDetailsTabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('admin.general_information'))
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Placeholder::make('user_name')
                                        ->label(__('admin.user'))
                                        ->content(fn ($record) => $record?->user ? "{$record->user->name} ({$record->user->email})" : 'Qonaq (Qeydiyyatsız)'),

                                    Forms\Components\TextInput::make('action')
                                        ->label(__('admin.action_event'))
                                        ->disabled(),

                                    Forms\Components\TextInput::make('ip_address')
                                        ->label(__('admin.ip_address'))
                                        ->disabled(),

                                    Forms\Components\TextInput::make('method')
                                        ->label('HTTP Metod')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('status_code')
                                        ->label('Status Kodu')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('duration_ms')
                                        ->label(__('admin.execution_time'))
                                        ->formatStateUsing(fn ($state) => $state ? "{$state} ms" : '—')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('url')
                                        ->label(__('admin.request_url'))
                                        ->disabled()
                                        ->columnSpanFull(),

                                    Forms\Components\TextInput::make('referer')
                                        ->label(__('admin.referer'))
                                        ->disabled()
                                        ->columnSpanFull(),

                                    Forms\Components\DateTimePicker::make('created_at')
                                        ->label(__('admin.recorded_at'))
                                        ->disabled()
                                        ->columnSpanFull(),
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('admin.device_and_browser'))
                            ->icon('heroicon-o-device-phone-mobile')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('device_type')
                                        ->label('Cihaz Tipi')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('browser')
                                        ->label('Brauzer')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('os')
                                        ->label(__('admin.operating_system'))
                                        ->disabled(),

                                    Forms\Components\Textarea::make('user_agent')
                                        ->label('Tam User Agent')
                                        ->rows(4)
                                        ->disabled()
                                        ->columnSpanFull(),
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('admin.location_and_map'))
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('location_name')
                                        ->label(__('admin.city_and_country'))
                                        ->formatStateUsing(fn ($record) => $record?->location_text)
                                        ->disabled(),

                                    Forms\Components\TextInput::make('isp')
                                        ->label(__('admin.isp'))
                                        ->disabled(),

                                    Forms\Components\TextInput::make('latitude')
                                        ->label('Enlik (Latitude)')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('longitude')
                                        ->label('Uzunluq (Longitude)')
                                        ->disabled(),
                                ]),

                                Forms\Components\View::make('filament.components.activity-log-map')
                                    ->viewData(fn ($record) => ['record' => $record])
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('admin.data_structure_payload'))
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Forms\Components\Textarea::make('payload')
                                    ->label(__('admin.data_json_format'))
                                    ->disabled()
                                    ->rows(14)
                                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->poll('15s')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Vaxt')
                    ->dateTime('d.m.Y H:i:s')
                    ->description(fn (ActivityLog $record) => $record->created_at?->diffForHumans())
                    ->sortable(),

                Tables\Columns\TextColumn::make('user')
                    ->label(__('admin.user_bot'))
                    ->formatStateUsing(function ($state, ActivityLog $record) {
                        $payload = $record->payload;
                        if (!empty($payload['bot_name'])) {
                            return '🤖 ' . $payload['bot_name'];
                        }
                        if ($record->user) {
                            return '👤 ' . $record->user->name;
                        }
                        return '🌐 Qonaq';
                    })
                    ->description(function (ActivityLog $record) {
                        $payload = $record->payload;
                        if ($record->user) {
                            return $record->user->email;
                        }
                        if (!empty($payload['user_email'])) {
                            return $payload['user_email'];
                        }
                        return 'Qeydiyyatsız ziyarətçi';
                    })
                    ->searchable(['user_id']),

                Tables\Columns\TextColumn::make('action')
                    ->label(__('admin.event'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'user_login' => __('admin.icon_login'),
                        'user_logout' => __('admin.icon_logout'),
                        'auth_failed' => __('admin.icon_login_error'),
                        'user_registered' => '✨ Yeni Qeydiyyat',
                        'password_reset' => __('admin.icon_password_changed'),
                        'model_created' => __('admin.icon_created'),
                        'model_updated' => __('admin.icon_edited'),
                        'model_deleted' => '🗑️ Silindi',
                        'search_filter' => __('admin.icon_search_filter'),
                        'property_view' => __('admin.icon_property_view'),
                        'page_view' => __('admin.icon_page_visit'),
                        'admin_view' => '🛠️ Admin Panel',
                        'admin_action' => __('admin.icon_admin_operation'),
                        'agency_view' => '🏢 Agentlik Paneli',
                        'agency_action' => __('admin.icon_agency_operation'),
                        'form_submit' => __('admin.icon_form_submission'),
                        'server_error' => __('admin.icon_500_server_error'),
                        'not_found_404' => __('admin.icon_404_not_found'),
                        'bot_visit' => __('admin.search_bot'),
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'user_login', 'user_registered', 'model_created' => 'success',
                        'model_updated', 'page_view', 'property_view', 'search_filter' => 'info',
                        'admin_view', 'admin_action', 'agency_view', 'agency_action' => 'warning',
                        'model_deleted', 'auth_failed', 'server_error' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('location')
                    ->label(__('admin.location_city'))
                    ->state(fn (ActivityLog $record) => $record->location_text)
                    ->description(fn (ActivityLog $record) => $record->isp ?: $record->ip_address)
                    ->searchable(['city', 'country_name', 'country_code', 'ip_address']),

                Tables\Columns\TextColumn::make('device')
                    ->label('Cihaz / Brauzer')
                    ->state(function (ActivityLog $record) {
                        $device = $record->device_type ?: 'Desktop';
                        $icon = match ($device) {
                            'Mobile' => '📱',
                            'Tablet' => '📟',
                            'Bot' => '🤖',
                            default => '💻',
                        };
                        $os = $record->os ? " ({$record->os})" : '';
                        $browser = $record->browser ? " {$record->browser}" : '';
                        return "{$icon} {$device}{$browser}{$os}";
                    }),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('admin.ip_address'))
                    ->copyable()
                    ->copyMessage('IP ünvanı kopyalandı')
                    ->fontFamily('mono')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status_code')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?int $state): string => match (true) {
                        $state >= 200 && $state < 300 => 'success',
                        $state >= 300 && $state < 400 => 'info',
                        $state >= 400 && $state < 500 => 'warning',
                        $state >= 500 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?int $state) => $state ? (string) $state : '—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label(__('admin.event_type'))
                    ->options([
                        'user_login' => __('admin.icon_logins'),
                        'auth_failed' => __('admin.icon_failed_logins'),
                        'user_registered' => '✨ Yeni Qeydiyyatlar',
                        'model_created' => __('admin.icon_data_created'),
                        'model_updated' => __('admin.icon_edit_update'),
                        'model_deleted' => __('admin.icon_deletion_operations'),
                        'search_filter' => __('admin.icon_search_and_filters'),
                        'property_view' => __('admin.icon_property_detail_views'),
                        'admin_action' => __('admin.icon_admin_operations'),
                        'server_error' => __('admin.icon_server_errors'),
                        'bot_visit' => __('admin.icon_bot_visits'),
                    ]),

                Tables\Filters\SelectFilter::make('device_type')
                    ->label(__('admin.device_type'))
                    ->options([
                        'Desktop' => __('admin.desktop'),
                        'Mobile' => '📱 Mobil Telefon',
                        'Tablet' => __('admin.tablet'),
                        'Bot' => __('admin.search_bot'),
                    ]),

                Tables\Filters\SelectFilter::make('country_code')
                    ->label(__('admin.country'))
                    ->options(function () {
                        return ActivityLog::query()
                            ->whereNotNull('country_code')
                            ->distinct()
                            ->pluck('country_name', 'country_code')
                            ->filter()
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view_map')
                    ->label(__('admin.view_on_map'))
                    ->icon('heroicon-o-map-pin')
                    ->color('warning')
                    ->modalHeading(fn (ActivityLog $record) => __('admin.location_map_popup', ['location' => $record->location_text]))
                    ->modalDescription(fn (ActivityLog $record) => __('admin.ip_vaxt_event', ['ip' => $record->ip_address, 'time' => $record->created_at?->format('d.m.Y H:i:s'), 'event' => $record->action]))
                    ->modalContent(fn (ActivityLog $record) => view('filament.components.activity-log-map', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('admin.close')),

                Tables\Actions\ViewAction::make()
                    ->label('Detallar')
                    ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([]);
    }

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Admin\Resources\ActivityLogResource\Widgets\ActivityLogStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
