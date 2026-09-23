<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Modules\Shared\Models\User;
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

    public static function getNavigationGroup(): ?string {
        return __('admin.users_and_agencies'); }

    public static function getNavigationLabel(): string {
        return __('admin.users'); }

    public static function getModelLabel(): string {
        return __('admin.user'); }

    public static function getPluralModelLabel(): string {
        return __('admin.users'); }

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.user_account'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label(__('admin.email_address'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->label(__('admin.password'))
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->helperText(__('admin.leave_blank_to_keep')),

                        Forms\Components\Toggle::make('email_verified_at')
                            ->label(__('admin.email_verified'))
                            ->formatStateUsing(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => $state ? now() : null),
                    ])->columns(2),

                Forms\Components\Section::make('Platforma Rolu')
                    ->description(__('admin.auto_determined_by_agent_hint'))
                    ->schema([
                        Forms\Components\Placeholder::make('role_summary')
                            ->label(__('admin.role_position'))
                            ->content(fn (?User $record): string => $record ? match (true) {
                                $record->email === 'admin@kibriskare.com' => 'Admin (Super Administrator)',
                                $record->agent && $record->agent->agency_id !== null => 'Rieltor — ' . ($record->agent->agency?->name ?? 'Agentlik'),
                                $record->agent !== null => __('admin.independent_realtor'),
                                $record->agencies()->exists() => 'Agentlik Sahibi',
                                default => __('admin.normal_user'),
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
                    ->label(__('admin.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tip')
                    ->getStateUsing(fn (User $record): string => match (true) {
                        $record->email === 'admin@kibriskare.com' => 'Admin',
                        $record->agent && $record->agent->agency_id !== null => 'Rieltor',
                        $record->agent !== null => __('admin.independent_realtor'),
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
                    ->label(__('admin.listing_count'))
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label(__('admin.email_verification'))
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
                        'independent' => __('admin.independent_realtor'),
                        'normal' => 'Normal',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'];
                        if (blank($value)) return;

                        if ($value === 'admin') {
                            $query->where('email', 'admin@kibriskare.com');
                        } elseif ($value === 'agency_owner') {
                            $query->whereHas('agencies');
                        } elseif ($value === 'realtor') {
                            $query->whereHas('agent', fn ($q) => $q->whereNotNull('agency_id'));
                        } elseif ($value === 'independent') {
                            $query->whereHas('agent', fn ($q) => $q->whereNull('agency_id'));
                        } elseif ($value === 'normal') {
                            $query->where('email', '!=', 'admin@kibriskare.com')
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
