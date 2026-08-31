<?php

namespace App\Filament\Agency\Widgets;

use App\Filament\Agency\Resources\PropertyResource;
use App\Modules\Inquiry\Models\Inquiry;
use App\Modules\Property\Models\Property;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class AgencyLatestInquiriesTableWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'xl' => 2,
    ];

    public function getHeading(): ?string
    {
        return app()->getLocale() === 'tr'
            ? 'Son Müşteri Talepleri ve Mesajları'
            : (app()->getLocale() === 'az' ? 'Son Müştəri Müraciətləri' : 'Recent Client Inquiries');
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $tenantAgency = $user?->tenantAgency();
        $isOwner = $user?->isTenantOwner() && $tenantAgency;

        $propertyQuery = Property::query();
        if ($isOwner) {
            $propertyQuery->where('agency_id', $tenantAgency->id);
        } else {
            $propertyQuery->where('user_id', $user?->id);
        }

        $propertyIds = $propertyQuery->pluck('id');

        return $table
            ->query(
                Inquiry::query()
                    ->with(['property'])
                    ->where(function ($q) use ($propertyIds, $isOwner, $tenantAgency, $user) {
                        if ($propertyIds->isNotEmpty()) {
                            $q->whereIn('property_id', $propertyIds);
                        }
                        if ($isOwner && $tenantAgency) {
                            $q->orWhere('agency_id', $tenantAgency->id);
                        } else {
                            $q->orWhere('agent_id', $user?->agent?->id);
                        }
                    })
                    ->latest('id')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(app()->getLocale() === 'tr' ? 'Müşteri' : (app()->getLocale() === 'az' ? 'Müştəri' : 'Client'))
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('phone')
                    ->label(app()->getLocale() === 'tr' ? 'Telefon' : (app()->getLocale() === 'az' ? 'Telefon' : 'Phone'))
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('property.title')
                    ->label(app()->getLocale() === 'tr' ? 'İlgili İlan' : (app()->getLocale() === 'az' ? 'Əlaqəli Əmlak' : 'Property'))
                    ->limit(25)
                    ->placeholder(app()->getLocale() === 'tr' ? 'Genel Talep' : 'Ümumi Müraciət'),

                Tables\Columns\TextColumn::make('message')
                    ->label(app()->getLocale() === 'tr' ? 'Mesaj' : (app()->getLocale() === 'az' ? 'Mesaj' : 'Message'))
                    ->limit(35),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(app()->getLocale() === 'tr' ? 'Tarih' : (app()->getLocale() === 'az' ? 'Tarix' : 'Date'))
                    ->since(),
            ])
            ->emptyStateHeading(app()->getLocale() === 'tr' ? 'Henüz müşteri talebi bulunmuyor' : (app()->getLocale() === 'az' ? 'Hələ ki müraciət yoxdur' : 'No inquiries yet'))
            ->emptyStateDescription(app()->getLocale() === 'tr' ? 'İlanlarınızdan gelen mesajlar burada listelenecektir.' : 'Elanlarınızdan daxil olan müraciətlər burada görünəcək.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right');
    }
}
