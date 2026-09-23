<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\InquiryResource;
use App\Modules\Inquiry\Models\Inquiry;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestInquiriesTableWidget extends BaseWidget
{
    protected function getTableHeading(): string | \Illuminate\Contracts\Support\Htmlable | null {
        return __('admin.latest_customer_inquiries'); }
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Inquiry::query()->with(['property'])->latest('id')->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.customer'))
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('property.title')
                    ->label(__('admin.related_property_full'))
                    ->limit(30)
                    ->placeholder(__('admin.general_inquiry')),

                Tables\Columns\TextColumn::make('message')
                    ->label('Mesaj')
                    ->limit(45),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'new', 'yeni' => 'warning',
                        'contacted', 'əlaqə saxlanıldı' => 'info',
                        'closed', 'bağlandı' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarix')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('admin.view'))
                    ->icon('heroicon-m-eye')
                    ->url(fn (Inquiry $record): string => InquiryResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
