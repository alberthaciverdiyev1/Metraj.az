<?php

namespace App\Filament\Admin\Resources\PropertyResource\Pages;

use App\Filament\Admin\Resources\PropertyResource;
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
                Section::make(__('admin.basic_information'))
                    ->columns(3)
                    ->schema([
                        TextEntry::make('code')
                            ->label('Elan Kodu')
                            ->weight('bold'),

                        TextEntry::make('title')
                            ->label(__('admin.title'))
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
                            ->label(__('admin.price'))
                            ->money(fn ($record) => $record->currency ?? 'GBP')
                            ->weight('bold')
                            ->color('success'),

                        TextEntry::make('area')
                            ->label(__('admin.area'))
                            ->suffix(' m²'),

                        TextEntry::make('land_area')
                            ->label(__('admin.land_area'))
                            ->suffix(' sot'),

                        TextEntry::make('rooms')
                            ->label(__('admin.room_count')),

                        TextEntry::make('floor')
                            ->label(__('admin.floor')),

                        TextEntry::make('total_floors')
                            ->label(__('admin.building_floors')),

                        TextEntry::make('views_count')
                            ->label(__('admin.view_count')),
                    ]),

                Section::make(__('admin.description_and_address'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('description')
                            ->label(__('admin.description'))
                            ->html()
                            ->columnSpanFull(),

                        TextEntry::make('address')
                            ->label(__('admin.exact_address'))
                            ->icon('heroicon-o-map-pin'),

                        TextEntry::make('landmark')
                            ->label(__('admin.landmark'))
                            ->icon('heroicon-o-building-library'),

                        TextEntry::make('latitude')
                            ->label('Koordinat')
                            ->formatStateUsing(fn ($record) => $record->latitude && $record->longitude
                                ? number_format((float) $record->latitude, 6, '.', '') . ', ' . number_format((float) $record->longitude, 6, '.', '')
                                : '—'),
                    ]),

                Section::make(__('admin.features_and_amenities'))
                    ->columns(3)
                    ->schema([
                        IconEntry::make('has_document')
                            ->label(__('admin.deed_kupcha'))
                            ->boolean(),

                        IconEntry::make('has_mortgage')
                            ->label(__('admin.mortgage_available'))
                            ->boolean(),

                        IconEntry::make('has_internal_credit')
                            ->label('Daxili Kredit')
                            ->boolean(),

                        IconEntry::make('is_vip')
                            ->label('VIP Elan')
                            ->boolean(),

                        IconEntry::make('is_featured')
                            ->label(__('admin.featured_listing'))
                            ->boolean(),

                        TextEntry::make('amenities')
                            ->label(__('admin.amenities'))
                            ->getStateUsing(fn ($record) => $record->amenities->map(fn ($a) => $a->localized_name))
                            ->badge()
                            ->color('info'),
                    ]),

                Section::make(__('admin.dynamic_features_filters'))
                    ->schema([
                        TextEntry::make('filter_options')
                            ->label(__('admin.selected_filters'))
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

                Section::make('Sahiblik')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label(__('admin.user'))
                            ->icon('heroicon-o-user')
                            ->placeholder('—'),

                        TextEntry::make('agency.name')
                            ->label('Agentlik')
                            ->icon('heroicon-o-building-office-2')
                            ->placeholder('—'),

                        TextEntry::make('agent.user.name')
                            ->label('Rieltor')
                            ->icon('heroicon-o-user-group')
                            ->placeholder('—'),
                    ]),

                Section::make(__('admin.images'))
                    ->schema([
                        ImageEntry::make('images.url')
                            ->label('')
                            ->height(160)
                            ->extraImgAttributes(['class' => 'rounded-xl object-cover'])
                            ->defaultImageUrl('https://placehold.co/320x200?text=No+Image'),
                    ]),

                Section::make('Zamanlama')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('admin.created_at'))
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('updated_at')
                            ->label(__('admin.updated_at'))
                            ->dateTime('d.m.Y H:i'),
                    ]),
            ]);
    }
}
