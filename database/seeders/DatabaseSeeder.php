<?php

namespace Database\Seeders;

use App\Modules\Location\Enums\FilterKey;
use App\Modules\Location\Models\Amenity;
use App\Modules\Location\Models\Filter;
use App\Modules\Location\Models\FilterOption;
use App\Modules\Shared\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@metraj.az'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Agency Owner User
        $agencyOwner = User::firstOrCreate(
            ['email' => 'agency@metraj.az'],
            [
                'name' => 'Fox Real Estate',
                'password' => Hash::make('password'),
            ]
        );

        // 3. Amenities (Təchizatlar)
        $amenities = [
            ['name' => 'Qaz', 'icon' => 'flame', 'category' => 'utilities'],
            ['name' => 'Kupça (Çıxarış)', 'icon' => 'document', 'category' => 'document'],
            ['name' => 'İpotekaya yararlı', 'icon' => 'banknotes', 'category' => 'financial'],
            ['name' => 'Lift', 'icon' => 'arrows-up-down', 'category' => 'building'],
            ['name' => 'Avtodayanacaq / Parkinq', 'icon' => 'truck', 'category' => 'building'],
            ['name' => 'Mərkəzi istilik sistemi', 'icon' => 'sun', 'category' => 'utilities'],
            ['name' => 'Kondisioner', 'icon' => 'sparkles', 'category' => 'interior'],
            ['name' => 'Mebel ilə birlikdə', 'icon' => 'home', 'category' => 'interior'],
            ['name' => 'Balkon / Terras', 'icon' => 'view-columns', 'category' => 'exterior'],
        ];

        foreach ($amenities as $item) {
            Amenity::updateOrCreate(['name' => $item['name']], $item);
        }

        // ==========================================
        // 4. DİNAMİK FİLTRLƏR
        // (Lokasiya seeder'ından ƏVVƏL yaradılır ki, nümunə elanlar filtr seçimlərinə bağlana bilsin)
        // ==========================================

        // A. Alqı-satqı növü (deal_type)
        $dealTypeFilter = Filter::updateOrCreate(
            ['key' => FilterKey::DealType->value],
            ['name' => ['az' => 'Alqı-satqı növü', 'tr' => 'İşlem Türü', 'ru' => 'Тип сделки', 'en' => 'Deal Type'], 'sort_order' => 1, 'is_active' => true, 'is_searchable' => true]
        );
        $optSale = FilterOption::updateOrCreate(
            ['filter_id' => $dealTypeFilter->id, 'value' => 'sale'],
            ['name' => ['az' => 'Alış', 'tr' => 'Satılık', 'ru' => 'Купить', 'en' => 'Buy / Sale'], 'sort_order' => 1, 'is_active' => true]
        );
        $optRentMonthly = FilterOption::updateOrCreate(
            ['filter_id' => $dealTypeFilter->id, 'value' => 'rent_monthly'],
            ['name' => ['az' => 'Kirayə (Aylıq)', 'tr' => 'Kiralık (Aylık)', 'ru' => 'Аренда (Месячно)', 'en' => 'Rent (Monthly)'], 'sort_order' => 2, 'is_active' => true]
        );
        $optRentDaily = FilterOption::updateOrCreate(
            ['filter_id' => $dealTypeFilter->id, 'value' => 'rent_daily'],
            ['name' => ['az' => 'Kirayə (Günlük)', 'tr' => 'Kiralık (Günlük)', 'ru' => 'Аренда (Посуточно)', 'en' => 'Rent (Daily)'], 'sort_order' => 3, 'is_active' => true]
        );

        // C. Əmlakın növü (property_type)
        $propertyTypeFilter = Filter::updateOrCreate(
            ['key' => FilterKey::PropertyType->value],
            ['name' => ['az' => 'Əmlakın növü', 'tr' => 'Emlak Türü', 'ru' => 'Тип недвижимости', 'en' => 'Property Type'], 'sort_order' => 3, 'is_active' => true, 'is_searchable' => true]
        );
        $optApartment = FilterOption::updateOrCreate(
            ['filter_id' => $propertyTypeFilter->id, 'value' => 'apartment'],
            ['name' => ['az' => 'Mənzil', 'tr' => 'Daire', 'ru' => 'Квартира', 'en' => 'Apartment'], 'sort_order' => 1, 'is_active' => true]
        );
        $optHouse = FilterOption::updateOrCreate(
            ['filter_id' => $propertyTypeFilter->id, 'value' => 'house'],
            ['name' => ['az' => 'Həyət evi / Bağ evi', 'tr' => 'Müstakil Ev / Villa', 'ru' => 'Дом / Дача', 'en' => 'House / Villa'], 'sort_order' => 2, 'is_active' => true]
        );
        $optOffice = FilterOption::updateOrCreate(
            ['filter_id' => $propertyTypeFilter->id, 'value' => 'office'],
            ['name' => ['az' => 'Ofis', 'tr' => 'Ofis', 'ru' => 'Офис', 'en' => 'Office'], 'sort_order' => 3, 'is_active' => true]
        );
        $optGarage = FilterOption::updateOrCreate(
            ['filter_id' => $propertyTypeFilter->id, 'value' => 'garage'],
            ['name' => ['az' => 'Qaraj', 'tr' => 'Garaj', 'ru' => 'Гараж', 'en' => 'Garage'], 'sort_order' => 4, 'is_active' => true]
        );
        $optLand = FilterOption::updateOrCreate(
            ['filter_id' => $propertyTypeFilter->id, 'value' => 'land'],
            ['name' => ['az' => 'Torpaq', 'tr' => 'Arsa', 'ru' => 'Земля', 'en' => 'Land'], 'sort_order' => 5, 'is_active' => true]
        );
        $optCommercial = FilterOption::updateOrCreate(
            ['filter_id' => $propertyTypeFilter->id, 'value' => 'commercial'],
            ['name' => ['az' => 'Obyekt', 'tr' => 'Ticari / Dükkan', 'ru' => 'Коммерческий объект', 'en' => 'Commercial'], 'sort_order' => 6, 'is_active' => true]
        );

        // D. Tikilinin növü (building_type)
        $buildingTypeFilter = Filter::updateOrCreate(
            ['key' => FilterKey::BuildingType->value],
            ['name' => ['az' => 'Tikilinin növü', 'tr' => 'Bina Türü', 'ru' => 'Тип постройки', 'en' => 'Building Type'], 'sort_order' => 4, 'is_active' => true, 'is_searchable' => true]
        );
        $optNewBuilding = FilterOption::updateOrCreate(
            ['filter_id' => $buildingTypeFilter->id, 'value' => 'new_building'],
            ['name' => ['az' => 'Yeni tikili', 'tr' => 'Sıfır Bina', 'ru' => 'Новостройка', 'en' => 'New Building'], 'sort_order' => 1, 'is_active' => true]
        );
        $optOldBuilding = FilterOption::updateOrCreate(
            ['filter_id' => $buildingTypeFilter->id, 'value' => 'old_building'],
            ['name' => ['az' => 'Köhnə tikili', 'tr' => 'İkinci El', 'ru' => 'Вторичка', 'en' => 'Old Building'], 'sort_order' => 2, 'is_active' => true]
        );

        // E. Təmir vəziyyəti (repair_type)
        $repairTypeFilter = Filter::updateOrCreate(
            ['key' => FilterKey::RepairType->value],
            ['name' => ['az' => 'Təmir', 'tr' => 'Tadilat Durumu', 'ru' => 'Ремонт', 'en' => 'Repair Status'], 'sort_order' => 5, 'is_active' => true, 'is_searchable' => true]
        );
        $optRepaired = FilterOption::updateOrCreate(
            ['filter_id' => $repairTypeFilter->id, 'value' => 'repaired'],
            ['name' => ['az' => 'Təmirli', 'tr' => 'Tadilatlı', 'ru' => 'С ремонтом', 'en' => 'Repaired'], 'sort_order' => 1, 'is_active' => true]
        );
        $optUnrepaired = FilterOption::updateOrCreate(
            ['filter_id' => $repairTypeFilter->id, 'value' => 'unrepaired'],
            ['name' => ['az' => 'Təmirsiz', 'tr' => 'Tadilatsız', 'ru' => 'Без ремонта', 'en' => 'Unrepaired'], 'sort_order' => 2, 'is_active' => true]
        );

        // F. İstilik Sistemi (heating_system)
        $heatingFilter = Filter::updateOrCreate(
            ['key' => FilterKey::HeatingSystem->value],
            ['name' => ['az' => 'İstilik Sistemi', 'tr' => 'Isıtma Sistemi', 'ru' => 'Система отопления', 'en' => 'Heating System'], 'sort_order' => 6, 'is_active' => true, 'is_searchable' => true]
        );
        $fOptKombi = FilterOption::updateOrCreate(
            ['filter_id' => $heatingFilter->id, 'value' => 'kombi'],
            ['name' => ['az' => 'Kombi', 'tr' => 'Kombi', 'ru' => 'Комби', 'en' => 'Combi'], 'sort_order' => 1, 'is_active' => true]
        );
        $fOptCentral = FilterOption::updateOrCreate(
            ['filter_id' => $heatingFilter->id, 'value' => 'central'],
            ['name' => ['az' => 'Mərkəzi İstilik', 'tr' => 'Merkezi Isıtma', 'ru' => 'Центральное', 'en' => 'Central'], 'sort_order' => 2, 'is_active' => true]
        );
        $fOptFloorHeating = FilterOption::updateOrCreate(
            ['filter_id' => $heatingFilter->id, 'value' => 'floor_heating'],
            ['name' => ['az' => 'İsti döşəmə', 'tr' => 'Yerden Isıtma', 'ru' => 'Теплый пол', 'en' => 'Floor Heating'], 'sort_order' => 3, 'is_active' => true]
        );

        // G. Pəncərə Baxışı (window_view)
        $windowViewFilter = Filter::updateOrCreate(
            ['key' => FilterKey::WindowView->value],
            ['name' => ['az' => 'Pəncərə Baxışı', 'tr' => 'Manzara', 'ru' => 'Вид из окон', 'en' => 'Window View'], 'sort_order' => 7, 'is_active' => true, 'is_searchable' => true]
        );
        $fOptSeaView = FilterOption::updateOrCreate(
            ['filter_id' => $windowViewFilter->id, 'value' => 'sea_view'],
            ['name' => ['az' => 'Dənizə baxış (Panorama)', 'tr' => 'Deniz Manzaralı', 'ru' => 'На море', 'en' => 'Sea view'], 'sort_order' => 1, 'is_active' => true]
        );
        $fOptCityView = FilterOption::updateOrCreate(
            ['filter_id' => $windowViewFilter->id, 'value' => 'city_view'],
            ['name' => ['az' => 'Şəhərə baxış', 'tr' => 'Şehir Manzaralı', 'ru' => 'На город', 'en' => 'City view'], 'sort_order' => 2, 'is_active' => true]
        );
        $fOptYardView = FilterOption::updateOrCreate(
            ['filter_id' => $windowViewFilter->id, 'value' => 'yard_view'],
            ['name' => ['az' => 'Həyətə baxış', 'tr' => 'Avlu / Bahçe Manzaralı', 'ru' => 'Во двор', 'en' => 'Yard view'], 'sort_order' => 3, 'is_active' => true]
        );

        // ==========================================
        // 5. YERLƏŞMƏLƏR (ŞƏHƏRLƏR VƏ RAYONLAR) + NÜMUNƏ ELANLAR (KUZEY KIBRIS)
        // ==========================================
        $this->call(NorthernCyprusLocationSeeder::class);

        // ==========================================
        // 6. Agentliklər və Rieltorlar (6 agentlik + 4 müstəqil rieltor)
        // ==========================================
        $this->call(AgencyAndAgentSeeder::class);

        // ==========================================
        // 7. Bloqlar (30 qısa məqalə)
        // ==========================================
        $this->call(BlogSeeder::class);
    }
}
