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

    public static function getNavigationGroup(): ?string {
        return __('admin.content_and_search'); }

    protected static ?string $navigationLabel = 'Sual-Cavab (FAQ)';

    protected static ?string $modelLabel = 'Sual-Cavab';

    protected static ?string $pluralModelLabel = 'Sual-Cavablar (FAQ)';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.main_parameters'))
                    ->schema([
                        Forms\Components\Select::make('category')
                            ->label('Kateqoriya')
                            ->options([
                                'general' => __('admin.general_information'),
                                'listings' => __('admin.listings_and_placement'),
                                'payments' => __('admin.payments_and_vip_services'),
                                'safety' => __('admin.security_and_privacy'),
                                'agency' => __('admin.real_estate_offices_and_agents'),
                            ])
                            ->default('general')
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('admin.sort_order'))
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Saytda Aktivdir')
                            ->default(true),
                    ])->columns(3),

                Forms\Components\Section::make(__('admin.question_multilingual'))
                    ->description(__('admin.enter_question_all_languages'))
                    ->schema([
                        Forms\Components\TextInput::make('question.tr')
                            ->label('Soru (TR)')
                            ->placeholder(__('admin.example_how_to_post_tr'))
                            ->required(),

                        Forms\Components\TextInput::make('question.az')
                            ->label('Sual (AZ)')
                            ->placeholder(__('admin.example_how_to_post'))
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

                Forms\Components\Section::make(__('admin.answer_multilingual'))
                    ->description(__('admin.enter_answer_all_languages'))
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
                        'general' => __('admin.general'),
                        'listings' => __('admin.listings'),
                        'payments' => __('admin.payments'),
                        'safety' => __('admin.security'),
                        'agency' => __('admin.agencies'),
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
                    ->label(__('admin.sort_order_short'))
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktiv'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('admin.updated'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kateqoriya')
                    ->options([
                        'general' => __('admin.general'),
                        'listings' => __('admin.listings'),
                        'payments' => __('admin.payments'),
                        'safety' => __('admin.security'),
                        'agency' => __('admin.agencies'),
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
