<?php

namespace App\Filament\Agency\Resources\PropertyResource\Pages;

use App\Filament\Agency\Resources\PropertyResource;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewProperty extends ViewRecord
{
    protected static string $resource = PropertyResource::class;

    protected static string $view = 'filament-panels::resources.pages.view-record';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Əsas Məlumatlar')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('code')
                            ->label('Elan Kodu')
                            ->weight('bold'),

                        TextEntry::make('title')
                            ->label('Başlıq')
                            ->weight('bold')
                            ->columnSpanFull(),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state) => match ($state->value) {
                                'draft' => 'gray',
                                'pending_approval' => 'warning',
                                'published' => 'success',
                                'rejected' => 'danger',
                                'sold' => 'info',
                                'rented' => 'info',
                                'archived' => 'gray',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => $state->label()),

                        TextEntry::make('price')
                            ->label('Qiymət')
                            ->money('AZN')
                            ->weight('bold')
                            ->color('success'),

                        TextEntry::make('area')
                            ->label('Sahə')
                            ->suffix(' m²'),

                        TextEntry::make('land_area')
                            ->label('Torpaq Sahəsi')
                            ->suffix(' sot'),

                        TextEntry::make('rooms')
                            ->label('Otaq Sayı'),

                        TextEntry::make('floor')
                            ->label('Mərtəbə'),

                        TextEntry::make('total_floors')
                            ->label('Bina Mərtəbəsi'),

                        TextEntry::make('views_count')
                            ->label('Baxış Sayı'),
                    ]),

                Section::make('Təsvir və Ünvan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('description')
                            ->label('Təsvir')
                            ->html()
                            ->columnSpanFull(),

                        TextEntry::make('address')
                            ->label('Dəqiq Ünvan')
                            ->icon('heroicon-o-map-pin'),

                        TextEntry::make('landmark')
                            ->label('Nişangah')
                            ->icon('heroicon-o-building-library'),
                    ]),

                Section::make('Xüsusiyyətlər & Təchizatlar')
                    ->columns(3)
                    ->schema([
                        IconEntry::make('has_document')
                            ->label('Çıxarış (Kupça)')
                            ->boolean(),

                        IconEntry::make('has_mortgage')
                            ->label('İpotekaya Yararlı')
                            ->boolean(),

                        IconEntry::make('has_internal_credit')
                            ->label('Daxili Kredit')
                            ->boolean(),

                        IconEntry::make('is_vip')
                            ->label('VIP Elan')
                            ->boolean(),

                        IconEntry::make('is_featured')
                            ->label('Seçilmiş Elan')
                            ->boolean(),

                        TextEntry::make('amenities.name')
                            ->label('Təchizatlar')
                            ->badge()
                            ->color('info'),
                    ]),

                Section::make('Dinamik Xüsusiyyətlər (Filtrlər)')
                    ->schema([
                        TextEntry::make('filter_options')
                            ->label('Seçilmiş filtrlər')
                            ->getStateUsing(function ($record): string {
                                if (! $record->filterOptions->count()) {
                                    return '—';
                                }

                                return $record->filterOptions
                                    ->groupBy(fn ($option) => $option->filter_id)
                                    ->map(function ($group) {
                                        // Hər filtr üçün ən dərin (ən dəqiq) seçimi göstəririk
                                        $option = $group
                                            ->sortByDesc(fn ($opt) => substr_count((string) $opt->hierarchical_name, '➔'))
                                            ->first();

                                        $filterName = $option->filter?->name['az']
                                            ?? (is_object($option->filter?->key) ? $option->filter?->key->value : $option->filter?->key)
                                            ?? '';

                                        return $filterName ? "{$filterName}: {$option->hierarchical_name}" : $option->hierarchical_name;
                                    })
                                    ->join(', ');
                            })
                            ->badge()
                            ->color('warning'),
                    ]),

                Section::make('Şəkillər')
                    ->schema([
                        ImageEntry::make('images.url')
                            ->label('')
                            ->height(160)
                            ->extraImgAttributes(['class' => 'rounded-xl object-cover'])
                            ->defaultImageUrl('https://placehold.co/320x200?text=No+Image'),
                    ]),

                Section::make('Zamanlama')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Yaradılma Tarixi')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Yenilənmə Tarixi')
                            ->dateTime('d.m.Y H:i'),
                    ]),
            ]);
    }
}
