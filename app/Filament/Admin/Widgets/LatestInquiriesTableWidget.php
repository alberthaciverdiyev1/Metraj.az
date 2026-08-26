<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\InquiryResource;
use App\Modules\Inquiry\Models\Inquiry;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestInquiriesTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Son Müştəri Müraciətləri';
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
                    ->label('Müştəri')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('property.title')
                    ->label('Əlaqəli Əmlak')
                    ->limit(30)
                    ->placeholder('Ümumi Müraciət'),

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
                    ->label('Baxış')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Inquiry $record): string => InquiryResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
