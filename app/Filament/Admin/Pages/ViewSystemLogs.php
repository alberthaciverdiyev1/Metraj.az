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
    public static function getNavigationGroup(): ?string {
        return __('admin.catalog_and_settings'); }
    public static function getNavigationLabel(): string {
        return __('admin.system_logs'); }
    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable {
        return __('admin.laravel_system_logs'); }
    protected static ?int $navigationSort = 6;

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
            Notification::make()->danger()->title(__('admin.no_log_file_found'))->send();
            return;
        }

        File::put($filePath, '');

        Notification::make()
            ->success()
            ->title(__('admin.log_file_cleared', ['file' => $this->selectedFile]))
            ->send();
    }

    /**
     * Log faylını tamamilə silir
     */
    public function deleteSelectedLog(): void
    {
        $filePath = $this->getSelectedFilePath();
        if (!$filePath) {
            Notification::make()->danger()->title(__('admin.no_log_file_found'))->send();
            return;
        }

        $deletedName = $this->selectedFile;
        File::delete($filePath);

        $files = $this->getLogFiles();
        $this->selectedFile = !empty($files) ? $files[0]['name'] : null;

        Notification::make()
            ->success()
            ->title(__('admin.log_file_deleted', ['file' => $deletedName]))
            ->send();
    }

    /**
     * Log faylını endirir
     */
    public function downloadSelectedLog(): ?BinaryFileResponse
    {
        $filePath = $this->getSelectedFilePath();
        if (!$filePath) {
            Notification::make()->danger()->title(__('admin.no_log_file_found'))->send();
            return null;
        }

        return response()->download($filePath);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download')
                ->label(__('admin.download_file'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action('downloadSelectedLog')
                ->visible(fn () => !empty($this->selectedFile)),

            Action::make('clear')
                ->label(__('admin.clear_contents'))
                ->icon('heroicon-o-paint-brush')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading(__('admin.clear_log_file_confirm'))
                ->modalDescription(__('admin.clear_log_file_hint'))
                ->modalSubmitActionLabel(__('admin.yes_clear'))
                ->action('clearSelectedLog')
                ->visible(fn () => !empty($this->selectedFile)),

            Action::make('delete')
                ->label(__('admin.delete_file'))
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(__('admin.delete_log_file_confirm'))
                ->modalDescription(__('admin.file_will_be_deleted'))
                ->modalSubmitActionLabel(__('admin.yes_delete'))
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
