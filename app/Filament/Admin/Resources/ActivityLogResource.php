<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Shared\Models\ActivityLog;
use App\Filament\Admin\Resources\ActivityLogResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    protected static ?string $navigationLabel = 'Aktivlik Qeydləri';

    protected static ?string $modelLabel = 'Aktivlik Qeydi';

    protected static ?string $pluralModelLabel = 'Aktivlik Qeydləri';

    protected static ?int $navigationSort = 7;

    public static function canCreate(): bool
    {
        return false; // Logs are read-only
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Qeyd Detalları')
                    ->schema([
                        Forms\Components\Placeholder::make('user_name')
                            ->label('İstifadəçi')
                            ->content(fn ($record) => $record?->user?->name ?? 'Qonaq (Qeydiyyatsız)'),

                        Forms\Components\TextInput::make('action')
                            ->label('Hərəkət / Hadisə')
                            ->disabled(),

                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Ünvanı')
                            ->disabled(),

                        Forms\Components\TextInput::make('method')
                            ->label('HTTP Metod')
                            ->disabled(),

                        Forms\Components\TextInput::make('url')
                            ->label('Tam URL')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('user_agent')
                            ->label('Brauzer (User Agent)')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Hadisə Tarixi')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Dəyişiklik / İstək Göndərişi (Payload)')
                    ->schema([
                        Forms\Components\Textarea::make('payload')
                            ->label('Məlumatlar (JSON)')
                            ->disabled()
                            ->rows(12)
                            ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (ActivityLog $record) => !empty($record->payload)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->label('İstifadəçi / Rol / Bot')
                    ->formatStateUsing(function ($state, $record) {
                        $payload = $record->payload;
                        if (!empty($payload['bot_name'])) {
                            return '🤖 ' . $payload['bot_name'] . ' (Axtarış Botu)';
                        }
                        
                        $name = $record->user?->name ?? 'Qonaq';
                        $role = $payload['user_role'] ?? 'Ziyarətçi';
                        
                        return $name . ' (' . $role . ')';
                    })
                    ->weight('bold')
                    ->description(fn ($record) => $record->user_agent ? \Illuminate\Support\Str::limit($record->user_agent, 45) : null),

                Tables\Columns\TextColumn::make('action')
                    ->label('Hərəkət / Hadisə')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'login' => 'success',
                        'logout' => 'gray',
                        'failed_login' => 'danger',
                        'create_model' => 'info',
                        'update_model' => 'warning',
                        'delete_model' => 'danger',
                        'bot_visit' => 'gray',
                        'admin_view' => 'warning',
                        'admin_action' => 'danger',
                        'form_submit' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'login' => 'Sistemə Giriş',
                        'logout' => 'Sistemdən Çıxış',
                        'failed_login' => 'Uğursuz Giriş',
                        'create_model' => 'Yeni Məlumat (Create)',
                        'update_model' => 'Məlumat Dəyişimi (Update)',
                        'delete_model' => 'Məlumat Silinməsi (Delete)',
                        'page_view' => 'Ziyarət',
                        'bot_visit' => 'Bot Ziyarəti 🤖',
                        'admin_view' => 'Admin Panel Səhifəsi',
                        'admin_action' => 'Admin Paneldə Əməliyyat',
                        'form_submit' => 'Form Göndərişi',
                        'data_update' => 'Məlumat Dəyişimi',
                        'data_delete' => 'Məlumat Silinməsi',
                        default => $state,
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('payload.status_code')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state >= 200 && $state < 300 => 'success',
                        $state >= 300 && $state < 400 => 'warning',
                        $state >= 400 => 'danger',
                        default => 'gray',
                    })
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('method')
                    ->label('Metod')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GET' => 'gray',
                        'POST' => 'success',
                        'PUT', 'PATCH' => 'warning',
                        'DELETE' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('İstifadəçi')
                    ->searchable()
                    ->options(fn () => \App\Modules\Shared\Models\User::pluck('name', 'id')->toArray()),

                Tables\Filters\SelectFilter::make('action')
                    ->label('Hərəkət növü')
                    ->options([
                        'login' => 'Giriş etdi',
                        'logout' => 'Çıxış etdi',
                        'failed_login' => 'Uğursuz Giriş',
                        'create_model' => 'Yeni Məlumat',
                        'update_model' => 'Yeniləndi',
                        'delete_model' => 'Silindi',
                        'page_view' => 'Səhifə Ziyarəti',
                        'bot_visit' => 'Bot Ziyarəti',
                        'admin_view' => 'Admin Panel Ziyarəti',
                        'admin_action' => 'Admin Panel Əməliyyatı',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
