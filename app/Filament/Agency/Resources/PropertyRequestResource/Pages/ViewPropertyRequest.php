<?php

namespace App\Filament\Agency\Resources\PropertyRequestResource\Pages;

use App\Filament\Agency\Resources\PropertyRequestResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewPropertyRequest extends ViewRecord
{
    protected static string $resource = PropertyRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('call')
                ->label('Zəng et')
                ->icon('heroicon-o-phone')
                ->color('warning')
                ->url(fn ($record) => $record->contact_phone ? 'tel:' . preg_replace('/[^0-9+]/', '', $record->contact_phone) : null)
                ->visible(fn ($record) => ! empty($record->contact_phone)),

            Action::make('whatsapp')
                ->label('WhatsApp ilə Əlaqə')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->color('success')
                ->url(fn ($record) => ! empty($record->contact_whatsapp ?? $record->contact_phone)
                    ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->contact_whatsapp ?? $record->contact_phone) . '?text=' . urlencode('Salam ' . $record->contact_name . ', KibrisKare-dəki "' . $record->title . '" tələbinizlə bağlı əlaqə saxlayıram.')
                    : null
                )
                ->openUrlInNewTab()
                ->visible(fn ($record) => ! empty($record->contact_whatsapp ?? $record->contact_phone)),
        ];
    }
}
