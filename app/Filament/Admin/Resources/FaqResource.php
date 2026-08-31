<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Shared\Models\Faq;
use App\Filament\Admin\Resources\FaqResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Məzmun və Axtarış';

    protected static ?string $navigationLabel = 'Sual-Cavab (FAQ)';

    protected static ?string $modelLabel = 'Sual-Cavab';

    protected static ?string $pluralModelLabel = 'Sual-Cavablar (FAQ)';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Əsas Parametrlər')
                    ->schema([
                        Forms\Components\Select::make('category')
                            ->label('Kateqoriya')
                            ->options([
                                'general' => 'Ümumi Məlumat',
                                'listings' => 'İlanlar və Yerləşdirmə',
                                'payments' => 'Ödənişlər və VIP Xidmətlər',
                                'safety' => 'Təhlükəsizlik və Məxfilik',
                                'agency' => 'Əmlak Ofisləri və Agentlər',
                            ])
                            ->default('general')
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Saytda Aktivdir')
                            ->default(true),
                    ])->columns(3),

                Forms\Components\Section::make('Sual (Çoxdilli)')
                    ->description('Bütün dillər üçün sual mətnini daxil edin')
                    ->schema([
                        Forms\Components\TextInput::make('question.tr')
                            ->label('Soru (TR)')
                            ->placeholder('Örn: KıbrısKare\'de nasıl ilan verebilirim?')
                            ->required(),

                        Forms\Components\TextInput::make('question.az')
                            ->label('Sual (AZ)')
                            ->placeholder('Məs: KıbrısKare-də necə elan yerləşdirə bilərəm?')
                            ->required(),

                        Forms\Components\TextInput::make('question.en')
                            ->label('Question (EN)')
                            ->placeholder('E.g.: How can I post a property listing on KibrisKare?')
                            ->nullable(),

                        Forms\Components\TextInput::make('question.ru')
                            ->label('Вопрос (RU)')
                            ->placeholder('Напр.: Как разместить объявление на KibrisKare?')
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Cavab (Çoxdilli)')
                    ->description('Bütün dillər üçün cavab mətnini daxil edin')
                    ->schema([
                        Forms\Components\Textarea::make('answer.tr')
                            ->label('Cevap (TR)')
                            ->rows(4)
                            ->required(),

                        Forms\Components\Textarea::make('answer.az')
                            ->label('Cavab (AZ)')
                            ->rows(4)
                            ->required(),

                        Forms\Components\Textarea::make('answer.en')
                            ->label('Answer (EN)')
                            ->rows(4)
                            ->nullable(),

                        Forms\Components\Textarea::make('answer.ru')
                            ->label('Ответ (RU)')
                            ->rows(4)
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('question.tr')
                    ->label('Soru / Sual')
                    ->searchable(query: function ($query, string $search) {
                        return $query->where('question->tr', 'ilike', "%{$search}%")
                                     ->orWhere('question->az', 'ilike', "%{$search}%");
                    })
                    ->limit(60),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kateqoriya')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'general' => 'Ümumi',
                        'listings' => 'İlanlar',
                        'payments' => 'Ödənişlər',
                        'safety' => 'Təhlükəsizlik',
                        'agency' => 'Agentliklər',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'general',
                        'success' => 'listings',
                        'warning' => 'payments',
                        'danger' => 'safety',
                        'info' => 'agency',
                    ]),

                Tables\Columns\TextInputColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktiv'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Yeniləndi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kateqoriya')
                    ->options([
                        'general' => 'Ümumi',
                        'listings' => 'İlanlar',
                        'payments' => 'Ödənişlər',
                        'safety' => 'Təhlükəsizlik',
                        'agency' => 'Agentliklər',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktivlik'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
