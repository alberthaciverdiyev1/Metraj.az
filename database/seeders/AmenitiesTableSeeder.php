<?php

namespace Database\Seeders;

use App\Modules\Location\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            // ========================================================
            // 1. DIŞ ÖZELLİKLER & BİNA (Exterior & Building Facilities)
            // ========================================================
            [
                'name' => ['tr' => 'Asansör', 'az' => 'Lift', 'en' => 'Elevator', 'ru' => 'Лифт'],
                'icon' => 'bi-arrows-vertical',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Bahçe', 'az' => 'Həyət', 'en' => 'Garden', 'ru' => 'Сад'],
                'icon' => 'bi-tree',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Barbekü', 'az' => 'Barbekü / Manqal', 'en' => 'Barbecue', 'ru' => 'Барбекю'],
                'icon' => 'bi-fire',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Güvenlik Kamerası', 'az' => 'Təhlükəsizlik Kamerası', 'en' => 'Security Camera', 'ru' => 'Камера наблюдения'],
                'icon' => 'bi-camera-video',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Jeneratör', 'az' => 'Generator', 'en' => 'Generator', 'ru' => 'Генератор'],
                'icon' => 'bi-lightning-charge',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Kapalı Otopark', 'az' => 'Qapalı Qaraj', 'en' => 'Covered Parking', 'ru' => 'Крытая парковка'],
                'icon' => 'bi-car-front-fill',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Otopark', 'az' => 'Avtodayanacaq / Parkinq', 'en' => 'Parking', 'ru' => 'Парковка'],
                'icon' => 'bi-car-front',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Açık Otopark', 'az' => 'Açıq Parkinq', 'en' => 'Outdoor Parking', 'ru' => 'Открытая парковка'],
                'icon' => 'bi-car-front',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Ortak Havuz', 'az' => 'Ümumi Hovuz', 'en' => 'Communal Pool', 'ru' => 'Общий бассейн'],
                'icon' => 'bi-water',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Özel Havuz', 'az' => 'Şəxsi Hovuz', 'en' => 'Private Pool', 'ru' => 'Частный бассейн'],
                'icon' => 'bi-water',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Yüzme Havuzu', 'az' => 'Üzgüçülük Hovuzu', 'en' => 'Swimming Pool', 'ru' => 'Плавательный бассейн'],
                'icon' => 'bi-water',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Teras', 'az' => 'Terras', 'en' => 'Terrace', 'ru' => 'Терраса'],
                'icon' => 'bi-border-top',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Çocuk Oyun Alanı', 'az' => 'Uşaq Meydançası', 'en' => 'Playground', 'ru' => 'Детская площадка'],
                'icon' => 'bi-balloon',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => '7/24 Güvenlik', 'az' => '24/7 Mühafizə', 'en' => '24/7 Security', 'ru' => 'Круглосуточная охрана'],
                'icon' => 'bi-shield-check',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Site İçi', 'az' => 'Qapalı Yaşayış Kompleksi', 'en' => 'Gated Community', 'ru' => 'Закрытый комплекс'],
                'icon' => 'bi-houses',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Fitness / Spor Salonu', 'az' => 'Fitnes / İdman Zalı', 'en' => 'Gym / Fitness Center', 'ru' => 'Фитнес / Спортзал'],
                'icon' => 'bi-activity',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Tenis Kortu', 'az' => 'Tennis Kortu', 'en' => 'Tennis Court', 'ru' => 'Теннисный корт'],
                'icon' => 'bi-circle',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Basketbol Sahası', 'az' => 'Basketbol Meydançası', 'en' => 'Basketball Court', 'ru' => 'Баскетбольная площадка'],
                'icon' => 'bi-dribbble',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Sauna', 'az' => 'Sauna', 'en' => 'Sauna', 'ru' => 'Сауна'],
                'icon' => 'bi-thermometer-sun',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Türk Hamamı', 'az' => 'Türk Hamamı', 'en' => 'Turkish Bath', 'ru' => 'Турецкая баня'],
                'icon' => 'bi-droplet',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Su Deposu', 'az' => 'Su Çəni / Rezervuar', 'en' => 'Water Tank', 'ru' => 'Резервуар для воды'],
                'icon' => 'bi-bucket',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Hidrofor', 'az' => 'Hidrofor (Nasos)', 'en' => 'Booster Pump', 'ru' => 'Насосная станция'],
                'icon' => 'bi-gear',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Güneş Enerjisi', 'az' => 'Günəş Enerjisi / Su Qızdırıcı', 'en' => 'Solar Energy', 'ru' => 'Солнечная энергия'],
                'icon' => 'bi-sun',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Yangın Merdiveni', 'az' => 'Yanğın Nərdivanı', 'en' => 'Fire Escape', 'ru' => 'Пожарная лестница'],
                'icon' => 'bi-ladder',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Yangın Alarmı', 'az' => 'Yanğın Siqnalizasiyası', 'en' => 'Fire Alarm', 'ru' => 'Пожарная сигнализация'],
                'icon' => 'bi-bell',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Isı Yalıtımı', 'az' => 'İstilik İzolyasiyası', 'en' => 'Thermal Insulation', 'ru' => 'Теплоизоляция'],
                'icon' => 'bi-thermometer-half',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Ses Yalıtımı', 'az' => 'Səs İzolyasiyası', 'en' => 'Soundproofing', 'ru' => 'Звукоизоляция'],
                'icon' => 'bi-volume-mute',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Resepsiyon', 'az' => 'Qəbul Şöbəsi / Resepşn', 'en' => 'Reception', 'ru' => 'Ресепшн'],
                'icon' => 'bi-person-badge',
                'category' => 'Dış Özellikler',
            ],
            [
                'name' => ['tr' => 'Konsiyerj', 'az' => 'Konsyerj Xidməti', 'en' => 'Concierge', 'ru' => 'Консьерж'],
                'icon' => 'bi-key',
                'category' => 'Dış Özellikler',
            ],

            // ========================================================
            // 2. İÇ ÖZELLİKLER & DONANIM (Interior Features)
            // ========================================================
            [
                'name' => ['tr' => 'Balkon', 'az' => 'Balkon', 'en' => 'Balcony', 'ru' => 'Балкон'],
                'icon' => 'bi-window-sidebar',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Çamaşır Odası', 'az' => 'Camaşırxana Otağı', 'en' => 'Laundry Room', 'ru' => 'Прачечная'],
                'icon' => 'bi-basket',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Çelik Kapı', 'az' => 'Seyf Qapı', 'en' => 'Steel Security Door', 'ru' => 'Бронированная дверь'],
                'icon' => 'bi-door-closed',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Giyinme Odası', 'az' => 'Qarderob Otağı', 'en' => 'Dressing Room', 'ru' => 'Гардеробная'],
                'icon' => 'bi-gem',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Ebeveyn WC', 'az' => 'Valideyn Sanitar Qovşağı', 'en' => 'Master WC', 'ru' => 'Родительский санузел'],
                'icon' => 'bi-badge-wc',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Ebeveyn Banyosu', 'az' => 'Valideyn Hamamı', 'en' => 'En-suite Bathroom', 'ru' => 'Ванная в спальне'],
                'icon' => 'bi-droplet-half',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Şömine', 'az' => 'Kamin', 'en' => 'Fireplace', 'ru' => 'Камин'],
                'icon' => 'bi-fire',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Kiler', 'az' => 'Anbar / Kiler', 'en' => 'Pantry / Storage', 'ru' => 'Кладовая'],
                'icon' => 'bi-box-seam',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Ankastre Mutfak', 'az' => 'Quraşdırılmış Mətbəx', 'en' => 'Built-in Kitchen', 'ru' => 'Встроенная кухня'],
                'icon' => 'bi-grid-3x3',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Jakuzi', 'az' => 'Cakkuzi', 'en' => 'Jacuzzi', 'ru' => 'Джакузи'],
                'icon' => 'bi-water',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Klima', 'az' => 'Kondisioner', 'en' => 'Air Conditioning', 'ru' => 'Кондиционер'],
                'icon' => 'bi-wind',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Beyaz Eşya', 'az' => 'Məişət Texnikası', 'en' => 'White Goods / Appliances', 'ru' => 'Бытовая техника'],
                'icon' => 'bi-tv',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Mobilyalı / Eşyalı', 'az' => 'Mebel ilə birlikdə', 'en' => 'Furnished', 'ru' => 'С мебелью'],
                'icon' => 'bi-house-heart',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Parke Zemin', 'az' => 'Laminat / Parket Döşəmə', 'en' => 'Parquet Flooring', 'ru' => 'Паркетный пол'],
                'icon' => 'bi-square',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Seramik Zemin', 'az' => 'Metlax / Kafel Döşəmə', 'en' => 'Ceramic Flooring', 'ru' => 'Керамический пол'],
                'icon' => 'bi-grid',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Granit Mutfak Tezgahı', 'az' => 'Qranit Mətbəx Masası', 'en' => 'Granite Countertop', 'ru' => 'Гранитная столешница'],
                'icon' => 'bi-dash-square',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Spot Aydınlatma', 'az' => 'Spot İşıqlandırma', 'en' => 'Spot Lighting', 'ru' => 'Точечное освещение'],
                'icon' => 'bi-lightbulb',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Görüntülü Diyafon', 'az' => 'Video Domofon', 'en' => 'Video Intercom', 'ru' => 'Видеодомофон'],
                'icon' => 'bi-camera',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Çift Cam', 'az' => 'İkiqat Şüşə / Paket Şüşə', 'en' => 'Double Glazing', 'ru' => 'Двойной стеклопакет'],
                'icon' => 'bi-window',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Duşakabin', 'az' => 'Duş Kabinəsi', 'en' => 'Shower Cabin', 'ru' => 'Душевая кабина'],
                'icon' => 'bi-badge-ad',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Küvet', 'az' => 'Vanna', 'en' => 'Bathtub', 'ru' => 'Ванна'],
                'icon' => 'bi-circle-half',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Amerikan Mutfak', 'az' => 'Studiya / Açıq Mətbəx', 'en' => 'Open Plan Kitchen', 'ru' => 'Американская кухня'],
                'icon' => 'bi-layout-text-window',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Ayrı Mutfak', 'az' => 'Ayrı Mətbəx', 'en' => 'Separate Kitchen', 'ru' => 'Отдельная кухня'],
                'icon' => 'bi-layout-sidebar-inset',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Vestiyer', 'az' => 'Dəhliz Şkafı', 'en' => 'Cloakroom / Wardrobe', 'ru' => 'Прихожая'],
                'icon' => 'bi-bag',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Gömme Dolap', 'az' => 'Divar Şkafı', 'en' => 'Built-in Wardrobe', 'ru' => 'Встроенный шкаф'],
                'icon' => 'bi-inboxes',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Asma Tavan', 'az' => 'Asma Tavan', 'en' => 'Suspended Ceiling', 'ru' => 'Подвесной потолок'],
                'icon' => 'bi-layers',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'İnternet / Wi-Fi', 'az' => 'Yüksəksürətli İnternet / Wi-Fi', 'en' => 'High Speed Internet', 'ru' => 'Скоростной интернет'],
                'icon' => 'bi-wifi',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Akıllı Ev Sistemi', 'az' => 'Ağıllı Ev Sistemi', 'en' => 'Smart Home System', 'ru' => 'Умный дом'],
                'icon' => 'bi-cpu',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Yerden Isıtma', 'az' => 'İsti Döşəmə', 'en' => 'Floor Heating', 'ru' => 'Теплый пол'],
                'icon' => 'bi-thermometer',
                'category' => 'İç Özellikler',
            ],
            [
                'name' => ['tr' => 'Merkezi Isıtma', 'az' => 'Mərkəzi İstilik Sistemi', 'en' => 'Central Heating', 'ru' => 'Центральное отопление'],
                'icon' => 'bi-sun',
                'category' => 'İç Özellikler',
            ],

            // ========================================================
            // 3. KONUM & MANZARA ÖZELLİKLERİ (Location & Views)
            // ========================================================
            [
                'name' => ['tr' => 'Deniz Manzarası', 'az' => 'Dəniz Mənzərəsi', 'en' => 'Sea View', 'ru' => 'Вид на море'],
                'icon' => 'bi-water',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Dağ Manzarası', 'az' => 'Dağ Mənzərəsi', 'en' => 'Mountain View', 'ru' => 'Вид на горы'],
                'icon' => 'bi-triangle',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Doğa / Yeşillik Manzaralı', 'az' => 'Təbiət / Meşə Mənzərəsi', 'en' => 'Nature View', 'ru' => 'Вид на природу'],
                'icon' => 'bi-tree',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Şehir Manzarası', 'az' => 'Şəhər Mənzərəsi', 'en' => 'City View', 'ru' => 'Вид на город'],
                'icon' => 'bi-buildings',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Havuz Manzaralı', 'az' => 'Hovuz Mənzərəsi', 'en' => 'Pool View', 'ru' => 'Вид на бассейн'],
                'icon' => 'bi-droplet',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Şehir İçi', 'az' => 'Şəhər Mərkəzi', 'en' => 'In City Center', 'ru' => 'Центр города'],
                'icon' => 'bi-pin-map',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Denize Sıfır', 'az' => 'Dəniz Sahili / Birinci Xətt', 'en' => 'Beachfront', 'ru' => 'Первая линия у моря'],
                'icon' => 'bi-geo-alt',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Denize Yakın', 'az' => 'Dənizə Yaxın', 'en' => 'Close to Sea', 'ru' => 'Близко к морю'],
                'icon' => 'bi-geo',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Anayola Yakın', 'az' => 'Baş Yola Yaxın', 'en' => 'Close to Highway', 'ru' => 'Близко к трассе'],
                'icon' => 'bi-signpost',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Toplu Ulaşıma Yakın', 'az' => 'İctimai Nəqliyyata Yaxın', 'en' => 'Near Public Transit', 'ru' => 'Рядом с остановкой'],
                'icon' => 'bi-bus-front',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Üniversiteye Yakın', 'az' => 'Universitetə Yaxın', 'en' => 'Near University', 'ru' => 'Рядом с университетом'],
                'icon' => 'bi-mortarboard',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Hastaneye Yakın', 'az' => 'Xəstəxanaya Yaxın', 'en' => 'Near Hospital', 'ru' => 'Рядом с больницей'],
                'icon' => 'bi-hospital',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Alışveriş Merkezine / Markete Yakın', 'az' => 'Supermarketə Yaxın', 'en' => 'Near Shopping / Markets', 'ru' => 'Рядом с магазинами'],
                'icon' => 'bi-cart',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Havaalanına Yakın', 'az' => 'Hava Limanına Yaxın', 'en' => 'Near Airport', 'ru' => 'Близко к аэропорту'],
                'icon' => 'bi-airplane',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Kuzey Cepheli', 'az' => 'Şimal İstiqamətli', 'en' => 'North Facing', 'ru' => 'Северная сторона'],
                'icon' => 'bi-compass',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Güney Cepheli', 'az' => 'Cənub İstiqamətli', 'en' => 'South Facing', 'ru' => 'Южная сторона'],
                'icon' => 'bi-compass',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Doğu Cepheli', 'az' => 'Şərq İstiqamətli', 'en' => 'East Facing', 'ru' => 'Восточная сторона'],
                'icon' => 'bi-compass',
                'category' => 'Konum Özellikleri',
            ],
            [
                'name' => ['tr' => 'Batı Cepheli', 'az' => 'Qərb İstiqamətli', 'en' => 'West Facing', 'ru' => 'Западная сторона'],
                'icon' => 'bi-compass',
                'category' => 'Konum Özellikleri',
            ],
        ];

        foreach ($amenities as $item) {
            $existing = Amenity::where('name->tr', $item['name']['tr'])
                ->orWhere('name->az', $item['name']['az'])
                ->first();

            if ($existing) {
                $existing->update([
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                    'category' => $item['category'],
                    'is_active' => true,
                ]);
            } else {
                Amenity::create([
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                    'category' => $item['category'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
