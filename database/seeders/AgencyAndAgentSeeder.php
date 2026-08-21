<?php

namespace Database\Seeders;

use App\Modules\Agency\Enums\AgencyStatus;
use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgencyAndAgentSeeder extends Seeder
{
    /**
     * Agentliklər və rieltorlar üçün seed.
     *
     * - 6 agentlik (hər birinin öz sahibi + rieltorları)
     * - 4 müstəqil rieltor (heç bir agentliyə bağlı deyil)
     *
     * Idempotent: updateOrCreate istifadə edir, təkrar işə salmaq təhlükəsizdir.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // ─────────────────────────────────────────────────────────────
        // AGENTLİKLƏR
        // ─────────────────────────────────────────────────────────────
        $agencies = [
            [
                'name' => 'Fox Real Estate MMC',
                'slug' => 'fox-real-estate',
                'owner' => ['name' => 'Fox Real Estate', 'email' => 'agency@metraj.az'],
                'description' => 'Bakı şəhərində 10 ildən artıq təcrübəyə malik peşəkar daşınmaz əmlak agentliyi. Alış, satış və kirayə üzrə tam xidmət.',
                'phone' => '+994 50 123 45 67',
                'whatsapp' => '+994 50 123 45 67',
                'email' => 'contact@foxestate.az',
                'website' => 'https://foxestate.az',
                'address' => 'Bakı ş., Nəsimi r., Nizami küç. 45',
                'is_verified' => true,
                'agents' => [
                    ['name' => 'Eldar Hüseynov', 'email' => 'eldar.huseynov@metraj.az', 'position' => 'Baş Rieltor', 'phone' => '+994 50 234 56 78', 'whatsapp' => '+994 50 234 56 78'],
                    ['name' => 'Nigar Əliyeva', 'email' => 'nigar.aliyeva@metraj.az', 'position' => 'Satış Meneceri', 'phone' => '+994 55 345 67 89', 'whatsapp' => '+994 55 345 67 89'],
                ],
            ],
            [
                'name' => 'AzEmlak Group',
                'slug' => 'azemlak-group',
                'owner' => ['name' => 'AzEmlak Group', 'email' => 'azemlak@metraj.az'],
                'description' => 'Bakı və Abşeronda geniş mənzil fondu. Yeni tikililər, ipoteka və daxili kredit təklifləri üzrə ixtisaslaşmış agentlik.',
                'phone' => '+994 12 456 78 90',
                'whatsapp' => '+994 55 111 22 33',
                'email' => 'info@azemlak.az',
                'website' => 'https://azemlak.az',
                'address' => 'Bakı ş., Nərimanov r., Təbriz küç. 12',
                'is_verified' => true,
                'agents' => [
                    ['name' => 'Ramin Məmmədov', 'email' => 'ramin.memmedov@metraj.az', 'position' => 'Baş Rieltor', 'phone' => '+994 50 456 78 90', 'whatsapp' => '+994 50 456 78 90'],
                    ['name' => 'Aynur Quliyeva', 'email' => 'aynur.quiliyeva@metraj.az', 'position' => 'Kirayə üzrə Mütəxəssis', 'phone' => '+994 70 567 89 01', 'whatsapp' => '+994 70 567 89 01'],
                    ['name' => 'Orxan Əsgərov', 'email' => 'orxan.askerov@metraj.az', 'position' => 'Rieltor', 'phone' => '+994 77 678 90 12', 'whatsapp' => '+994 77 678 90 12'],
                ],
            ],
            [
                'name' => 'Premium Estate Azerbaijan',
                'slug' => 'premium-estate-az',
                'owner' => ['name' => 'Premium Estate', 'email' => 'premium@metraj.az'],
                'description' => 'Luxury yaşayış kompleksləri və biznes mərkəzləri üzrə ixtisaslaşmış premium daşınmaz əmlak şirkəti.',
                'phone' => '+994 50 789 01 23',
                'whatsapp' => '+994 50 789 01 23',
                'email' => 'sales@premiumestate.az',
                'website' => 'https://premiumestate.az',
                'address' => 'Bakı ş., Yasamal r., H. Cavid prospekti 33',
                'is_verified' => true,
                'agents' => [
                    ['name' => 'Leyla Həsənova', 'email' => 'leyla.hasanova@metraj.az', 'position' => 'Satış Meneceri', 'phone' => '+994 55 890 12 34', 'whatsapp' => '+994 55 890 12 34'],
                    ['name' => 'Tural İsmayılov', 'email' => 'tural.ismayilov@metraj.az', 'position' => 'Rieltor', 'phone' => '+994 70 901 23 45', 'whatsapp' => '+994 70 901 23 45'],
                ],
            ],
            [
                'name' => 'Atlas Realty',
                'slug' => 'atlas-realty',
                'owner' => ['name' => 'Atlas Realty', 'email' => 'atlas@metraj.az'],
                'description' => 'Sumqayıt şəhəri və ətraf qəsəbələrdə fəaliyyət göstərən etibarlı daşınmaz əmlak agentliyi.',
                'phone' => '+994 18 65 43 21',
                'whatsapp' => '+994 55 222 33 44',
                'email' => 'info@atlasrealty.az',
                'website' => 'https://atlasrealty.az',
                'address' => 'Sumqayıt ş., N. Nərimanov pr. 77',
                'is_verified' => false,
                'agents' => [
                    ['name' => 'Günel Rəhimova', 'email' => 'gunel.rehimova@metraj.az', 'position' => 'Baş Rieltor', 'phone' => '+994 50 012 34 56', 'whatsapp' => '+994 50 012 34 56'],
                    ['name' => 'Elvin Səfərov', 'email' => 'elvin.seferov@metraj.az', 'position' => 'Rieltor', 'phone' => '+994 77 123 45 67', 'whatsapp' => '+994 77 123 45 67'],
                ],
            ],
            [
                'name' => 'Qərb Daşınmaz Əmlak',
                'slug' => 'qerb-dasnmaz-emlak',
                'owner' => ['name' => 'Qərb D.Ə.', 'email' => 'gerb@metraj.az'],
                'description' => 'Gəncə və Qərb bölgəsində mənzil, həyət evi və torpaq alqı-satqısı üzrə xidmət göstərən agentlik.',
                'phone' => '+994 22 12 34 56',
                'whatsapp' => '+994 55 333 44 55',
                'email' => 'info@qerbemlak.az',
                'website' => 'https://qerbemlak.az',
                'address' => 'Gəncə ş., Atatürk pr. 15',
                'is_verified' => false,
                'agents' => [
                    ['name' => 'Aysel Mustafayeva', 'email' => 'aysel.mustafayeva@metraj.az', 'position' => 'Baş Rieltor', 'phone' => '+994 50 234 56 01', 'whatsapp' => '+994 50 234 56 01'],
                    ['name' => 'Vüqar Abbasov', 'email' => 'vugar.abbasov@metraj.az', 'position' => 'Rieltor', 'phone' => '+994 55 345 67 12', 'whatsapp' => '+994 55 345 67 12'],
                ],
            ],
            [
                'name' => 'Caspian Property Partners',
                'slug' => 'caspian-property-partners',
                'owner' => ['name' => 'Caspian Partners', 'email' => 'caspian@metraj.az'],
                'description' => 'Dəniz kənarı premium layihələr və kommersiya obyektləri üzrə ixtisaslaşmış beynəlxalq daşınmaz əmlak şirkəti.',
                'phone' => '+994 12 987 65 43',
                'whatsapp' => '+994 70 444 55 66',
                'email' => 'hello@caspianpp.az',
                'website' => 'https://caspianpp.az',
                'address' => 'Bakı ş., Səbail r., Neftçilər pr. 5',
                'is_verified' => true,
                'agents' => [
                    ['name' => 'Sevinc Məlikova', 'email' => 'sevinc.melikova@metraj.az', 'position' => 'Baş Rieltor', 'phone' => '+994 50 456 78 01', 'whatsapp' => '+994 50 456 78 01'],
                    ['name' => 'Elnur Qasımov', 'email' => 'elnur.qasimov@metraj.az', 'position' => 'İpoteka üzrə Məsləhətçi', 'phone' => '+994 77 567 89 12', 'whatsapp' => '+994 77 567 89 12'],
                ],
            ],
        ];

        // ─────────────────────────────────────────────────────────────
        // MÜSTƏQİL RİELTORLAR (heç bir agentliyə bağlı deyil)
        // ─────────────────────────────────────────────────────────────
        $independentAgents = [
            ['name' => 'Samir Hümbətov', 'email' => 'samir.humbetov@metraj.az', 'position' => 'Müstəqil Rieltor', 'phone' => '+994 50 678 90 12', 'whatsapp' => '+994 50 678 90 12'],
            ['name' => 'Fidan Hüseynova', 'email' => 'fidan.huseynova@metraj.az', 'position' => 'Müstəqil Rieltor', 'phone' => '+994 55 789 01 23', 'whatsapp' => '+994 55 789 01 23'],
            ['name' => 'Cavid Babayev', 'email' => 'cavid.babayev@metraj.az', 'position' => 'Müstəqil Rieltor', 'phone' => '+994 70 890 12 34', 'whatsapp' => '+994 70 890 12 34'],
            ['name' => 'Könül Hacıyeva', 'email' => 'konul.haciyeva@metraj.az', 'position' => 'Müstəqil Rieltor', 'phone' => '+994 77 901 23 45', 'whatsapp' => '+994 77 901 23 45'],
        ];

        // ─────────────────────────────────────────────────────────────
        // AGENTLİKLƏRİ YARAD
        // ─────────────────────────────────────────────────────────────
        foreach ($agencies as $item) {
            // Sahib istifadəçi
            $owner = User::updateOrCreate(
                ['email' => $item['owner']['email']],
                ['name' => $item['owner']['name'], 'password' => $password]
            );

            // Agentlik
            $agency = Agency::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'owner_id' => $owner->id,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'phone' => $item['phone'],
                    'whatsapp' => $item['whatsapp'],
                    'email' => $item['email'],
                    'website' => $item['website'],
                    'address' => $item['address'],
                    'status' => AgencyStatus::Active,
                    'is_verified' => $item['is_verified'],
                ]
            );

            $this->command?->info("Agentlik: {$agency->name}");

            // Agentlik rieltorları
            foreach ($item['agents'] as $a) {
                $user = User::updateOrCreate(
                    ['email' => $a['email']],
                    ['name' => $a['name'], 'password' => $password]
                );

                Agent::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'agency_id' => $agency->id,
                        'position' => $a['position'],
                        'phone' => $a['phone'],
                        'whatsapp' => $a['whatsapp'],
                        'is_active' => true,
                    ]
                );
            }
        }

        // ─────────────────────────────────────────────────────────────
        // MÜSTƏQİL RİELTORLARI YARAD
        // ─────────────────────────────────────────────────────────────
        foreach ($independentAgents as $a) {
            $user = User::updateOrCreate(
                ['email' => $a['email']],
                ['name' => $a['name'], 'password' => $password]
            );

            Agent::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'agency_id' => null, // Müstəqil rieltor
                    'position' => $a['position'],
                    'phone' => $a['phone'],
                    'whatsapp' => $a['whatsapp'],
                    'is_active' => true,
                ]
            );

            $this->command?->info("Müstəqil rieltor: {$a['name']}");
        }
    }
}
