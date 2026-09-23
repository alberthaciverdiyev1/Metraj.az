<?php

namespace App\Filament\Admin\Resources\FilterResource\RelationManagers;

use App\Modules\Location\Models\FilterOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FilterOptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string {
        return __('admin.filter_options_subfilters'); }

    protected static function getModelLabel(): ?string {
        return __('admin.option'); }

    protected static function getPluralModelLabel(): ?string {
        return __('admin.options'); }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parent_id')
                    ->label(__('admin.parent_selection'))
                    ->placeholder(__('admin.root_selection'))
                    ->options(function ($livewire, ?FilterOption $record) {
                        $filterId = $livewire->ownerRecord->id;
                        if (!$filterId) {
                            return [];
                        }
                        return FilterOption::where('filter_id', $filterId)
                            ->when($record?->id, fn ($q) => $q->where('id', '!=', $record->id))
                            ->get()
                            ->mapWithKeys(fn ($opt) => [$opt->id => $opt->hierarchical_name])
                            ->toArray();
                    })
                    ->searchable()
                    ->nullable()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('value')
                    ->label(__('admin.value_slug'))
                    ->placeholder(__('admin.example_slug_values'))
                    ->required(),

                Forms\Components\TextInput::make('sort_order')
                    ->label(__('admin.sort_order'))
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\Section::make(__('admin.selection_name_multilingual'))
                    ->schema([
                        Forms\Components\TextInput::make('name.az')
                            ->label('Ad (AZ)')
                            ->required(),

                        Forms\Components\TextInput::make('name.tr')
                            ->label('Ad (TR)')
                            ->nullable(),

                        Forms\Components\TextInput::make('name.en')
                            ->label('Ad (EN)')
                            ->nullable(),

                        Forms\Components\TextInput::make('name.ru')
                            ->label('Ad (RU)')
                            ->nullable(),
                    ])->columns(4),

                Forms\Components\TextInput::make('icon')
                    ->label(__('admin.icon_fontawesome'))
                    ->placeholder('fa-map-pin')
                    ->nullable(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktivdir')
                    ->default(true)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->recordTitleAttribute('value')
            ->columns([
                Tables\Columns\TextColumn::make('hierarchical_name')
                    ->label(__('admin.selection_hierarchy'))
                    ->searchable(query: function ($query, $search) {
                        return $query->where('name->az', 'like', "%{$search}%")
                            ->orWhere('name->tr', 'like', "%{$search}%")
                            ->orWhere('value', 'like', "%{$search}%");
                    })
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name.tr')
                    ->label('Ad (TR)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('value')
                    ->label(__('admin.value'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name.az')
                    ->label(__('admin.parent'))
                    ->placeholder(__('admin.root_selection_short'))
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('admin.sort_order_short'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktivlik'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order');
    }
}
