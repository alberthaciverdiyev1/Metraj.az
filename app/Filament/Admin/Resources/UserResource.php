<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Sistem & İstifadəçilər';

    protected static ?string $navigationLabel = 'İstifadəçilər';

    protected static ?string $modelLabel = 'İstifadəçi';

    protected static ?string $pluralModelLabel = 'İstifadəçilər';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('İstifadəçi Hesabı')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('E-poçt Ünvanı')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->label('Şifrə')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->helperText('Dəyişmək istəmirsinizsə boş buraxın.'),

                        Forms\Components\Toggle::make('email_verified_at')
                            ->label('E-poçt Təsdiqlənib')
                            ->formatStateUsing(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => $state ? now() : null),
                    ])->columns(2),

                Forms\Components\Section::make('Platforma Rolu')
                    ->description('İstifadəçinin agent/agentlik əlaqəsi ilə avtomatik müəyyən edilir.')
                    ->schema([
                        Forms\Components\Placeholder::make('role_summary')
                            ->label('Rol / Vəzifə')
                            ->content(fn (?User $record): string => $record ? match (true) {
                                $record->email === 'admin@metraj.az' => 'Admin (Super Administrator)',
                                $record->agent && $record->agent->agency_id !== null => 'Rieltor — ' . ($record->agent->agency?->name ?? 'Agentlik'),
                                $record->agent !== null => 'Müstəqil Rieltor',
                                $record->agencies()->exists() => 'Agentlik Sahibi',
                                default => 'Normal İstifadəçi',
                            } : '—'),

                        Forms\Components\Placeholder::make('agent_info')
                            ->label('Rieltor Profili')
                            ->content(fn (?User $record): string => $record?->agent
                                ? 'Vəzifə: ' . ($record->agent->position ?? '—') . ' | Telefon: ' . ($record->agent->phone ?? '—')
                                : 'Rieltor profili yoxdur.')
                            ->visible(fn (?User $record): bool => $record?->agent !== null),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-poçt')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tip')
                    ->getStateUsing(fn (User $record): string => match (true) {
                        $record->email === 'admin@metraj.az' => 'Admin',
                        $record->agent && $record->agent->agency_id !== null => 'Rieltor',
                        $record->agent !== null => 'Müstəqil Rieltor',
                        $record->agencies()->exists() => 'Agentlik Sahibi',
                        default => 'Normal',
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Admin' => 'danger',
                        'Agentlik Sahibi' => 'warning',
                        'Rieltor' => 'info',
                        'Müstəqil Rieltor' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('agent.agency.name')
                    ->label('Agentlik')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('properties_count')
                    ->counts('properties')
                    ->label('Elan Sayı')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('E-poçt Təsdiqi')
                    ->boolean()
                    ->falseIcon('heroicon-o-x-circle')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Qeydiyyat Tarixi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tip')
                    ->options([
                        'admin' => 'Admin',
                        'agency_owner' => 'Agentlik Sahibi',
                        'realtor' => 'Rieltor',
                        'independent' => 'Müstəqil Rieltor',
                        'normal' => 'Normal',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'];
                        if (blank($value)) return;

                        if ($value === 'admin') {
                            $query->where('email', 'admin@metraj.az');
                        } elseif ($value === 'agency_owner') {
                            $query->whereHas('agencies');
                        } elseif ($value === 'realtor') {
                            $query->whereHas('agent', fn ($q) => $q->whereNotNull('agency_id'));
                        } elseif ($value === 'independent') {
                            $query->whereHas('agent', fn ($q) => $q->whereNull('agency_id'));
                        } elseif ($value === 'normal') {
                            $query->where('email', '!=', 'admin@metraj.az')
                                ->whereDoesntHave('agent')
                                ->whereDoesntHave('agencies');
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
