<?php

namespace App\Filament\Admin\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ViewSystemLogs extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $navigationGroup = 'Parametrlər';
    protected static ?string $navigationLabel = 'Sistem Loqları';
    protected static ?string $title = 'Laravel Sistem Loqları';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.view-system-logs';

    public ?string $selectedFile = null;
    public string $filterLevel = 'ALL';
    public string $searchQuery = '';
    public int $limit = 100;

    public function mount(): void
    {
        $files = $this->getLogFiles();
        if (!empty($files)) {
            $this->selectedFile = $files[0]['name'];
        }
    }

    /**
     * Bütün log fayllarının siyahısı
     */
    public function getLogFiles(): array
    {
        $logPath = storage_path('logs');
        if (!File::isDirectory($logPath)) {
            return [];
        }

        $files = File::glob($logPath . '/*.log');
        $list = [];

        foreach ($files as $filePath) {
            $filename = basename($filePath);
            $size = File::size($filePath);
            $modified = File::lastModified($filePath);

            $list[] = [
                'name' => $filename,
                'path' => $filePath,
                'size' => $this->formatBytes($size),
                'size_raw' => $size,
                'modified' => date('d.m.Y H:i:s', $modified),
                'timestamp' => $modified,
            ];
        }

        // Ən son dəyişdirilən fayl yuxarıda olsun
        usort($list, fn ($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return $list;
    }

    /**
     * Seçilmiş faylın tam yolu
     */
    public function getSelectedFilePath(): ?string
    {
        if (!$this->selectedFile) {
            return null;
        }

        $path = storage_path('logs/' . basename($this->selectedFile));
        return File::exists($path) ? $path : null;
    }

    /**
     * Seçilmiş log faylının məzmununu parse edib qaytarır
     */
    public function getParsedLogs(): array
    {
        $filePath = $this->getSelectedFilePath();
        if (!$filePath) {
            return [];
        }

        $content = File::get($filePath);
        if (empty(trim($content))) {
            return [];
        }

        // Laravel standart log formatı regex
        $pattern = '/^\[(?P<date>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}(?:\.\d+)?(?:[\+\-]\d{2}:\d{2})?)\] (?P<env>\w+)\.(?P<level>[A-Z]+): (?P<message>.*)$/m';

        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) {
            return [
                [
                    'date' => date('Y-m-d H:i:s', File::lastModified($filePath)),
                    'env' => 'app',
                    'level' => 'RAW',
                    'message' => mb_substr($content, 0, 500),
                    'stack' => $content,
                ]
            ];
        }

        $totalMatches = count($matches[0]);
        $entries = [];

        for ($i = 0; $i < $totalMatches; $i++) {
            $date = $matches['date'][$i][0];
            $env = $matches['env'][$i][0];
            $level = strtoupper($matches['level'][$i][0]);
            $message = $matches['message'][$i][0];

            $offset = $matches[0][$i][1];
            $length = strlen($matches[0][$i][0]);

            $nextOffset = ($i + 1 < $totalMatches) ? $matches[0][$i + 1][1] : strlen($content);
            $stackChunk = substr($content, $offset + $length, $nextOffset - ($offset + $length));
            $stack = trim($stackChunk);

            // Filter by Level
            if ($this->filterLevel !== 'ALL' && $level !== $this->filterLevel) {
                continue;
            }

            // Filter by Search Query
            if ($this->searchQuery !== '') {
                $q = mb_strtolower($this->searchQuery);
                if (
                    !str_contains(mb_strtolower($message), $q) &&
                    !str_contains(mb_strtolower($stack), $q) &&
                    !str_contains(mb_strtolower($date), $q)
                ) {
                    continue;
                }
            }

            $entries[] = [
                'id' => $i,
                'date' => $date,
                'env' => $env,
                'level' => $level,
                'message' => $message,
                'stack' => $stack,
            ];
        }

        // Ən son loqlar yuxarıda çıxsın
        $entries = array_reverse($entries);

        if ($this->limit > 0) {
            $entries = array_slice($entries, 0, $this->limit);
        }

        return $entries;
    }

    /**
     * Log faylının içini təmizləyir
     */
    public function clearSelectedLog(): void
    {
        $filePath = $this->getSelectedFilePath();
        if (!$filePath) {
            Notification::make()->danger()->title('Log faylı tapılmadı!')->send();
            return;
        }

        File::put($filePath, '');

        Notification::make()
            ->success()
            ->title("{$this->selectedFile} faylının məzmunu uğurla təmizləndi!")
            ->send();
    }

    /**
     * Log faylını tamamilə silir
     */
    public function deleteSelectedLog(): void
    {
        $filePath = $this->getSelectedFilePath();
        if (!$filePath) {
            Notification::make()->danger()->title('Log faylı tapılmadı!')->send();
            return;
        }

        $deletedName = $this->selectedFile;
        File::delete($filePath);

        $files = $this->getLogFiles();
        $this->selectedFile = !empty($files) ? $files[0]['name'] : null;

        Notification::make()
            ->success()
            ->title("{$deletedName} faylı uğurla silindi!")
            ->send();
    }

    /**
     * Log faylını endirir
     */
    public function downloadSelectedLog(): ?BinaryFileResponse
    {
        $filePath = $this->getSelectedFilePath();
        if (!$filePath) {
            Notification::make()->danger()->title('Log faylı tapılmadı!')->send();
            return null;
        }

        return response()->download($filePath);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download')
                ->label('Faylı Endir')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action('downloadSelectedLog')
                ->visible(fn () => !empty($this->selectedFile)),

            Action::make('clear')
                ->label('İçini Təmizlə')
                ->icon('heroicon-o-paint-brush')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Log faylının məzmununu təmizləmək istəyirsiniz?')
                ->modalDescription('Seçilmiş log faylının daxilindəki bütün qeydlər silinəcək, faylın özü saxlanılacaq.')
                ->modalSubmitActionLabel('Bəli, təmizlə')
                ->action('clearSelectedLog')
                ->visible(fn () => !empty($this->selectedFile)),

            Action::make('delete')
                ->label('Faylı Sil')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Log faylını tamamilə silmək istəyirsiniz?')
                ->modalDescription('Bu fayl server diskindən tamamilə silinəcək.')
                ->modalSubmitActionLabel('Bəli, sil')
                ->action('deleteSelectedLog')
                ->visible(fn () => !empty($this->selectedFile)),
        ];
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
