<?php

namespace App\Filament\Admin\Pages;

use App\Services\SitemapService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;

class ManageSitemap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Kataloq və Tənzimləmələr';

    protected static ?string $navigationLabel = 'Sitemap İdarəetməsi';

    protected static ?string $title = 'Sitemap XML Generator';

    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.pages.manage-sitemap';

    public array $sitemaps = [];

    public function mount(): void
    {
        $this->loadSitemaps();
    }

    public function loadSitemaps(): void
    {
        $this->sitemaps = [];
        $baseUrl = rtrim(config('app.url'), '/');
        $publicPath = public_path();
        
        $files = File::glob($publicPath . '/sitemap*.xml');
        
        foreach ($files as $file) {
            $name = basename($file);
            $content = File::get($file);
            
            // Count <url> or <sitemap> tags
            $urlCount = substr_count($content, '<url>');
            $isIndex = false;
            
            if ($urlCount === 0) {
                $urlCount = substr_count($content, '<sitemap>');
                $isIndex = true;
            }

            $this->sitemaps[] = [
                'name' => $name,
                'url' => $baseUrl . '/' . $name,
                'count' => $urlCount,
                'is_index' => $isIndex,
                'size' => round(filesize($file) / 1024, 2) . ' KB',
                'modified_at' => date('d.m.Y H:i:s', filemtime($file)),
            ];
        }

        // Sort: index/sitemap.xml first, then by page number
        usort($this->sitemaps, function ($a, $b) {
            if ($a['name'] === 'sitemap.xml') return -1;
            if ($b['name'] === 'sitemap.xml') return 1;
            return strcmp($a['name'], $b['name']);
        });
    }

    public function generateSitemap(SitemapService $sitemapService): void
    {
        try {
            $results = $sitemapService->generate();
            $this->loadSitemaps();

            Notification::make()
                ->title('Sitemap XML faylları uğurla yaradıldı!')
                ->body('Ümumi ' . count($results) . ' sitemap faylı yeniləndi.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Sitemap yaradılarkən xəta baş verdi!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
