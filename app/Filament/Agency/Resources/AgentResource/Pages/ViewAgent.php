<?php

namespace App\Filament\Agency\Resources\AgentResource\Pages;

use App\Filament\Agency\Resources\AgentResource;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewAgent extends ViewRecord
{
    protected static string $resource = AgentResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Rieltor Profili')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('avatar')
                            ->label('')
                            ->circular()
                            ->height(96)
                            ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user?->name ?? 'R') . '&background=F97316&color=fff&size=160')
                            ->columnSpan(1),

                        TextEntry::make('user.name')
                            ->label('Ad Soyad')
                            ->weight('bold')
                            ->size('lg')
                            ->columnSpan(2),

                        TextEntry::make('position')
                            ->label('Vəzifə')
                            ->placeholder('—'),

                        TextEntry::make('phone')
                            ->label('Telefon')
                            ->icon('heroicon-o-phone'),

                        TextEntry::make('whatsapp')
                            ->label('WhatsApp')
                            ->color('success')
                            ->icon('heroicon-o-chat-bubble-left-right'),

                        IconEntry::make('is_active')
                            ->label('Aktiv')
                            ->boolean(),

                        TextEntry::make('properties_count')
                            ->label('Elan Sayı')
                            ->badge()
                            ->color('success'),
                    ]),
            ]);
    }
}
