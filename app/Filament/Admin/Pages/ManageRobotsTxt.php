<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;

class ManageRobotsTxt extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationGroup(): ?string {
        return __('admin.catalog_and_settings'); }

    protected static ?string $navigationLabel = 'Robots.txt Redaktoru';

    protected static ?string $title = 'Robots.txt Redaktoru';

    protected static ?int $navigationSort = 8;

    protected static string $view = 'filament.pages.manage-robots-txt';

    public ?array $data = [];

    public function mount(): void
    {
        $filePath = public_path('robots.txt');
        $content = File::exists($filePath) ? File::get($filePath) : "User-agent: *\nDisallow:\n\nSitemap: " . url('sitemap.xml');

        $this->form->fill([
            'content' => $content,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('admin.robots_file_content'))
                    ->description(__('admin.robots_help'))
                    ->schema([
                        Textarea::make('content')
                            ->label(__('admin.file_content'))
                            ->rows(18)
                            ->required()
                            ->extraInputAttributes(['style' => 'font-family: monospace;'])
                            ->placeholder("User-agent: *\nDisallow: /admin\n\nSitemap: " . url('sitemap.xml'))
                            ->helperText(__('admin.robots_edit_hint')),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $this->validate();
        
        try {
            $content = $this->data['content'] ?? '';
            $filePath = public_path('robots.txt');
            
            File::put($filePath, $content);

            Notification::make()
                ->title(__('admin.robots_updated'))
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title(__('admin.file_write_error'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
