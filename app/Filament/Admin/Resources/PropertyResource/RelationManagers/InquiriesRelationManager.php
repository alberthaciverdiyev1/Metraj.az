<?php

namespace App\Filament\Admin\Resources\PropertyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InquiriesRelationManager extends RelationManager
{
    protected static string $relationship = 'inquiries';

    protected static ?string $title = 'Bu Elana Gələn Müraciətlər';

    protected static ?string $modelLabel = 'Müraciət';

    protected static ?string $pluralModelLabel = 'Müraciətlər';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Müştərinin Adı')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('Telefon')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('E-poçt')
                    ->email()
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Yeni',
                        'contacted' => 'Əlaqə saxlanılıb',
                        'in_progress' => 'Baxış təyin olunub',
                        'closed' => 'Bağlanıb',
                        'cancelled' => 'Ləğv edilib',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('message')
                    ->label('Müştərinin Mesajı')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Müştəri')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
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
                    ->options([
                        'new' => 'Yeni',
                        'contacted' => 'Əlaqə saxlanılıb',
                        'in_progress' => 'Baxış təyin olunub',
                        'closed' => 'Bağlanıb',
                        'cancelled' => 'Ləğv edilib',
                    ]),
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
            ]);
    }
}
