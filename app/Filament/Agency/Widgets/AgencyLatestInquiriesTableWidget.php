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
        return __('admin.latest_customer_inquiries');
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
                    ->label(__('admin.customer'))
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('phone')
                    ->label(__('admin.phone_short'))
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('property.title')
                    ->label(__('admin.related_property_full'))
                    ->limit(25)
                    ->placeholder(__('admin.general_inquiry')),

                Tables\Columns\TextColumn::make('message')
                    ->label(__('admin.message_field'))
                    ->limit(35),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.date_field'))
                    ->since(),
            ])
            ->emptyStateHeading(__('admin.no_inquiries_yet'))
            ->emptyStateDescription(__('admin.incoming_inquiries_hint'))
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right');
    }
}
