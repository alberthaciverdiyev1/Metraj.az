<?php

namespace App\Filament\Admin\Resources\LocationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DistrictsRelationManager extends RelationManager
{
    protected static string $relationship = 'districts';

    protected static ?string $title = 'Bu Şəhərə Aid Rayonlar / Bölqələr';

    protected static ?string $modelLabel = 'Rayon / Bölqə';

    protected static ?string $pluralModelLabel = 'Rayonlar və Bölqələr';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name.az')
                    ->label('Rayon / Bölqə Adı (AZ)')
                    ->placeholder('Məs: Yasamal, Alsancak, Lapta')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        if (filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),

                Forms\Components\TextInput::make('name.tr')
                    ->label('Rayon / Bölqə Adı (TR)')
                    ->placeholder('Məs: Alsancak, Lapta')
                    ->nullable(),

                Forms\Components\TextInput::make('name.en')
                    ->label('Rayon / Bölqə Adı (EN)')
                    ->placeholder('Məs: Alsancak')
                    ->nullable(),

                Forms\Components\TextInput::make('name.ru')
                    ->label('Rayon / Bölqə Adı (RU)')
                    ->placeholder('Məs: Алсанджак')
                    ->nullable(),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug / Dəyər')
                    ->placeholder('yasamal, lapta')
                    ->required(),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Sıralama')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktivdir')
                    ->default(true),
            ])->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->recordTitleAttribute('name.az')
            ->columns([
                Tables\Columns\TextColumn::make('name.az')
                    ->label('Rayon / Bölqə (AZ)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name.tr')
                    ->label('Ad (TR)')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('name.en')
                    ->label('Ad (EN)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('name.ru')
                    ->label('Ad (RU)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Yeni Rayon / Bölqə Əlavə Et'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
