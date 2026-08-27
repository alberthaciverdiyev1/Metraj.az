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

    protected static ?string $navigationGroup = 'Kataloq və Tənzimləmələr';

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
                Section::make('robots.txt Faylının Məzmunu')
                    ->description('Axtarış motorlarının (Google, Yandex, Bing) saytınızda hansı səhifələri indeksləyib, hansıları indeksləyə bilməyəcəyini tənzimləyən qaydalar.')
                    ->schema([
                        Textarea::make('content')
                            ->label('Fayl Məzmunu')
                            ->rows(18)
                            ->required()
                            ->fontFamily('mono')
                            ->placeholder("User-agent: *\nDisallow: /admin\n\nSitemap: " . url('sitemap.xml'))
                            ->helperText('Hər bir qaydanı yeni sətirdən yazın. Dəyişikliklərdən dərhal sonra robots.txt faylı yenilənəcək.'),
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
                ->title('robots.txt faylı uğurla yeniləndi!')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Fayla yazılarkən xəta baş verdi!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
