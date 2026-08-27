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

    protected static ?string $navigationGroup = 'Sistem və Monitorinq';

    protected static ?string $navigationLabel = 'Aktivlik və Hərəkət Tarixçəsi';

    protected static ?string $modelLabel = 'Aktivlik Qeydi';

    protected static ?string $pluralModelLabel = 'Aktivlik və Hərəkət Tarixçəsi';

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
                        Forms\Components\Tabs\Tab::make('Ümumi Məlumat')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Placeholder::make('user_name')
                                        ->label('İstifadəçi')
                                        ->content(fn ($record) => $record?->user ? "{$record->user->name} ({$record->user->email})" : 'Qonaq (Qeydiyyatsız)'),

                                    Forms\Components\TextInput::make('action')
                                        ->label('Hərəkət / Hadisə')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('ip_address')
                                        ->label('IP Ünvanı')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('method')
                                        ->label('HTTP Metod')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('status_code')
                                        ->label('Status Kodu')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('duration_ms')
                                        ->label('İcra Müddəti')
                                        ->formatStateUsing(fn ($state) => $state ? "{$state} ms" : '—')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('url')
                                        ->label('Sorğu URL')
                                        ->disabled()
                                        ->columnSpanFull(),

                                    Forms\Components\TextInput::make('referer')
                                        ->label('Gəldiyi Səhifə (Referer)')
                                        ->disabled()
                                        ->columnSpanFull(),

                                    Forms\Components\DateTimePicker::make('created_at')
                                        ->label('Qeyd Vaxtı')
                                        ->disabled()
                                        ->columnSpanFull(),
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Cihaz və Brauzer')
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
                                        ->label('Əməliyyat Sistemi')
                                        ->disabled(),

                                    Forms\Components\Textarea::make('user_agent')
                                        ->label('Tam User Agent')
                                        ->rows(4)
                                        ->disabled()
                                        ->columnSpanFull(),
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Məkan və Xəritə')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('location_name')
                                        ->label('Şəhər və Ölkə')
                                        ->formatStateUsing(fn ($record) => $record?->location_text)
                                        ->disabled(),

                                    Forms\Components\TextInput::make('isp')
                                        ->label('İnternet Provayder (ISP)')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('latitude')
                                        ->label('Enlik (Latitude)')
                                        ->disabled(),

                                    Forms\Components\TextInput::make('longitude')
                                        ->label('Uzunluq (Longitude)')
                                        ->disabled(),
                                ]),

                                Forms\Components\View::make('filament.components.activity-log-map')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Məlumat Strukturu (Payload)')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Forms\Components\Textarea::make('payload')
                                    ->label('Məlumatlar (JSON Formatında)')
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
                    ->label('İstifadəçi / Bot')
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
                    ->label('Hadisə')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'user_login' => '🔐 Giriş (Login)',
                        'user_logout' => '🚪 Çıxış (Logout)',
                        'auth_failed' => '⚠️ Giriş Xətası',
                        'user_registered' => '✨ Yeni Qeydiyyat',
                        'password_reset' => '🔑 Şifrə Dəyişdirildi',
                        'model_created' => '➕ Yaradıldı',
                        'model_updated' => '✏️ Redaktə Edildi',
                        'model_deleted' => '🗑️ Silindi',
                        'search_filter' => '🔍 Axtarış / Filtr',
                        'property_view' => '🏠 Əmlaka Baxış',
                        'page_view' => '📄 Səhifə Ziyarəti',
                        'admin_view' => '🛠️ Admin Panel',
                        'admin_action' => '⚡ Admin Əməliyyat',
                        'agency_view' => '🏢 Agentlik Paneli',
                        'agency_action' => '📝 Agentlik Əməliyyat',
                        'form_submit' => '📨 Forma Göndərişi',
                        'server_error' => '🚨 500 Server Xətası',
                        'not_found_404' => '❓ 404 Tapılmadı',
                        'bot_visit' => '🤖 Axtarış Botu',
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
                    ->label('Məkan / Şəhər')
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
                    ->label('IP Ünvanı')
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
                    ->label('Hadisə Növü')
                    ->options([
                        'user_login' => '🔐 Girişlər (Logins)',
                        'auth_failed' => '⚠️ Uğursuz Giriş Cəhdləri',
                        'user_registered' => '✨ Yeni Qeydiyyatlar',
                        'model_created' => '➕ Məlumat Yaradılması',
                        'model_updated' => '✏️ Redaktə / Yenilənmə',
                        'model_deleted' => '🗑️ Silinmə Əməliyyatları',
                        'search_filter' => '🔍 Axtarış və Filtrlər',
                        'property_view' => '🏠 Əmlak Detal Baxışları',
                        'admin_action' => '⚡ Admin Əməliyyatları',
                        'server_error' => '🚨 Server Xətaları (500)',
                        'bot_visit' => '🤖 Bot Ziyarətləri',
                    ]),

                Tables\Filters\SelectFilter::make('device_type')
                    ->label('Cihaz Növü')
                    ->options([
                        'Desktop' => '💻 Kompüter (Desktop)',
                        'Mobile' => '📱 Mobil Telefon',
                        'Tablet' => '📟 Planşet',
                        'Bot' => '🤖 Axtarış Botu',
                    ]),

                Tables\Filters\SelectFilter::make('country_code')
                    ->label('Ölkə')
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
                    ->label('Xəritədə Bax')
                    ->icon('heroicon-o-map-pin')
                    ->color('warning')
                    ->modalHeading(fn (ActivityLog $record) => "📍 Məkan Xəritəsi: {$record->location_text}")
                    ->modalDescription(fn (ActivityLog $record) => "IP: {$record->ip_address} | Vaxt: {$record->created_at?->format('d.m.Y H:i:s')} | Hadisə: {$record->action}")
                    ->modalContent(fn (ActivityLog $record) => view('filament.components.activity-log-map', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Bağla'),

                Tables\Actions\ViewAction::make()
                    ->label('Detallar')
                    ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([]);
    }

    public static function getWidgets(): array
    {
        return [
            Pages\ActivityLogStatsWidget::class ?? \App\Filament\Admin\Resources\ActivityLogResource\Widgets\ActivityLogStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
