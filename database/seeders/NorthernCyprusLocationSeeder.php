<?php

namespace Database\Seeders;

use App\Modules\Location\Enums\FilterKey;
use App\Modules\Location\Models\Amenity;
use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Location\Models\Filter;
use App\Modules\Location\Models\FilterOption;
use App\Modules\Property\Models\Property;
use App\Modules\Property\Models\PropertyImage;
use App\Modules\Shared\Models\User;
use Illuminate\Database\Seeder;

class NorthernCyprusLocationSeeder extends Seeder
{
    /**
     * Run the database seeds for Northern Cyprus (KKTC) locations.
     *
     * Idempotent: updateOrCreate istifadə edir, təkrar işə salmaq təhlükəsizdir.
     */
    public function run(): void
    {
        // 1. Comprehensive Northern Cyprus Cities & Districts
        $locations = [
            [
                'name' => ['az' => 'Girne', 'tr' => 'Girne', 'en' => 'Kyrenia', 'ru' => 'Гирне (Кирения)'],
                'slug' => 'girne',
                'sort_order' => 1,
                'districts' => [
                    ['name' => ['az' => 'Girne Mərkəz', 'tr' => 'Girne Merkez', 'en' => 'Kyrenia Center', 'ru' => 'Центр Гирне'], 'slug' => 'girne-merkez'],
                    ['name' => ['az' => 'Alsancak', 'tr' => 'Alsancak', 'en' => 'Alsancak', 'ru' => 'Алсанджак'], 'slug' => 'alsancak'],
                    ['name' => ['az' => 'Lapta', 'tr' => 'Lapta', 'en' => 'Lapta', 'ru' => 'Лапта'], 'slug' => 'lapta'],
                    ['name' => ['az' => 'Çatalköy', 'tr' => 'Çatalköy', 'en' => 'Catalkoy', 'ru' => 'Чаталкой'], 'slug' => 'catalkoy'],
                    ['name' => ['az' => 'Esentepe', 'tr' => 'Esentepe', 'en' => 'Esentepe', 'ru' => 'Эсентепе'], 'slug' => 'esentepe'],
                    ['name' => ['az' => 'Karaoğlanoğlu', 'tr' => 'Karaoğlanoğlu', 'en' => 'Karaoglanoglu', 'ru' => 'Караогланоглу'], 'slug' => 'karaoglanoglu'],
                    ['name' => ['az' => 'Ozanköy', 'tr' => 'Ozanköy', 'en' => 'Ozankoy', 'ru' => 'Озанкой'], 'slug' => 'ozankoy'],
                    ['name' => ['az' => 'Beylerbeyi (Bellapais)', 'tr' => 'Beylerbeyi (Bellapais)', 'en' => 'Bellapais', 'ru' => 'Беллапаис'], 'slug' => 'bellapais'],
                    ['name' => ['az' => 'Karşıyaka', 'tr' => 'Karşıyaka', 'en' => 'Karsiyaka', 'ru' => 'Каршияка'], 'slug' => 'karsiyaka'],
                    ['name' => ['az' => 'Doğanköy', 'tr' => 'Doğanköy', 'en' => 'Dogankoy', 'ru' => 'Доганкой'], 'slug' => 'dogankoy'],
                    ['name' => ['az' => 'Zeytinlik', 'tr' => 'Zeytinlik', 'en' => 'Zeytinlik', 'ru' => 'Зейтинлик'], 'slug' => 'zeytinlik'],
                    ['name' => ['az' => 'Dikmen', 'tr' => 'Dikmen', 'en' => 'Dikmen', 'ru' => 'Дикмен'], 'slug' => 'dikmen'],
                    ['name' => ['az' => 'Bahçeli', 'tr' => 'Bahçeli', 'en' => 'Bahceli', 'ru' => 'Бахчели'], 'slug' => 'bahceli'],
                    ['name' => ['az' => 'Tatlısu', 'tr' => 'Tatlısu', 'en' => 'Tatlisu', 'ru' => 'Татлысу'], 'slug' => 'tatlisu'],
                ]
            ],
            [
                'name' => ['az' => 'Lefkoşa', 'tr' => 'Lefkoşa', 'en' => 'Nicosia', 'ru' => 'Лефкоша (Никосия)'],
                'slug' => 'lefkosa',
                'sort_order' => 2,
                'districts' => [
                    ['name' => ['az' => 'Lefkoşa Mərkəz', 'tr' => 'Lefkoşa Merkez', 'en' => 'Nicosia Center', 'ru' => 'Центр Лефкоша'], 'slug' => 'lefkosa-merkez'],
                    ['name' => ['az' => 'Gönyeli', 'tr' => 'Gönyeli', 'en' => 'Gonyeli', 'ru' => 'Гёньели'], 'slug' => 'gonyeli'],
                    ['name' => ['az' => 'Küçük Kaymaklı', 'tr' => 'Küçük Kaymaklı', 'en' => 'Kucuk Kaymakli', 'ru' => 'Кючюк Каймаклы'], 'slug' => 'kucuk-kaymakli'],
                    ['name' => ['az' => 'Ortaköy', 'tr' => 'Ortaköy', 'en' => 'Ortakoy', 'ru' => 'Ортакой'], 'slug' => 'ortakoy'],
                    ['name' => ['az' => 'Köşklüçiftlik / Kumsal', 'tr' => 'Köşklüçiftlik / Kumsal', 'en' => 'Kumsal', 'ru' => 'Кумсал'], 'slug' => 'kumsal'],
                    ['name' => ['az' => 'Marmara', 'tr' => 'Marmara', 'en' => 'Marmara', 'ru' => 'Мармара'], 'slug' => 'marmara'],
                    ['name' => ['az' => 'Yenişehir', 'tr' => 'Yenişehir', 'en' => 'Yenisehir', 'ru' => 'Енишехир'], 'slug' => 'yenisehir'],
                    ['name' => ['az' => 'Taşkınköy', 'tr' => 'Taşkınköy', 'en' => 'Taskinkoy', 'ru' => 'Ташкынкой'], 'slug' => 'taskinkoy'],
                    ['name' => ['az' => 'Hamitköy', 'tr' => 'Hamitköy', 'en' => 'Hamitkoy', 'ru' => 'Хамиткой'], 'slug' => 'hamitkoy'],
                    ['name' => ['az' => 'Değirmenlik', 'tr' => 'Değirmenlik', 'en' => 'Degirmenlik', 'ru' => 'Дегирменлик'], 'slug' => 'degirmenlik'],
                    ['name' => ['az' => 'Alayköy', 'tr' => 'Alayköy', 'en' => 'Alaykoy', 'ru' => 'Алайкой'], 'slug' => 'alaykoy'],
                    ['name' => ['az' => 'Haspolat', 'tr' => 'Haspolat', 'en' => 'Haspolat', 'ru' => 'Хасполат'], 'slug' => 'haspolat'],
                ]
            ],
            [
                'name' => ['az' => 'Gazimağusa', 'tr' => 'Gazimağusa', 'en' => 'Famagusta', 'ru' => 'Газимагуса (Фамагуста)'],
                'slug' => 'gazimagusa',
                'sort_order' => 3,
                'districts' => [
                    ['name' => ['az' => 'Gazimağusa Mərkəz', 'tr' => 'Gazimağusa Merkez', 'en' => 'Famagusta Center', 'ru' => 'Центр Фамагусты'], 'slug' => 'gazimagusa-merkez'],
                    ['name' => ['az' => 'Salamis / Yeni Boğaziçi', 'tr' => 'Salamis / Yeni Boğaziçi', 'en' => 'Yeni Bogazici', 'ru' => 'Ени Богазчи'], 'slug' => 'yeni-bogazici'],
                    ['name' => ['az' => 'Karakol', 'tr' => 'Karakol', 'en' => 'Karakol', 'ru' => 'Каракол'], 'slug' => 'karakol'],
                    ['name' => ['az' => 'Sakarya', 'tr' => 'Sakarya', 'en' => 'Sakarya', 'ru' => 'Сакарья'], 'slug' => 'sakarya'],
                    ['name' => ['az' => 'Gülseren', 'tr' => 'Gülseren', 'en' => 'Gulseren', 'ru' => 'Гюльсерен'], 'slug' => 'gulseren'],
                    ['name' => ['az' => 'Tuzla', 'tr' => 'Tuzla', 'en' => 'Tuzla', 'ru' => 'Тузла'], 'slug' => 'tuzla'],
                    ['name' => ['az' => 'Dumlupınar', 'tr' => 'Dumlupınar', 'en' => 'Dumlupinar', 'ru' => 'Думлупынар'], 'slug' => 'dumlupinar'],
                    ['name' => ['az' => 'Çanakkale', 'tr' => 'Çanakkale', 'en' => 'Canakkale', 'ru' => 'Чанаккале'], 'slug' => 'canakkale'],
                    ['name' => ['az' => 'Maraş', 'tr' => 'Maraş', 'en' => 'Maras', 'ru' => 'Мараш'], 'slug' => 'maras'],
                    ['name' => ['az' => 'Geçitkale', 'tr' => 'Geçitkale', 'en' => 'Gecitkale', 'ru' => 'Гечиткале'], 'slug' => 'gecitkale'],
                ]
            ],
            [
                'name' => ['az' => 'İskele', 'tr' => 'İskele', 'en' => 'Iskele (Trikomo)', 'ru' => 'Искеле (Трикомо)'],
                'slug' => 'iskele',
                'sort_order' => 4,
                'districts' => [
                    ['name' => ['az' => 'İskele Mərkəz', 'tr' => 'İskele Merkez', 'en' => 'Iskele Center', 'ru' => 'Центр Искеле'], 'slug' => 'iskele-merkez'],
                    ['name' => ['az' => 'Long Beach', 'tr' => 'Long Beach', 'en' => 'Long Beach', 'ru' => 'Лонг Бич'], 'slug' => 'long-beach'],
                    ['name' => ['az' => 'Boğaz', 'tr' => 'Boğaz', 'en' => 'Bogaz', 'ru' => 'Богаз'], 'slug' => 'bogaz'],
                    ['name' => ['az' => 'Bafra Turizm Bölgəsi', 'tr' => 'Bafra Turizm Bölgesi', 'en' => 'Bafra', 'ru' => 'Бафра'], 'slug' => 'bafra'],
                    ['name' => ['az' => 'Ötüken', 'tr' => 'Ötüken', 'en' => 'Otuken', 'ru' => 'Отукен'], 'slug' => 'otuken'],
                    ['name' => ['az' => 'Kumyalı', 'tr' => 'Kumyalı', 'en' => 'Kumyali', 'ru' => 'Кумьялы'], 'slug' => 'kumyali'],
                    ['name' => ['az' => 'Mehmetçik', 'tr' => 'Mehmetçik', 'en' => 'Mehmetcik', 'ru' => 'Мехметчик'], 'slug' => 'mehmetcik'],
                    ['name' => ['az' => 'Dipkarpaz', 'tr' => 'Dipkarpaz', 'en' => 'Dipkarpaz', 'ru' => 'Дипкарпаз'], 'slug' => 'dipkarpaz'],
                    ['name' => ['az' => 'Yenierenköy', 'tr' => 'Yenierenköy', 'en' => 'Yenierenkoy', 'ru' => 'Ениэренкёй'], 'slug' => 'yenierenkoy'],
                ]
            ],
            [
                'name' => ['az' => 'Güzelyurt', 'tr' => 'Güzelyurt', 'en' => 'Guzelyurt (Morphou)', 'ru' => 'Гюзельюрт (Морфу)'],
                'slug' => 'guzelyurt',
                'sort_order' => 5,
                'districts' => [
                    ['name' => ['az' => 'Güzelyurt Mərkəz', 'tr' => 'Güzelyurt Merkez', 'en' => 'Guzelyurt Center', 'ru' => 'Центр Гюзельюрт'], 'slug' => 'guzelyurt-merkez'],
                    ['name' => ['az' => 'Kalkanlı (ODTÜ)', 'tr' => 'Kalkanlı (ODTÜ)', 'en' => 'Kalkanli', 'ru' => 'Калканлы'], 'slug' => 'kalkanli'],
                    ['name' => ['az' => 'Bostancı', 'tr' => 'Bostancı', 'en' => 'Bostanci', 'ru' => 'Бостанджи'], 'slug' => 'bostanci'],
                    ['name' => ['az' => 'Yayla', 'tr' => 'Yayla', 'en' => 'Yayla', 'ru' => 'Яйла'], 'slug' => 'yayla'],
                    ['name' => ['az' => 'Zümrütköy', 'tr' => 'Zümrütköy', 'en' => 'Zumrutkoy', 'ru' => 'Зюмрюткой'], 'slug' => 'zumrutkoy'],
                    ['name' => ['az' => 'Akçay', 'tr' => 'Akçay', 'en' => 'Akcay', 'ru' => 'Акчай'], 'slug' => 'akcay'],
                    ['name' => ['az' => 'Aydınköy', 'tr' => 'Aydınköy', 'en' => 'Aydinkoy', 'ru' => 'Айдынкой'], 'slug' => 'aydinkoy'],
                ]
            ],
            [
                'name' => ['az' => 'Lefke', 'tr' => 'Lefke', 'en' => 'Lefka', 'ru' => 'Лефке'],
                'slug' => 'lefke',
                'sort_order' => 6,
                'districts' => [
                    ['name' => ['az' => 'Lefke Mərkəz', 'tr' => 'Lefke Merkez', 'en' => 'Lefke Center', 'ru' => 'Центр Лефке'], 'slug' => 'lefke-merkez'],
                    ['name' => ['az' => 'Gemikonağı (LAÜ)', 'tr' => 'Gemikonağı (LAÜ)', 'en' => 'Gemikonagi', 'ru' => 'Гемиконагы'], 'slug' => 'gemikonagi'],
                    ['name' => ['az' => 'Yedidalga', 'tr' => 'Yedidalga', 'en' => 'Yedidalga', 'ru' => 'Йедидалга'], 'slug' => 'yedidalga'],
                    ['name' => ['az' => 'Gaziveren', 'tr' => 'Gaziveren', 'en' => 'Gaziveren', 'ru' => 'Газиверен'], 'slug' => 'gaziveren'],
                    ['name' => ['az' => 'Bağlıköy', 'tr' => 'Bağlıköy', 'en' => 'Baglikoy', 'ru' => 'Баглыкой'], 'slug' => 'baglikoy'],
                    ['name' => ['az' => 'Yeşilyurt', 'tr' => 'Yeşilyurt', 'en' => 'Yesilyurt', 'ru' => 'Ешилюрт'], 'slug' => 'yesilyurt'],
                ]
            ]
        ];

        $createdCities = [];

        foreach ($locations as $loc) {
            $city = City::updateOrCreate(
                ['slug' => $loc['slug']],
                [
                    'name' => $loc['name'],
                    'sort_order' => $loc['sort_order'],
                    'is_active' => true,
                ]
            );
            $createdCities[] = $city;

            $dOrder = 1;
            foreach ($loc['districts'] as $dist) {
                District::updateOrCreate(
                    ['city_id' => $city->id, 'slug' => $dist['slug']],
                    [
                        'name' => $dist['name'],
                        'sort_order' => $dOrder++,
                        'is_active' => true,
                    ]
                );
            }
        }

        // 3. Seed realistic Northern Cyprus Sample Properties
        $samplePhotos = [
            'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
        ];

        $propertyTemplates = [
            [
                'title' => [
                    'az' => 'Girne Alsancakda Dəniz Mənzərəli 2+1 Lüks Mənzil',
                    'tr' => "Girne Alsancak'ta Deniz Manzaralı 2+1 Lüks Daire",
                    'en' => '2+1 Luxury Apartment with Sea View in Alsancak, Kyrenia',
                    'ru' => 'Роскошная 2+1 квартира с видом на море в Алсанджаке, Гирне',
                ],
                'type' => 'apartment', 'deal' => 'sale', 'price' => 125000, 'rooms' => 2, 'area' => 85, 'city_slug' => 'girne', 'dist_slug' => 'alsancak'
            ],
            [
                'title' => [
                    'az' => 'Girne Esentepe - Şəxsi Hovuzlu 3+1 Villa',
                    'tr' => "Girne Esentepe'de Özel Havuzlu 3+1 Villa",
                    'en' => '3+1 Luxury Villa with Private Pool in Esentepe, Kyrenia',
                    'ru' => '3+1 Вилла с частным бассейном в Эсентепе, Гирне',
                ],
                'type' => 'house', 'deal' => 'sale', 'price' => 280000, 'rooms' => 3, 'area' => 175, 'city_slug' => 'girne', 'dist_slug' => 'esentepe'
            ],
            [
                'title' => [
                    'az' => 'Lefkoşa Gönyelidə Geniş 3+1 Yeni Tikili Mənzil',
                    'tr' => "Lefkoşa Gönyeli'de Geniş 3+1 Sıfır Daire",
                    'en' => 'Spacious 3+1 New Build Apartment in Gonyeli, Nicosia',
                    'ru' => 'Просторная 3+1 новостройка в Гёньели, Никосия',
                ],
                'type' => 'apartment', 'deal' => 'sale', 'price' => 95000, 'rooms' => 3, 'area' => 120, 'city_slug' => 'lefkosa', 'dist_slug' => 'gonyeli'
            ],
            [
                'title' => [
                    'az' => 'Lefkoşa Küçük Kaymaklıda Aylıq Kirayə 2+1 Əşyalı Mənzil',
                    'tr' => "Lefkoşa Küçük Kaymaklı'da Aylık Kiralık 2+1 Mobilyalı Daire",
                    'en' => '2+1 Furnished Apartment for Monthly Rent in Kucuk Kaymakli, Nicosia',
                    'ru' => '2+1 Меблированная квартира в помесячную аренду в Кючюк Каймаклы, Никосия',
                ],
                'type' => 'apartment', 'deal' => 'rent_monthly', 'price' => 450, 'rooms' => 2, 'area' => 75, 'city_slug' => 'lefkosa', 'dist_slug' => 'kucuk-kaymakli'
            ],
            [
                'title' => [
                    'az' => 'İskele Long Beachdə Dənizə 200m Məsafədə 1+1 Studio',
                    'tr' => "İskele Long Beach'te Denize 200m Mesafede 1+1 Stüdyo",
                    'en' => '1+1 Studio 200m from the Sea in Long Beach, Iskele',
                    'ru' => '1+1 Студия в 200м от моря на Лонг Бич, Искеле',
                ],
                'type' => 'apartment', 'deal' => 'sale', 'price' => 85000, 'rooms' => 1, 'area' => 52, 'city_slug' => 'iskele', 'dist_slug' => 'long-beach'
            ],
            [
                'title' => [
                    'az' => 'İskele Boğazda Möhtəşəm Dəniz Panoramalı 4+1 Villa',
                    'tr' => "İskele Boğaz'da Muhteşem Deniz Manzaralı 4+1 Villa",
                    'en' => 'Stunning 4+1 Sea View Villa in Bogaz, Iskele',
                    'ru' => 'Великолепная 4+1 вилла с панорамным видом на море в Богазе, Искеле',
                ],
                'type' => 'house', 'deal' => 'sale', 'price' => 340000, 'rooms' => 4, 'area' => 240, 'city_slug' => 'iskele', 'dist_slug' => 'bogaz'
            ],
            [
                'title' => [
                    'az' => 'Gazimağusa Salamis Yolunda Tələbələr üçün 2+1 Mənzil',
                    'tr' => "Gazimağusa Salamis Yolunda Öğrencilere Uygun 2+1 Daire",
                    'en' => 'Student-Friendly 2+1 Apartment on Salamis Road, Famagusta',
                    'ru' => '2+1 Квартира для студентов на улице Саламис, Фамагуста',
                ],
                'type' => 'apartment', 'deal' => 'rent_monthly', 'price' => 400, 'rooms' => 2, 'area' => 70, 'city_slug' => 'gazimagusa', 'dist_slug' => 'karakol'
            ],
            [
                'title' => [
                    'az' => 'Gazimağusa Yeni Boğaziçidə Müstəqil Bağ Evi',
                    'tr' => "Gazimağusa Yeniboğaziçi'nde Müstakil Bahçeli Ev",
                    'en' => 'Detached House with Garden in Yeni Bogazici, Famagusta',
                    'ru' => 'Отдельный дом с садом в Ени Богазичи, Фамагуста',
                ],
                'type' => 'house', 'deal' => 'sale', 'price' => 195000, 'rooms' => 3, 'area' => 160, 'city_slug' => 'gazimagusa', 'dist_slug' => 'yeni-bogazici'
            ],
            [
                'title' => [
                    'az' => 'Güzelyurt Kalkanlıda ODTÜ Yaxınlığı İnvestisiya Mənzili',
                    'tr' => "Güzelyurt Kalkanlı'da ODTÜ Yakını Yatırımlık Daire",
                    'en' => 'Investment Apartment near METU in Kalkanli, Guzelyurt',
                    'ru' => 'Инвестиционная квартира рядом с METU в Калканлы, Гюзельюрт',
                ],
                'type' => 'apartment', 'deal' => 'sale', 'price' => 68000, 'rooms' => 2, 'area' => 80, 'city_slug' => 'guzelyurt', 'dist_slug' => 'kalkanli'
            ],
            [
                'title' => [
                    'az' => 'Lefke Gemikonağında Dəniz Kənarında 1+1 Rezidensiya',
                    'tr' => "Lefke Gemikonağı'nda Denize Sıfır 1+1 Rezidans",
                    'en' => 'Seaside 1+1 Residence in Gemikonagi, Lefke',
                    'ru' => '1+1 Резиденция на первой линии моря в Гемиконагы, Лефке',
                ],
                'type' => 'apartment', 'deal' => 'sale', 'price' => 62000, 'rooms' => 1, 'area' => 50, 'city_slug' => 'lefke', 'dist_slug' => 'gemikonagi'
            ],
            [
                'title' => [
                    'az' => 'Girne Mərkəzdə Premium Yaşayış Kompleksində Penthouse',
                    'tr' => "Girne Merkezde Premium Sitede Penthouse",
                    'en' => 'Luxury Penthouse in Prime Central Kyrenia Complex',
                    'ru' => 'Элитный пентхаус в премиальном жилом комплексе в центре Гирне',
                ],
                'type' => 'apartment', 'deal' => 'sale', 'price' => 210000, 'rooms' => 3, 'area' => 165, 'city_slug' => 'girne', 'dist_slug' => 'girne-merkez'
            ],
            [
                'title' => [
                    'az' => 'Girne Çatalköydə Bağlı Ərazidə 3+1 Müasir Villa',
                    'tr' => "Girne Çatalköy'de Site İçerisinde 3+1 Modern Villa",
                    'en' => 'Modern 3+1 Villa in Gated Community in Catalkoy, Kyrenia',
                    'ru' => 'Современная 3+1 вилла в закрытом комплексе в Чаталкёй, Гирне',
                ],
                'type' => 'house', 'deal' => 'sale', 'price' => 315000, 'rooms' => 3, 'area' => 190, 'city_slug' => 'girne', 'dist_slug' => 'catalkoy'
            ],
            [
                'title' => [
                    'az' => 'Lefkoşa Ortaköydə Ofis və ya Yaşayış üçün Əlverişli Mənzil',
                    'tr' => "Lefkoşa Ortaköy'de Ofis veya Konut Kullanımına Uygun Daire",
                    'en' => 'Versatile Office/Residential Apartment in Ortakoy, Nicosia',
                    'ru' => 'Квартира под офис или жилье в Ортакёй, Никосия',
                ],
                'type' => 'office', 'deal' => 'rent_monthly', 'price' => 600, 'rooms' => 3, 'area' => 110, 'city_slug' => 'lefkosa', 'dist_slug' => 'ortakoy'
            ],
            [
                'title' => [
                    'az' => 'İskele Bafra Turizm Bölgəsində Torpaq Sahəsi',
                    'tr' => "İskele Bafra Turizm Bölgesinde Satılık Arsa",
                    'en' => 'Plot of Land in Bafra Tourism Area, Iskele',
                    'ru' => 'Земельный участок в туристической зоне Бафра, Искеле',
                ],
                'type' => 'land', 'deal' => 'sale', 'price' => 150000, 'rooms' => null, 'area' => null, 'city_slug' => 'iskele', 'dist_slug' => 'bafra'
            ],
            [
                'title' => [
                    'az' => 'Gazimağusa Sakaryada Ticarət Mərkəzi Yanında Obyekt',
                    'tr' => "Gazimağusa Sakarya'da AVM Yanı Ticari Dükkan / İşyeri",
                    'en' => 'Commercial Property next to Mall in Sakarya, Famagusta',
                    'ru' => 'Коммерческое помещение рядом с ТЦ в Сакарья, Фамагуста',
                ],
                'type' => 'commercial', 'deal' => 'sale', 'price' => 175000, 'rooms' => null, 'area' => 130, 'city_slug' => 'gazimagusa', 'dist_slug' => 'sakarya'
            ],
        ];

        $cyprusCoords = [
            'girne' => ['lat' => 35.3382, 'lon' => 33.3186],
            'lefkosa' => ['lat' => 35.1856, 'lon' => 33.3823],
            'gazimagusa' => ['lat' => 35.1253, 'lon' => 33.9416],
            'iskele' => ['lat' => 35.2917, 'lon' => 33.8889],
            'guzelyurt' => ['lat' => 35.1989, 'lon' => 32.9936],
            'lefke' => ['lat' => 35.1167, 'lon' => 32.8500],
        ];

        $amenityIds = Amenity::pluck('id')->toArray();
        $adminUserId = User::where('email', 'admin@kibriskare.com')->first()?->id ?? 1;

        foreach ($propertyTemplates as $idx => $tpl) {
            $city = City::where('slug', $tpl['city_slug'])->first() ?? $createdCities[0];
            $district = District::where('slug', $tpl['dist_slug'])->first() ?? $city->districts->first();

            $baseCoords = $cyprusCoords[$city->slug] ?? ['lat' => 35.3382, 'lon' => 33.3186];
            $lat = (float) $baseCoords['lat'] + ($idx * 0.003 - 0.02);
            $lon = (float) $baseCoords['lon'] + ($idx * 0.004 - 0.02);

            $prices = app(\App\Modules\Shared\Services\CurrencyService::class)->convertFromGbp((float) $tpl['price']);
            $code = (string) (100500 + $idx);

            $azTitle = $tpl['title']['az'];
            $trTitle = $tpl['title']['tr'];
            $enTitle = $tpl['title']['en'];
            $ruTitle = $tpl['title']['ru'];

            $descriptions = [
                'az' => "<p><strong>{$azTitle}</strong></p><p>Şimali Kiprin ən prestijli və sürətlə inkişaf edən bölgəsində yerləşir. Bütün zəruri infrastruktur (məktəb, supermarketlər, çimərlik və restoranlar) yaxınlıqdadır. Əla investisiya və daimi yaşayış imkanı təqdim edir.</p><ul><li>Tam təchizatlı və yüksək keyfiyyətli materiallar</li><li>Dəniz və ya dağ panoraması</li><li>Rahat nəqliyyat əlçatanlığı</li></ul>",
                'tr' => "<p><strong>{$trTitle}</strong></p><p>Kuzey Kıbrıs'ın en prestijli ve hızla gelişen bölgesinde yer almaktadır. Tüm temel altyapı (okul, süpermarketler, plaj ve restoranlar) yürüme mesafesindedir. Harika bir yatırım ve daimi yaşam fırsatı sunmaktadır.</p><ul><li>Tam donanımlı ve yüksek kaliteli malzemeler</li><li>Deniz veya dağ manzarası</li><li>Kolay ulaşım imkanı</li></ul>",
                'en' => "<p><strong>{$enTitle}</strong></p><p>Located in the most prestigious and rapidly growing area of Northern Cyprus. All essential infrastructure (schools, supermarkets, beach and restaurants) is within close reach. Offers an excellent investment and permanent living opportunity.</p><ul><li>Fully equipped with high quality materials</li><li>Sea or mountain panoramic views</li><li>Easy transport accessibility</li></ul>",
                'ru' => "<p><strong>{$ruTitle}</strong></p><p>Расположен в самом престижном и быстро развивающемся районе Северного Кипра. Вся необходимая инфраструктура (школы, супермаркеты, пляж и рестораны) находится поблизости. Отличная возможность для инвестиций и постоянного проживания.</p><ul><li>Полная комплектация и высококачественные материалы</li><li>Панорамный вид на море или горы</li><li>Удобная транспортная доступность</li></ul>",
            ];

            $prop = Property::updateOrCreate(
                ['code' => $code],
                [
                    'user_id' => $adminUserId,
                    'slug' => \Illuminate\Support\Str::slug($azTitle) . '-' . $code,
                    'title' => $tpl['title'],
                    'description' => $descriptions,
                    'price' => $tpl['price'],
                    'currency' => 'GBP',
                    'prices' => $prices,
                    'area' => $tpl['area'],
                    'land_area' => ($tpl['type'] === 'land') ? 10 : null,
                    'rooms' => $tpl['rooms'],
                    'floor' => ($tpl['type'] === 'apartment') ? ($idx % 5 + 1) : 1,
                    'total_floors' => ($tpl['type'] === 'apartment') ? 6 : 2,
                    'city_id' => $city->id,
                    'district_id' => $district?->id,
                    'landmark' => 'Dəniz sahili və ya Mərkəz yaxınlığı',
                    'address' => "Kuzey Kıbrıs, " . ($city->name['tr'] ?? $city->name['az']) . ", " . ($district ? ($district->name['tr'] ?? $district->name['az']) : 'Merkez') . ", No " . ($idx + 12),
                    'latitude' => number_format($lat, 8, '.', ''),
                    'longitude' => number_format($lon, 8, '.', ''),
                    'has_document' => true,
                    'has_mortgage' => ($idx % 2 === 0),
                    'has_internal_credit' => ($idx % 3 === 0),
                    'seller_type' => ($idx % 3 === 0) ? \App\Modules\Property\Enums\SellerType::Agency : (($idx % 3 === 1) ? \App\Modules\Property\Enums\SellerType::Complex : \App\Modules\Property\Enums\SellerType::Owner),
                    'status' => \App\Modules\Property\Enums\PropertyStatus::Published,
                    'is_featured' => ($idx < 6),
                    'is_vip' => ($idx < 4),
                    'views_count' => rand(50, 450),
                ]
            );

            if (!empty($amenityIds)) {
                $prop->amenities()->sync(array_slice($amenityIds, 0, rand(4, count($amenityIds))));
            }

            // Property Type + Deal Type filter options
            $propertyTypeOption = FilterOption::whereHas('filter', function ($q) {
                $q->where('key', FilterKey::PropertyType->value);
            })->where('value', $tpl['type'])->first();

            $dealTypeOption = FilterOption::whereHas('filter', function ($q) {
                $q->where('key', FilterKey::DealType->value);
            })->where('value', $tpl['deal'])->first();

            $optionIds = array_filter([$propertyTypeOption?->id, $dealTypeOption?->id]);
            if (!empty($optionIds)) {
                $prop->filterOptions()->sync($optionIds);
            }

            // Add Images (idempotent — mövcud şəkillər silinir və yenidən yaradılır)
            $img1 = $samplePhotos[$idx % count($samplePhotos)];
            $img2 = $samplePhotos[($idx + 1) % count($samplePhotos)];
            $img3 = $samplePhotos[($idx + 2) % count($samplePhotos)];

            $prop->images()->delete();
            PropertyImage::create(['property_id' => $prop->id, 'url' => $img1, 'sort_order' => 0]);
            PropertyImage::create(['property_id' => $prop->id, 'url' => $img2, 'sort_order' => 1]);
            PropertyImage::create(['property_id' => $prop->id, 'url' => $img3, 'sort_order' => 2]);
        }
    }
}
