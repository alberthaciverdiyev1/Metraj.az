<?php

namespace App\Filament\Admin\Pages;

use App\Modules\Shared\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    public static function getNavigationGroup(): ?string {
        return __('admin.catalog_and_settings'); }
    public static function getNavigationLabel(): string {
        return __('admin.site_settings'); }
    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable {
        return __('admin.site_and_contact_settings'); }
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = SiteSetting::current();
        $this->form->fill($setting->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('SettingsTabs')
                    ->tabs([
                        Tabs\Tab::make(__('admin.contact_information'))
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('phone')
                                        ->label(__('admin.primary_phone'))
                                        ->tel()
                                        ->placeholder('+90 (548) 888-8888'),
                                    TextInput::make('phone_secondary')
                                        ->label(__('admin.second_phone'))
                                        ->tel()
                                        ->placeholder('+90 (392) 815 00 00'),
                                    TextInput::make('whatsapp')
                                        ->label(__('admin.whatsapp_number'))
                                        ->placeholder('+905488888888'),
                                    TextInput::make('email')
                                        ->label(__('admin.primary_email'))
                                        ->email()
                                        ->placeholder('info@kibriskare.com'),
                                    TextInput::make('support_email')
                                        ->label(__('admin.support_email'))
                                        ->email()
                                        ->placeholder('support@kibriskare.com'),
                                ]),

                                Section::make(__('admin.office_address_4langs'))
                                    ->description(__('admin.office_address_footer_hint'))
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('address.tr')
                                                ->label(__('admin.address_label_tr'))
                                                ->placeholder(__('admin.address_tr')),
                                            TextInput::make('address.az')
                                                ->label(__('admin.address_label_az'))
                                                ->placeholder(__('admin.address_az')),
                                            TextInput::make('address.en')
                                                ->label(__('admin.address_label_en'))
                                                ->placeholder('Kyrenia, Northern Cyprus'),
                                            TextInput::make('address.ru')
                                                ->label(__('admin.address_label_ru'))
                                                ->placeholder('Кирения, Северный Кипр'),
                                        ]),
                                    ])
                                    ->collapsible(),
                            ]),

                        Tabs\Tab::make(__('admin.social_networks'))
                            ->icon('heroicon-o-share')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('instagram_url')
                                        ->label('Instagram URL')
                                        ->url()
                                        ->placeholder('https://instagram.com/kibriskare'),
                                    TextInput::make('facebook_url')
                                        ->label('Facebook URL')
                                        ->url()
                                        ->placeholder('https://facebook.com/kibriskare'),
                                    TextInput::make('linkedin_url')
                                        ->label('LinkedIn URL')
                                        ->url()
                                        ->placeholder('https://linkedin.com/company/kibriskare'),
                                    TextInput::make('youtube_url')
                                        ->label('YouTube URL')
                                        ->url()
                                        ->placeholder('https://youtube.com/@kibriskare'),
                                    TextInput::make('telegram_url')
                                        ->label('Telegram URL')
                                        ->url()
                                        ->placeholder('https://t.me/kibriskare'),
                                    TextInput::make('tiktok_url')
                                        ->label('TikTok URL')
                                        ->url()
                                        ->placeholder('https://tiktok.com/@kibriskare'),
                                    TextInput::make('twitter_url')
                                        ->label('X (Twitter) URL')
                                        ->url()
                                        ->placeholder('https://x.com/kibriskare'),
                                ]),
                            ]),

                        Tabs\Tab::make(__('admin.working_hours_map'))
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Section::make(__('admin.working_hours'))
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('working_hours_mon_fri')
                                                ->label(__('admin.monday_friday'))
                                                ->placeholder('09:00 – 19:00'),
                                            TextInput::make('working_hours_sat')
                                                ->label(__('admin.saturday'))
                                                ->placeholder('10:00 – 18:00'),
                                            TextInput::make('working_hours_sun')
                                                ->label('Bazar')
                                                ->placeholder('Online 7/24'),
                                        ]),
                                    ]),

                                Section::make(__('admin.office_map_coordinates'))
                                    ->description(__('admin.office_map_location_hint'))
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('map_latitude')
                                                ->label('Enlik (Latitude)')
                                                ->numeric()
                                                ->placeholder('35.3382440'),
                                            TextInput::make('map_longitude')
                                                ->label('Uzunluq (Longitude)')
                                                ->numeric()
                                                ->placeholder('33.3186270'),
                                        ]),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('admin.texts_footer'))
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make(__('admin.site_tagline'))
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('tagline.tr')->label(__('admin.tagline_tr')),
                                            TextInput::make('tagline.az')->label(__('admin.tagline_az')),
                                            TextInput::make('tagline.en')->label(__('admin.tagline_en')),
                                            TextInput::make('tagline.ru')->label(__('admin.tagline_ru')),
                                        ]),
                                    ])
                                    ->collapsible(),

                                Section::make(__('admin.footer_description_4langs'))
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Textarea::make('footer_description.tr')->label(__('admin.description_field_tr'))->rows(3),
                                            Textarea::make('footer_description.az')->label(__('admin.description_field_az'))->rows(3),
                                            Textarea::make('footer_description.en')->label(__('admin.description_field_en'))->rows(3),
                                            Textarea::make('footer_description.ru')->label(__('admin.description_ru'))->rows(3),
                                        ]),
                                    ])
                                    ->collapsible(),

                                TextInput::make('copyright_text')
                                    ->label(__('admin.copyright'))
                                    ->placeholder('KibrisKare.com'),
                            ]),

                        Tabs\Tab::make(__('admin.legal_documents_terms'))
                            ->icon('heroicon-o-scale')
                            ->schema([
                                Section::make(__('admin.user_agreement_4langs'))
                                    ->description(__('admin.user_agreement_hint'))
                                    ->schema([
                                        Tabs::make('UserAgreementLangTabs')->tabs([
                                            Tabs\Tab::make(__('admin.turkish_tr'))->schema([
                                                RichEditor::make('user_agreement.tr')
                                                    ->label(__('admin.user_agreement_tr'))
                                                    ->placeholder(__('admin.user_agreement_tr_placeholder')),
                                            ]),
                                            Tabs\Tab::make(__('admin.azerbaijani_az'))->schema([
                                                RichEditor::make('user_agreement.az')
                                                    ->label(__('admin.user_agreement_az'))
                                                    ->placeholder(__('admin.user_agreement_az_placeholder')),
                                            ]),
                                            Tabs\Tab::make(__('admin.english_en'))->schema([
                                                RichEditor::make('user_agreement.en')
                                                    ->label('User Agreement (English)')
                                                    ->placeholder('Enter user agreement text here...'),
                                            ]),
                                            Tabs\Tab::make('Rusca (RU)')->schema([
                                                RichEditor::make('user_agreement.ru')
                                                    ->label('Пользовательское соглашение (Русский)')
                                                    ->placeholder('Введите текст пользовательского соглашения...'),
                                            ]),
                                        ]),
                                    ])
                                    ->collapsible(),

                                Section::make(__('admin.privacy_policy_4langs'))
                                    ->description(__('admin.privacy_policy_hint'))
                                    ->schema([
                                        Tabs::make('PrivacyPolicyLangTabs')->tabs([
                                            Tabs\Tab::make(__('admin.turkish_tr'))->schema([
                                                RichEditor::make('privacy_policy.tr')
                                                    ->label(__('admin.privacy_policy_tr'))
                                                    ->placeholder(__('admin.privacy_policy_tr_placeholder')),
                                            ]),
                                            Tabs\Tab::make(__('admin.azerbaijani_az'))->schema([
                                                RichEditor::make('privacy_policy.az')
                                                    ->label(__('admin.privacy_policy_az'))
                                                    ->placeholder(__('admin.privacy_policy_az_placeholder')),
                                            ]),
                                            Tabs\Tab::make(__('admin.english_en'))->schema([
                                                RichEditor::make('privacy_policy.en')
                                                    ->label('Privacy Policy (English)')
                                                    ->placeholder('Enter privacy policy text here...'),
                                            ]),
                                            Tabs\Tab::make('Rusca (RU)')->schema([
                                                RichEditor::make('privacy_policy.ru')
                                                    ->label('Политика конфиденциальности (Русский)')
                                                    ->placeholder('Введите текст политики конфиденциальности...'),
                                            ]),
                                        ]),
                                    ])
                                    ->collapsible(),

                                Section::make(__('admin.terms_4langs'))
                                    ->description(__('admin.terms_general_hint'))
                                    ->schema([
                                        Tabs::make('TermsOfUseLangTabs')->tabs([
                                            Tabs\Tab::make(__('admin.turkish_tr'))->schema([
                                                RichEditor::make('terms_of_use.tr')
                                                    ->label(__('admin.terms_tr'))
                                                    ->placeholder(__('admin.terms_tr_placeholder')),
                                            ]),
                                            Tabs\Tab::make(__('admin.azerbaijani_az'))->schema([
                                                RichEditor::make('terms_of_use.az')
                                                    ->label(__('admin.terms_az'))
                                                    ->placeholder(__('admin.terms_az_placeholder')),
                                            ]),
                                            Tabs\Tab::make(__('admin.english_en'))->schema([
                                                RichEditor::make('terms_of_use.en')
                                                    ->label('Terms of Use (English)')
                                                    ->placeholder('Enter terms of use text here...'),
                                            ]),
                                            Tabs\Tab::make('Rusca (RU)')->schema([
                                                RichEditor::make('terms_of_use.ru')
                                                    ->label('Условия использования (Русский)')
                                                    ->placeholder('Введите текст условий использования...'),
                                            ]),
                                        ]),
                                    ])
                                    ->collapsible(),
                            ]),

                        Tabs\Tab::make(__('admin.limits_message_templates'))
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make(__('admin.listing_pagination_limits'))
                                    ->description(__('admin.listing_visibility_limits'))
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('listing_expiration_days')
                                                ->label(__('admin.listing_active_days'))
                                                ->numeric()
                                                ->default(30)
                                                ->helperText(__('admin.listing_expiry_hint')),
                                            TextInput::make('items_per_page')
                                                ->label(__('admin.listings_per_page'))
                                                ->numeric()
                                                ->default(30),
                                            TextInput::make('featured_limit')
                                                ->label(__('admin.featured_listings_count'))
                                                ->numeric()
                                                ->default(10),
                                            TextInput::make('vip_limit')
                                                ->label(__('admin.vip_listings_count'))
                                                ->numeric()
                                                ->default(10),
                                        ]),
                                    ]),

                                Section::make(__('admin.whatsapp_templates_4langs'))
                                    ->description(__('admin.whatsapp_autofill_hint'))
                                    ->schema([
                                        Tabs::make('WhatsAppMsgTabs')->tabs([
                                            Tabs\Tab::make(__('admin.property_message'))->schema([
                                                Grid::make(2)->schema([
                                                    TextInput::make('whatsapp_property_message.tr')->label(__('admin.message_tr'))->placeholder(__('admin.whatsapp_property_template_tr')),
                                                    TextInput::make('whatsapp_property_message.az')->label(__('admin.message_az'))->placeholder(__('admin.whatsapp_property_template_az')),
                                                    TextInput::make('whatsapp_property_message.en')->label(__('admin.message_en'))->placeholder('Hello, I would like to get information regarding your KibrisKare.com listing: {title}'),
                                                    TextInput::make('whatsapp_property_message.ru')->label('Mesaj (Rusca)')->placeholder('Здравствуйте, хочу получить информацию по вашему объявлению на KibrisKare.com: {title}'),
                                                ]),
                                            ]),
                                            Tabs\Tab::make(__('admin.roommate_message'))->schema([
                                                Grid::make(2)->schema([
                                                    TextInput::make('whatsapp_roommate_message.tr')->label(__('admin.message_tr'))->placeholder(__('admin.whatsapp_roommate_template_tr')),
                                                    TextInput::make('whatsapp_roommate_message.az')->label(__('admin.message_az'))->placeholder(__('admin.whatsapp_roommate_template_az')),
                                                    TextInput::make('whatsapp_roommate_message.en')->label(__('admin.message_en'))->placeholder('Hello, I am contacting you regarding your roommate listing on KibrisKare.com: {title}'),
                                                    TextInput::make('whatsapp_roommate_message.ru')->label('Mesaj (Rusca)')->placeholder('Здравствуйте, пишу по поводу вашего объявления о поиске соседа на KibrisKare.com: {title}'),
                                                ]),
                                            ]),
                                        ]),
                                    ])
                                    ->collapsible(),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $setting = SiteSetting::firstOrNew(['id' => 1]);
        $setting->fill($data);
        $setting->save();

        Notification::make()
            ->title(__('admin.settings_saved_success'))
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
