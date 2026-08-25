<?php

namespace App\Filament\Admin\Pages;

use App\Modules\Shared\Models\SiteSetting;
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

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'Parametrlər';
    protected static ?string $navigationLabel = 'Sayt Məlumatları & Əlaqə';
    protected static ?string $title = 'Sayt Məlumatları və Əlaqə Parametrləri';
    protected static ?int $navigationSort = 2;

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
                        Tabs\Tab::make('Əlaqə Məlumatları')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('phone')
                                        ->label('Əsas Telefon')
                                        ->tel()
                                        ->placeholder('+90 (548) 888-8888'),
                                    TextInput::make('phone_secondary')
                                        ->label('İkinci Telefon')
                                        ->tel()
                                        ->placeholder('+90 (392) 815 00 00'),
                                    TextInput::make('whatsapp')
                                        ->label('WhatsApp Nömrəsi')
                                        ->placeholder('+905488888888'),
                                    TextInput::make('email')
                                        ->label('Əsas E-poçt')
                                        ->email()
                                        ->placeholder('info@kibriskare.com'),
                                    TextInput::make('support_email')
                                        ->label('Dəstək E-poçtu')
                                        ->email()
                                        ->placeholder('support@kibriskare.com'),
                                ]),

                                Section::make('Ofis Ünvanı (4 Dildə)')
                                    ->description('Saytın Footer və Əlaqə səhifəsində görünəcək ofis ünvanı')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('address.tr')
                                                ->label('Ünvan (Türkcə)')
                                                ->placeholder('Girne, Kuzey Kıbrıs Türk Cumhuriyeti'),
                                            TextInput::make('address.az')
                                                ->label('Ünvan (Azərbaycanca)')
                                                ->placeholder('Girnə, Şimali Kipr'),
                                            TextInput::make('address.en')
                                                ->label('Ünvan (İngiliscə)')
                                                ->placeholder('Kyrenia, Northern Cyprus'),
                                            TextInput::make('address.ru')
                                                ->label('Ünvan (Rusca)')
                                                ->placeholder('Кирения, Северный Кипр'),
                                        ]),
                                    ])
                                    ->collapsible(),
                            ]),

                        Tabs\Tab::make('Sosial Şəbəkələr')
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

                        Tabs\Tab::make('İş Saatları & Xəritə')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Section::make('İş Rejimi')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('working_hours_mon_fri')
                                                ->label('Bazar ertəsi – Cümə')
                                                ->placeholder('09:00 – 19:00'),
                                            TextInput::make('working_hours_sat')
                                                ->label('Şənbə')
                                                ->placeholder('10:00 – 18:00'),
                                            TextInput::make('working_hours_sun')
                                                ->label('Bazar')
                                                ->placeholder('Online 7/24'),
                                        ]),
                                    ]),

                                Section::make('Ofis Xəritə Koordinatları')
                                    ->description('Əlaqə səhifəsində xəritə üzərində ofisin yerini təyin edir')
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

                        Tabs\Tab::make('Mətnlər & Footer')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Sayt Şüarı (Tagline)')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('tagline.tr')->label('Şüar (Türkcə)'),
                                            TextInput::make('tagline.az')->label('Şüar (Azərbaycanca)'),
                                            TextInput::make('tagline.en')->label('Şüar (İngiliscə)'),
                                            TextInput::make('tagline.ru')->label('Şüar (Rusca)'),
                                        ]),
                                    ])
                                    ->collapsible(),

                                Section::make('Footer Təsviri (4 Dildə)')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Textarea::make('footer_description.tr')->label('Təsvir (Türkcə)')->rows(3),
                                            Textarea::make('footer_description.az')->label('Təsvir (Azərbaycanca)')->rows(3),
                                            Textarea::make('footer_description.en')->label('Təsvir (İngiliscə)')->rows(3),
                                            Textarea::make('footer_description.ru')->label('Təsvir (Rusca)')->rows(3),
                                        ]),
                                    ])
                                    ->collapsible(),

                                TextInput::make('copyright_text')
                                    ->label('Müəllif Hüququ (Copyright)')
                                    ->placeholder('KibrisKare.com'),
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
            ->title('Parametrlər uğurla yadda saxlanıldı!')
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
