<?php

namespace App\Filament\Admin\Resources\BlogResource\Pages;

use App\Filament\Admin\Resources\BlogResource;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewBlog extends ViewRecord
{
    protected static string $resource = BlogResource::class;

    protected static string $view = 'filament-panels::resources.pages.view-record';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Bloq')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('title')
                            ->label(__('admin.title'))
                            ->weight('bold')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->columnSpanFull(),

                        TextEntry::make('category')
                            ->label('Kategoriya')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'Məsləhət' => 'info',
                                'Bazar' => 'success',
                                'Xəbər' => 'warning',
                                'İnvestisiya' => 'primary',
                                'Hüquqi' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('published_at')
                            ->label(__('admin.publish_date'))
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('slug')
                            ->label('URL (Slug)')
                            ->copyable()
                            ->copyableState(fn ($state): string => url('/blog/' . $state)),

                        ImageEntry::make('cover_image')
                            ->label(__('admin.cover_image'))
                            ->height(200)
                            ->columnSpanFull()
                            ->extraImgAttributes(['class' => 'rounded-xl object-cover']),

                        TextEntry::make('excerpt')
                            ->label(__('admin.excerpt'))
                            ->columnSpanFull(),

                        TextEntry::make('content')
                            ->label(__('admin.content'))
                            ->html()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
