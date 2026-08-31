<?php

namespace App\Filament\Agency\Widgets;

use App\Filament\Agency\Resources\PropertyResource;
use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Models\Property;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class AgencyLatestPropertiesTableWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return app()->getLocale() === 'tr'
            ? 'Son Eklenen İlanlarım'
            : (app()->getLocale() === 'az' ? 'Son Əlavə Edilən Elanlarım' : 'Recent Listings');
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $tenantAgency = $user?->tenantAgency();
        $isOwner = $user?->isTenantOwner() && $tenantAgency;

        $query = Property::query();
        if ($isOwner) {
            $query->where('agency_id', $tenantAgency->id);
        } else {
            $query->where('user_id', $user?->id);
        }

        return $table
            ->query($query->latest('id')->limit(5))
            ->columns([
                Tables\Columns\ImageColumn::make('first_image_url')
                    ->label(app()->getLocale() === 'tr' ? 'Fotoğraf' : (app()->getLocale() === 'az' ? 'Şəkil' : 'Image'))
                    ->state(fn (Property $record) => $record->first_image_url)
                    ->size(44)
                    ->square(),

                Tables\Columns\TextColumn::make('code')
                    ->label(app()->getLocale() === 'tr' ? 'Kod' : (app()->getLocale() === 'az' ? 'Kod' : 'Code'))
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('title')
                    ->label(app()->getLocale() === 'tr' ? 'Başlık' : (app()->getLocale() === 'az' ? 'Başlıq' : 'Title'))
                    ->limit(35),

                Tables\Columns\TextColumn::make('price')
                    ->label(app()->getLocale() === 'tr' ? 'Fiyat' : (app()->getLocale() === 'az' ? 'Qiymət' : 'Price'))
                    ->formatStateUsing(fn ($record) => ($record->currency === 'GBP' || empty($record->currency) ? '£ ' : $record->currency . ' ') . number_format($record->price, 0, '.', ' '))
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('views_count')
                    ->label(app()->getLocale() === 'tr' ? 'Görüntülenme' : (app()->getLocale() === 'az' ? 'Baxış' : 'Views'))
                    ->icon('heroicon-o-eye')
                    ->badge()
                    ->color('gray'),

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
                    ->label(app()->getLocale() === 'tr' ? 'Tarih' : (app()->getLocale() === 'az' ? 'Tarix' : 'Date'))
                    ->dateTime('d.m.Y H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(app()->getLocale() === 'tr' ? 'Düzenle' : (app()->getLocale() === 'az' ? 'Düzəliş et' : 'Edit'))
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Property $record): string => PropertyResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
