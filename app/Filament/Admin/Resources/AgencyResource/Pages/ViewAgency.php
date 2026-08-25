<?php

namespace App\Filament\Admin\Resources\AgencyResource\Pages;

use App\Modules\Agency\Enums\AgencyStatus;
use App\Filament\Admin\Resources\AgencyResource;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewAgency extends ViewRecord
{
    protected static string $resource = AgencyResource::class;

    protected static string $view = 'filament-panels::resources.pages.view-record';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Loqo və Banner')
                    ->columns(2)
                    ->schema([
                        ImageEntry::make('logo')
                            ->label('Loqo')
                            ->circular()
                            ->height(96)
                            ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode(substr($record->name ?? 'A', 0, 1)) . '&background=F97316&color=fff&size=96')
                            ->extraImgAttributes(['class' => 'rounded-2xl']),

                        ImageEntry::make('banner')
                            ->label('Banner')
                            ->height(160)
                            ->defaultImageUrl('https://placehold.co/800x160?text=Banner+yoxdur')
                            ->extraImgAttributes(['class' => 'rounded-xl object-cover w-full']),
                    ]),

                Section::make('Agentlik Məlumatları')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Agentliyin Adı')
                            ->weight('bold')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->columnSpanFull(),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (AgencyStatus $state): string => match ($state) {
                                AgencyStatus::Pending => 'warning',
                                AgencyStatus::Active => 'success',
                                AgencyStatus::Suspended => 'danger',
                                AgencyStatus::Inactive => 'gray',
                            })
                            ->formatStateUsing(fn (AgencyStatus $state): string => $state->label()),

                        IconEntry::make('is_verified')
                            ->label('Rəsmi Partnyor (Təsdiqlənib)')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle'),

                        TextEntry::make('owner.name')
                            ->label('Rəhbər / Sahibi')
                            ->icon('heroicon-o-user')
                            ->placeholder('—'),

                        TextEntry::make('slug')
                            ->label('URL (Slug)')
                            ->copyable()
                            ->copyableState(fn ($state): string => url('/agentlik/' . $state))
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),

                Section::make('Haqqında')
                    ->schema([
                        TextEntry::make('description')
                            ->label('')
                            ->html()
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),

                Section::make('Əlaqə və Ünvan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('phone')
                            ->label('Telefon')
                            ->icon('heroicon-o-phone')
                            ->placeholder('—')
                            ->copyable(),

                        TextEntry::make('whatsapp')
                            ->label('WhatsApp')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->color('success')
                            ->placeholder('—')
                            ->copyable(),

                        TextEntry::make('email')
                            ->label('Rəsmi E-poçt')
                            ->icon('heroicon-o-envelope')
                            ->placeholder('—')
                            ->copyable(),

                        TextEntry::make('website')
                            ->label('Vebsayt')
                            ->icon('heroicon-o-globe-alt')
                            ->placeholder('—')
                            ->formatStateUsing(fn (?string $state): ?string => $state ? preg_replace('#^https?://#', '', $state) : null)
                            ->url(fn (?string $state): ?string => $state ?: null)
                            ->openUrlInNewTab(),

                        TextEntry::make('address')
                            ->label('Ofis Ünvanı')
                            ->icon('heroicon-o-map-pin')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),

                Section::make('Statistika')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('agents_count')
                            ->label('Rieltor Sayı')
                            ->state(fn ($record): int => $record->agents()->count())
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-user-group'),

                        TextEntry::make('properties_count')
                            ->label('Elan Sayı')
                            ->state(fn ($record): int => $record->properties()->count())
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-home-modern'),

                        TextEntry::make('published_properties_count')
                            ->label('Aktiv (Dərc olunmuş) Elan')
                            ->state(fn ($record): int => $record->properties()->where('status', 'published')->count())
                            ->badge()
                            ->color('primary')
                            ->icon('heroicon-o-check-circle'),
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
