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

    protected static ?string $title = 'Filtr Seçimləri və Subfiltrlər';

    protected static ?string $modelLabel = 'Seçim';

    protected static ?string $pluralModelLabel = 'Seçimlər';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parent_id')
                    ->label('Üst Seçim (Parent - Alt-filtr üçün)')
                    ->placeholder('Ana Seçim (Root - Ən üst səviyyə)')
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
                    ->label('Dəyər (Slug / Value)')
                    ->placeholder('Məs: yasamal, new_building')
                    ->required(),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Sıralama')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\Section::make('Seçim Adı (Çoxdilli)')
                    ->schema([
                        Forms\Components\TextInput::make('name.az')
                            ->label('Ad (AZ)')
                            ->required(),

                        Forms\Components\TextInput::make('name.ru')
                            ->label('Ad (RU)')
                            ->nullable(),

                        Forms\Components\TextInput::make('name.en')
                            ->label('Ad (EN)')
                            ->nullable(),
                    ])->columns(3),

                Forms\Components\TextInput::make('icon')
                    ->label('İkon (FontAwesome)')
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
                    ->label('Seçim / İyerarxiya')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('name->az', 'like', "%{$search}%")
                            ->orWhere('value', 'like', "%{$search}%");
                    })
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('value')
                    ->label('Dəyər (Value)')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name.az')
                    ->label('Üst Seçim (Parent)')
                    ->placeholder('Əsas Seçim (Root)')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
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
