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
    public static function getNavigationGroup(): ?string {
        return __('admin.catalog_and_settings'); }
    public static function getNavigationLabel(): string {
        return __('admin.seo_settings'); }
    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable {
        return __('admin.seo_settings_global_scripts'); }
    protected static ?int $navigationSort = 5;

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
                'h1' => $page->h1,
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
        $iconMap = [
            'home' => 'heroicon-o-home',
            'listing_sale' => 'heroicon-o-tag',
            'listing_rent_monthly' => 'heroicon-o-key',
            'listing_rent_daily' => 'heroicon-o-calendar',
            'requests' => 'heroicon-o-megaphone',
            'requests_create' => 'heroicon-o-plus-circle',
            'roommates' => 'heroicon-o-user-group',
            'roommates_create' => 'heroicon-o-user-plus',
            'add_property' => 'heroicon-o-document-plus',
            'agencies' => 'heroicon-o-building-office-2',
            'blog' => 'heroicon-o-newspaper',
            'contact' => 'heroicon-o-phone',
            'about' => 'heroicon-o-information-circle',
            'faq' => 'heroicon-o-question-mark-circle',
            'compare' => 'heroicon-o-arrows-right-left',
            'favorites' => 'heroicon-o-heart',
        ];

        $pageTabs = [];
        foreach ($pages as $p) {
            $key = $p->page_key;
            $pageTabs[] = Tabs\Tab::make("page_{$key}")
                ->label($p->page_name)
                ->icon($iconMap[$key] ?? 'heroicon-o-document-text')
                ->schema([
                    Section::make(__('admin.seo_section_h1', ['page' => $p->page_name]))
                        ->description(__('admin.seo_h1_hint'))
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make("pages.{$key}.h1.tr")->label(__('admin.h1_tr'))->placeholder(__('admin.example_h1_tr')),
                                TextInput::make("pages.{$key}.h1.az")->label(__('admin.h1_az'))->placeholder(__('admin.example_h1_az')),
                                TextInput::make("pages.{$key}.h1.en")->label(__('admin.h1_en'))->placeholder('e.g: Properties For Sale in Northern Cyprus'),
                                TextInput::make("pages.{$key}.h1.ru")->label('H1 (Rusca)')->placeholder('напр: Недвижимость на Северном Кипре'),
                            ]),
                        ])
                        ->collapsible(),

                    Section::make(__('admin.seo_section_meta_title', ['page' => $p->page_name]))
                        ->description(__('admin.seo_meta_title_hint'))
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make("pages.{$key}.title.tr")->label(__('admin.title_tr')),
                                TextInput::make("pages.{$key}.title.az")->label(__('admin.title_az')),
                                TextInput::make("pages.{$key}.title.en")->label(__('admin.title_en')),
                                TextInput::make("pages.{$key}.title.ru")->label('Title (Rusca)'),
                            ]),
                        ])
                        ->collapsible(),

                    Section::make(__('admin.seo_section_meta_description', ['page' => $p->page_name]))
                        ->description(__('admin.meta_description_hint'))
                        ->schema([
                            Grid::make(2)->schema([
                                Textarea::make("pages.{$key}.description.tr")->label(__('admin.description_tr'))->rows(2),
                                Textarea::make("pages.{$key}.description.az")->label(__('admin.description_az'))->rows(2),
                                Textarea::make("pages.{$key}.description.en")->label(__('admin.description_en'))->rows(2),
                                Textarea::make("pages.{$key}.description.ru")->label('Description (Rusca)')->rows(2),
                            ]),
                        ])
                        ->collapsible(),

                    Section::make(__('admin.seo_section_meta_keywords', ['page' => $p->page_name]))
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make("pages.{$key}.keywords.tr")->label(__('admin.keywords_tr'))->placeholder(__('admin.separate_with_commas')),
                                TextInput::make("pages.{$key}.keywords.az")->label(__('admin.keywords_az'))->placeholder(__('admin.separate_with_commas')),
                                TextInput::make("pages.{$key}.keywords.en")->label(__('admin.keywords_en'))->placeholder('comma separated'),
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
                        Tabs\Tab::make(__('admin.global_scripts'))
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Section::make(__('admin.head_scripts_title'))
                                    ->description(__('admin.head_scripts_hint'))
                                    ->schema([
                                        Textarea::make('head_scripts')
                                            ->label(__('admin.html_js_head'))
                                            ->rows(6)
                                            ->extraAttributes(['class' => 'font-mono text-xs'])
                                            ->placeholder("<!-- Google Tag Manager -->\n<script>...</script>\n<!-- End Google Tag Manager -->"),
                                    ]),

                                Section::make(__('admin.body_scripts_title'))
                                    ->description(__('admin.body_scripts_hint'))
                                    ->schema([
                                        Textarea::make('body_scripts')
                                            ->label(__('admin.html_js_body'))
                                            ->rows(5)
                                            ->extraAttributes(['class' => 'font-mono text-xs'])
                                            ->placeholder("<!-- Google Tag Manager (noscript) -->\n<noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=GTM-XXXX\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>"),
                                    ]),

                                Section::make(__('admin.footer_scripts_title'))
                                    ->description(__('admin.footer_scripts_hint'))
                                    ->schema([
                                        Textarea::make('footer_scripts')
                                            ->label(__('admin.html_js_footer'))
                                            ->rows(5)
                                            ->extraAttributes(['class' => 'font-mono text-xs'])
                                            ->placeholder("<!-- Live Chat Widget -->\n<script>...</script>"),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('admin.page_titles_meta'))
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Tabs::make('PageTabs')
                                    ->tabs($pageTabs),
                            ]),

                        Tabs\Tab::make('Qlobal Standart Meta (Default)')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Section::make(__('admin.default_meta_title'))
                                    ->description(__('admin.default_meta_title_hint'))
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('default_meta_title.tr')->label(__('admin.title_tr')),
                                            TextInput::make('default_meta_title.az')->label(__('admin.title_az')),
                                            TextInput::make('default_meta_title.en')->label(__('admin.title_en')),
                                            TextInput::make('default_meta_title.ru')->label('Title (Rusca)'),
                                        ]),
                                    ]),

                                Section::make(__('admin.default_meta_description'))
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Textarea::make('default_meta_description.tr')->label(__('admin.description_tr'))->rows(2),
                                            Textarea::make('default_meta_description.az')->label(__('admin.description_az'))->rows(2),
                                            Textarea::make('default_meta_description.en')->label(__('admin.description_en'))->rows(2),
                                            Textarea::make('default_meta_description.ru')->label('Description (Rusca)')->rows(2),
                                        ]),
                                    ]),

                                Section::make(__('admin.default_meta_keywords'))
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('default_meta_keywords.tr')->label(__('admin.keywords_tr')),
                                            TextInput::make('default_meta_keywords.az')->label(__('admin.keywords_az')),
                                            TextInput::make('default_meta_keywords.en')->label(__('admin.keywords_en')),
                                            TextInput::make('default_meta_keywords.ru')->label('Keywords (Rusca)'),
                                        ]),
                                    ]),

                                TextInput::make('og_image')
                                    ->label(__('admin.default_og_image'))
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
                        'h1' => $pData['h1'] ?? null,
                        'title' => $pData['title'] ?? null,
                        'description' => $pData['description'] ?? null,
                        'keywords' => $pData['keywords'] ?? null,
                    ]
                );
            }
        }

        Notification::make()
            ->title(__('admin.seo_scripts_saved'))
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
