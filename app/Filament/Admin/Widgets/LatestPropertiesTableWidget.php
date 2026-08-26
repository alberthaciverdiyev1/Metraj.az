<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\PropertyResource;
use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Models\Property;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPropertiesTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Son Əlavə Olunan Əmlaklar';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Property::query()->with(['city', 'district'])->latest('id')->limit(6)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('first_image_url')
                    ->label('Şəkil')
                    ->circular(false)
                    ->square()
                    ->defaultImageUrl(asset('images/no-photo.svg')),

                Tables\Columns\TextColumn::make('title')
                    ->label('Başlıq')
                    ->limit(40)
                    ->description(fn (Property $record): string => $record->code ?: '')
                    ->searchable(),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('Şəhər / Rayon')
                    ->formatStateUsing(function ($state, Property $record) {
                        $cityName = is_array($state) ? ($state['tr'] ?? $state['az'] ?? reset($state)) : $state;
                        $district = $record->district;
                        $districtName = $district ? (is_array($district->name) ? ($district->name['tr'] ?? $district->name['az'] ?? reset($district->name)) : $district->name) : null;
                        return $districtName ? "{$cityName}, {$districtName}" : ($cityName ?: '—');
                    })
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Qiymət')
                    ->formatStateUsing(fn (Property $record): string => number_format((float) $record->price, 0, '.', ' ') . ' ' . $record->currency)
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (PropertyStatus $state): string => match ($state) {
                        PropertyStatus::Published => 'success',
                        PropertyStatus::PendingApproval => 'warning',
                        PropertyStatus::Rejected => 'danger',
                        PropertyStatus::Sold, PropertyStatus::Rented => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (PropertyStatus $state): string => $state->label()),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Baxış')
                    ->icon('heroicon-m-eye')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarix')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Redaktə')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Property $record): string => PropertyResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
