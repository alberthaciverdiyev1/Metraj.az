<?php

namespace App\Filament\Admin\Pages;

use App\Modules\Shared\Models\PageSeo;
use App\Modules\Shared\Models\SeoSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSeoSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Parametrlər';
    protected static ?string $navigationLabel = 'SEO & Skriptlər';
    protected static ?string $title = 'SEO Tənzimləmələri və Qlobal Skriptlər';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.manage-seo-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = SeoSetting::current();
        PageSeo::ensureDefaults();
        $pages = PageSeo::orderBy('sort_order')->get();

        $pageData = [];
        foreach ($pages as $page) {
            $pageData[$page->page_key] = [
                'page_name' => $page->page_name,
                'title' => $page->title,
                'description' => $page->description,
                'keywords' => $page->keywords,
            ];
        }

        $this->form->fill(array_merge(
            $setting->toArray(),
            ['pages' => $pageData]
        ));
    }

    public function form(Form $form): Form
    {
        PageSeo::ensureDefaults();
        $pages = PageSeo::orderBy('sort_order')->get();

        $pageTabs = [];
        foreach ($pages as $p) {
            $key = $p->page_key;
            $pageTabs[] = Tabs\Tab::make("page_{$key}")
                ->label($p->page_name)
                ->schema([
                    Section::make("{$p->page_name} — SEO Başlığı (Meta Title)")
                        ->description('Axtarış sistemlərində və brauzer tabında görünəcək səhifə başlığı')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make("pages.{$key}.title.tr")->label('Title (Türkcə)'),
                                TextInput::make("pages.{$key}.title.az")->label('Title (Azərbaycanca)'),
                                TextInput::make("pages.{$key}.title.en")->label('Title (İngiliscə)'),
                                TextInput::make("pages.{$key}.title.ru")->label('Title (Rusca)'),
                            ]),
                        ])
                        ->collapsible(),

                    Section::make("{$p->page_name} — SEO Təsviri (Meta Description)")
                        ->description('Google və digər axtarış sistemlərində nəticələrin altında çıxan təsvir')
                        ->schema([
                            Grid::make(2)->schema([
                                Textarea::make("pages.{$key}.description.tr")->label('Description (Türkcə)')->rows(2),
                                Textarea::make("pages.{$key}.description.az")->label('Description (Azərbaycanca)')->rows(2),
                                Textarea::make("pages.{$key}.description.en")->label('Description (İngiliscə)')->rows(2),
                                Textarea::make("pages.{$key}.description.ru")->label('Description (Rusca)')->rows(2),
                            ]),
                        ])
                        ->collapsible(),

                    Section::make("{$p->page_name} — Açar Sözlər (Meta Keywords)")
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make("pages.{$key}.keywords.tr")->label('Keywords (Türkcə)')->placeholder('vergüllə ayırın'),
                                TextInput::make("pages.{$key}.keywords.az")->label('Keywords (Azərbaycanca)')->placeholder('vergüllə ayırın'),
                                TextInput::make("pages.{$key}.keywords.en")->label('Keywords (İngiliscə)')->placeholder('comma separated'),
                                TextInput::make("pages.{$key}.keywords.ru")->label('Keywords (Rusca)')->placeholder('через запятую'),
                            ]),
                        ])
                        ->collapsible()
                        ->collapsed(),
                ]);
        }

        return $form
            ->schema([
                Tabs::make('SeoSettingsTabs')
                    ->tabs([
                        Tabs\Tab::make('Qlobal Skriptlər (Head, Body, Footer)')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Section::make('1. <head> Skriptləri (Global Head Scripts)')
                                    ->description('Google Analytics (gtag.js), Google Tag Manager (<head>), Meta Pixel, Yandex Metrika və ya Meta Verification kodları. Saytın bütün səhifələrində <head> daxilində birbaşa (RAW) icra olunur.')
                                    ->schema([
                                        Textarea::make('head_scripts')
                                            ->label('HTML / JS Kodları (<head>)')
                                            ->rows(6)
                                            ->extraAttributes(['class' => 'font-mono text-xs'])
                                            ->placeholder("<!-- Google Tag Manager -->\n<script>...</script>\n<!-- End Google Tag Manager -->"),
                                    ]),

                                Section::make('2. <body> Skriptləri (Global Body Opening Scripts)')
                                    ->description('Google Tag Manager (<noscript>) və ya <body> tagı açılan kimi dərhal icra olunmalı olan izləmə və xüsusi kodlar.')
                                    ->schema([
                                        Textarea::make('body_scripts')
                                            ->label('HTML / JS Kodları (<body>)')
                                            ->rows(5)
                                            ->extraAttributes(['class' => 'font-mono text-xs'])
                                            ->placeholder("<!-- Google Tag Manager (noscript) -->\n<noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=GTM-XXXX\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>"),
                                    ]),

                                Section::make('3. Footer / </body> Skriptləri (Global Footer Scripts)')
                                    ->description('Canlı Çat (Tawk.to, WhatsApp vidjetləri), reCAPTCHA və ya </body> bağlanışından dərhal əvvəl yüklənən digər üçüncü tərəf skriptləri.')
                                    ->schema([
                                        Textarea::make('footer_scripts')
                                            ->label('HTML / JS Kodları (</body> öncəsi)')
                                            ->rows(5)
                                            ->extraAttributes(['class' => 'font-mono text-xs'])
                                            ->placeholder("<!-- Live Chat Widget -->\n<script>...</script>"),
                                    ]),
                            ]),

                        Tabs\Tab::make('Səhifə Başlıqları və Meta (Hər Səhifə Üçün)')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Tabs::make('PageTabs')
                                    ->tabs($pageTabs),
                            ]),

                        Tabs\Tab::make('Qlobal Standart Meta (Default)')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Section::make('Ümumi Sayt Başlığı (Default Meta Title)')
                                    ->description('Xüsusi başlıq təyin olunmayan səhifələrdə istifadə ediləcək standart başlıq')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('default_meta_title.tr')->label('Title (Türkcə)'),
                                            TextInput::make('default_meta_title.az')->label('Title (Azərbaycanca)'),
                                            TextInput::make('default_meta_title.en')->label('Title (İngiliscə)'),
                                            TextInput::make('default_meta_title.ru')->label('Title (Rusca)'),
                                        ]),
                                    ]),

                                Section::make('Ümumi Sayt Təsviri (Default Meta Description)')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Textarea::make('default_meta_description.tr')->label('Description (Türkcə)')->rows(2),
                                            Textarea::make('default_meta_description.az')->label('Description (Azərbaycanca)')->rows(2),
                                            Textarea::make('default_meta_description.en')->label('Description (İngiliscə)')->rows(2),
                                            Textarea::make('default_meta_description.ru')->label('Description (Rusca)')->rows(2),
                                        ]),
                                    ]),

                                Section::make('Ümumi Açar Sözlər (Default Meta Keywords)')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('default_meta_keywords.tr')->label('Keywords (Türkcə)'),
                                            TextInput::make('default_meta_keywords.az')->label('Keywords (Azərbaycanca)'),
                                            TextInput::make('default_meta_keywords.en')->label('Keywords (İngiliscə)'),
                                            TextInput::make('default_meta_keywords.ru')->label('Keywords (Rusca)'),
                                        ]),
                                    ]),

                                TextInput::make('og_image')
                                    ->label('Standart Sosial Şəbəkə Şəkli (OG:Image URL)')
                                    ->placeholder('https://kibriskare.com/images/og-share.jpg'),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // 1. Save Global SEO Settings
        $setting = SeoSetting::firstOrNew(['id' => 1]);
        $setting->fill([
            'head_scripts' => $data['head_scripts'] ?? null,
            'body_scripts' => $data['body_scripts'] ?? null,
            'footer_scripts' => $data['footer_scripts'] ?? null,
            'default_meta_title' => $data['default_meta_title'] ?? null,
            'default_meta_description' => $data['default_meta_description'] ?? null,
            'default_meta_keywords' => $data['default_meta_keywords'] ?? null,
            'og_image' => $data['og_image'] ?? null,
        ]);
        $setting->save();

        // 2. Save Page-by-Page SEO Settings
        if (isset($data['pages']) && is_array($data['pages'])) {
            foreach ($data['pages'] as $pageKey => $pData) {
                PageSeo::updateOrCreate(
                    ['page_key' => $pageKey],
                    [
                        'title' => $pData['title'] ?? null,
                        'description' => $pData['description'] ?? null,
                        'keywords' => $pData['keywords'] ?? null,
                    ]
                );
            }
        }

        Notification::make()
            ->title('SEO və Skript tənzimləmələri uğurla yadda saxlanıldı!')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Yadda Saxla')
                ->submit('save')
                ->color('primary'),
        ];
    }
}
