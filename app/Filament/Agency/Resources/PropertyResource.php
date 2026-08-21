<?php

namespace App\Filament\Agency\Resources;

use App\Core\Domain\Property\Enums\PropertyStatus;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Property;
use App\Filament\Agency\Resources\PropertyResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Elanlarım';

    protected static ?string $modelLabel = 'Əmlak Elanı';

    protected static ?string $pluralModelLabel = 'Agentlik Elanları';

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $tenantAgency = $user?->tenantAgency();

        return parent::getEloquentQuery()
            ->when(
                $user?->isTenantOwner() && $tenantAgency,
                fn (Builder $query) => $query->where('agency_id', $tenantAgency->id),
                fn (Builder $query) => $query->where('user_id', $user->id),
            );
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(\App\Filament\Admin\Resources\PropertyResource::getFormSchema(false))
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        $isGrid = session('properties_table_layout', 'table') === 'grid';

        if ($isGrid) {
            return $table
                ->defaultSort('id', 'desc')
                ->contentGrid([
                    'sm' => 1,
                    'md' => 2,
                    'lg' => 3,
                    'xl' => 4,
                    '2xl' => 4,
                ])
                ->columns([
                    Tables\Columns\Layout\View::make('filament.tables.components.property-grid-card'),
                ])
                ->filters([
                    Tables\Filters\SelectFilter::make('status')
                        ->label('Status')
                        ->options(collect(PropertyStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])),
                ])
                ->actions([
                    Tables\Actions\ViewAction::make()
                        ->url(fn (\Illuminate\Database\Eloquent\Model $record): string => static::getUrl('view', ['record' => $record])),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->bulkActions([]);
        }

        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('first_image_url')
                    ->label('Şəkil')
                    ->state(fn (Property $record) => $record->first_image_url)
                    ->extraImgAttributes([
                        'class' => 'w-12 h-12 object-cover rounded-lg shadow-sm',
                    ])
                    ->size(48)
                    ->square(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Başlıq')
                    ->limit(35)
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Qiymət')
                    ->formatStateUsing(fn ($record) => ($record->currency === 'GBP' || empty($record->currency) ? '£ ' : $record->currency . ' ') . number_format($record->price, 0, '.', ' '))
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Baxış Sayı')
                    ->icon('heroicon-o-eye')
                    ->numeric()
                    ->default(0)
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('inquiries_count')
                    ->counts('inquiries')
                    ->label('Müraciət')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (PropertyStatus $state): string => match ($state) {
                        PropertyStatus::Draft => 'gray',
                        PropertyStatus::PendingApproval => 'warning',
                        PropertyStatus::Published => 'success',
                        PropertyStatus::Rejected => 'danger',
                        PropertyStatus::Sold => 'info',
                        PropertyStatus::Rented => 'info',
                        PropertyStatus::Archived => 'gray',
                    })
                    ->formatStateUsing(fn (PropertyStatus $state): string => $state->label()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(PropertyStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (\Illuminate\Database\Eloquent\Model $record): string => static::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Admin\Resources\PropertyResource\RelationManagers\InquiriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'view' => Pages\ViewProperty::route('/{record}'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
