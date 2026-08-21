<?php

namespace App\Filament\Pages;

use App\Modules\Shared\Models\User;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class EditProfile extends BaseEditProfile
{
    protected static ?string $title = 'Profilim';

    protected static ?string $navigationLabel = 'Profilim';

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? __('Profilim');
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? __('Profilim');
    }

    public static function getSlug(): string
    {
        return static::$slug ?? 'profile';
    }

    /**
     * İstifadəçinin rolunu təyin edir (admin / sahib / rieltor / normal).
     */
    protected function getRoleLabel(): string
    {
        $user = $this->getUser();

        return match (true) {
            $user->email === User::ADMIN_EMAIL => 'Admin (Super Administrator)',
            $user->isTenantOwner() => 'Agentlik Sahibi — ' . ($user->tenantAgency()?->name ?? 'Agentlik'),
            $user->agent && $user->agent->agency_id !== null => 'Rieltor — ' . ($user->agent->agency?->name ?? 'Agentlik'),
            $user->agent !== null => 'Müstəqil Rieltor',
            default => 'İstifadəçi',
        };
    }

    /**
     * Əsas profil formuna əlavə komponentlər:
     * - Əgər Agentlik Sahibidirsə: YALNIZ Agentlik Məlumatlarını görür (rieltor görmür).
     * - Əgər Rieltordursa: YALNIZ Rieltor Məlumatlarını görür (agentlik görmür).
     */
    protected function getAdditionalFormComponents(): array
    {
        $user = $this->getUser();

        $components = [
            Placeholder::make('role_summary')
                ->label('Platforma Rolu')
                ->content(new HtmlString(
                    '<span class="inline-flex items-center gap-1 rounded-full bg-orange-100 text-orange-700 text-xs font-medium px-3 py-1">' .
                    e($this->getRoleLabel()) .
                    '</span>'
                )),
        ];

        // 1. Əgər Agentlik Sahibidirsə -> YALNIZ Agentlik Məlumatları göstərilir
        if ($user->isTenantOwner() && $user->tenantAgency()) {
            $components[] = Section::make('Agentlik Məlumatları')
                ->description('Veb saytında və elanlarınızda görünən rəsmi agentlik detalları.')
                ->schema([
                    TextInput::make('agency.name')
                        ->label('Agentliyin Adı')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('agency.description')
                        ->label('Haqqında Ətraflı Məlumat')
                        ->rows(3)
                        ->columnSpanFull()
                        ->helperText('Agentliyiniz haqqında ətraflı məlumat.'),

                    FileUpload::make('agency.logo')
                        ->label('Agentlik Loqosu')
                        ->image()
                        ->imageEditor()
                        ->directory('agencies')
                        ->visibility('public')
                        ->columnSpan(1),

                    FileUpload::make('agency.banner')
                        ->label('Banner Şəkli')
                        ->image()
                        ->imageEditor()
                        ->directory('agencies')
                        ->visibility('public')
                        ->columnSpan(1),

                    TextInput::make('agency.phone')
                        ->label('Telefon Nömrəsi')
                        ->tel()
                        ->required(),

                    TextInput::make('agency.whatsapp')
                        ->label('WhatsApp Nömrəsi')
                        ->tel()
                        ->prefixIcon('heroicon-o-chat-bubble-left-right'),

                    TextInput::make('agency.email')
                        ->label('Rəsmi E-poçt')
                        ->email(),

                    TextInput::make('agency.website')
                        ->label('Vebsayt')
                        ->url()
                        ->placeholder('https://...'),

                    TextInput::make('agency.address')
                        ->label('Ofis Ünvanı')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])->columns(2);
        }
        // 2. Əks halda Rieltordursa -> YALNIZ Rieltor Profili göstərilir
        elseif ($user->agent) {
            $components[] = Section::make('Rieltor Profili')
                ->description('Veb saytında görünən rieltor məlumatlarınızı buradan yeniləyin.')
                ->schema([
                    FileUpload::make('agent.avatar')
                        ->label('Profil Şəkli')
                        ->image()
                        ->imageEditor()
                        ->directory('agents')
                        ->visibility('public')
                        ->columnSpanFull(),

                    TextInput::make('agent.position')
                        ->label('Vəzifə / Titul')
                        ->placeholder('Məs: Baş rieltor, Satış meneceri')
                        ->maxLength(255),

                    TextInput::make('agent.phone')
                        ->label('Əlaqə Nömrəsi')
                        ->tel(),

                    TextInput::make('agent.whatsapp')
                        ->label('WhatsApp Nömrəsi')
                        ->tel()
                        ->prefixIcon('heroicon-o-chat-bubble-left-right'),
                ])->columns(2);
        }

        return $components;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        ...$this->getAdditionalFormComponents(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(! static::isSimple()),
            ),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->getUser();

        // 1. Agentlik Sahibi üçün
        if ($user->isTenantOwner() && $user->tenantAgency()) {
            $agency = $user->tenantAgency();
            $data['agency'] = [
                'name' => $agency->name,
                'description' => $agency->description,
                'logo' => $agency->logo,
                'banner' => $agency->banner,
                'phone' => $agency->phone,
                'whatsapp' => $agency->whatsapp,
                'email' => $agency->email,
                'website' => $agency->website,
                'address' => $agency->address,
            ];
        }
        // 2. Rieltor üçün
        elseif ($user->agent) {
            $data['agent'] = [
                'avatar' => $user->agent->avatar,
                'position' => $user->agent->position,
                'phone' => $user->agent->phone,
                'whatsapp' => $user->agent->whatsapp,
            ];
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $agencyData = $data['agency'] ?? null;
        $agentData = $data['agent'] ?? null;
        unset($data['agency'], $data['agent']);

        $record->update($data);

        // Əgər Agentlik Sahibidirsə -> Agentlik məlumatlarını yenilə
        if ($record->isTenantOwner() && $agencyData !== null && $record->tenantAgency()) {
            $record->tenantAgency()->update($agencyData);
        }
        // Əgər Rieltordursa -> Rieltor məlumatlarını yenilə
        elseif ($record->agent && $agentData !== null) {
            $agentData = array_filter($agentData, fn ($value) => $value !== null);
            $record->agent->update($agentData);
        }

        return $record;
    }

    public function getLayoutData(): array
    {
        return [
            'hasTopbar' => $this->hasTopbar(),
            'maxWidth' => $this->getMaxWidth(),
        ];
    }
}
