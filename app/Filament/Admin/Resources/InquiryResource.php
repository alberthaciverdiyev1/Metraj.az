<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Inquiry\Models\Inquiry;
use App\Filament\Admin\Resources\InquiryResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Əmlak və Müraciətlər';

    protected static ?string $navigationLabel = 'Müştəri Müraciətləri';

    protected static ?string $modelLabel = 'Müraciət';

    protected static ?string $pluralModelLabel = 'Müştəri Müraciətləri';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereIn('status', ['new', 'yeni'])->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Müraciət Məlumatları')
                    ->schema([
                        Forms\Components\Select::make('property_id')
                            ->label('Aid Olduğu Əmlak')
                            ->relationship('property', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'new' => 'Yeni',
                                'contacted' => 'Əlaqə saxlanılıb',
                                'in_progress' => 'Baxış təyin olunub',
                                'closed' => 'Bağlanıb (Uğurlu)',
                                'cancelled' => 'Ləğv edilib',
                            ])
                            ->default('new')
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->label('Müştərinin Adı')
                            ->required(),

                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon Nömrəsi')
                            ->tel()
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->label('E-poçt')
                            ->email(),

                        Forms\Components\Textarea::make('message')
                            ->label('Müştərinin Mesajı')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Müştəri')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('property.code')
                    ->label('Elan Kodu')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('property.title')
                    ->label('Əmlak')
                    ->limit(25)
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        'in_progress' => 'primary',
                        'closed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Yeni',
                        'contacted' => 'Əlaqə saxlanılıb',
                        'in_progress' => 'Baxış təyin olunub',
                        'closed' => 'Bağlanıb',
                        'cancelled' => 'Ləğv edilib',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Yeni',
                        'contacted' => 'Əlaqə saxlanılıb',
                        'in_progress' => 'Baxış təyin olunub',
                        'closed' => 'Bağlanıb',
                        'cancelled' => 'Ləğv edilib',
                    ]),
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
            'index' => Pages\ListInquiries::route('/'),
            'create' => Pages\CreateInquiry::route('/create'),
            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }
}
