<?php

namespace Database\Seeders;

use App\Core\Domain\Filter\Enums\FilterKey;
use App\Core\Domain\Property\Enums\PropertyStatus;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Agency;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Agent;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Amenity;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Filter;
use App\Core\Infrastructure\Persistence\Eloquent\Models\FilterOption;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Property;
use App\Core\Infrastructure\Persistence\Eloquent\Models\PropertyImage;
use App\Models\User;
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
            Amenity::create($item);
        }

        // ==========================================
        // 3.5. YERLƏŞMƏLƏR (ŞƏHƏRLƏR VƏ RAYONLAR - KUZEY KIBRIS)
        // ==========================================
        $this->call(NorthernCyprusLocationSeeder::class);

        // ==========================================
        // 4. DİNAMİK FİLTRLƏR
        // ==========================================

        // A. Alqı-satqı növü (deal_type)
        $dealTypeFilter = Filter::create([
            'key' => FilterKey::DealType,
            'name' => ['az' => 'Alqı-satqı növü', 'ru' => 'Тип сделки', 'en' => 'Deal Type'],
            'sort_order' => 1,
            'is_active' => true,
            'is_searchable' => true,
        ]);
        $optSale = FilterOption::create(['filter_id' => $dealTypeFilter->id, 'value' => 'sale', 'name' => ['az' => 'Alış', 'ru' => 'Купить', 'en' => 'Buy / Sale'], 'sort_order' => 1, 'is_active' => true]);
        $optRentMonthly = FilterOption::create(['filter_id' => $dealTypeFilter->id, 'value' => 'rent_monthly', 'name' => ['az' => 'Kirayə (Aylıq)', 'ru' => 'Аренда (Месячно)', 'en' => 'Rent (Monthly)'], 'sort_order' => 2, 'is_active' => true]);
        $optRentDaily = FilterOption::create(['filter_id' => $dealTypeFilter->id, 'value' => 'rent_daily', 'name' => ['az' => 'Kirayə (Günlük)', 'ru' => 'Аренда (Посуточно)', 'en' => 'Rent (Daily)'], 'sort_order' => 3, 'is_active' => true]);

        // C. Əmlakın növü (property_type)
        $propertyTypeFilter = Filter::create([
            'key' => FilterKey::PropertyType,
            'name' => ['az' => 'Əmlakın növü', 'ru' => 'Тип недвижимости', 'en' => 'Property Type'],
            'sort_order' => 3,
            'is_active' => true,
            'is_searchable' => true,
        ]);
        $optApartment = FilterOption::create(['filter_id' => $propertyTypeFilter->id, 'value' => 'apartment', 'name' => ['az' => 'Mənzil', 'ru' => 'Квартира', 'en' => 'Apartment'], 'sort_order' => 1, 'is_active' => true]);
        $optHouse = FilterOption::create(['filter_id' => $propertyTypeFilter->id, 'value' => 'house', 'name' => ['az' => 'Həyət evi / Bağ evi', 'ru' => 'Дом / Дача', 'en' => 'House / Villa'], 'sort_order' => 2, 'is_active' => true]);
        $optOffice = FilterOption::create(['filter_id' => $propertyTypeFilter->id, 'value' => 'office', 'name' => ['az' => 'Ofis', 'ru' => 'Офис', 'en' => 'Office'], 'sort_order' => 3, 'is_active' => true]);
        $optGarage = FilterOption::create(['filter_id' => $propertyTypeFilter->id, 'value' => 'garage', 'name' => ['az' => 'Qaraj', 'ru' => 'Гараж', 'en' => 'Garage'], 'sort_order' => 4, 'is_active' => true]);
        $optLand = FilterOption::create(['filter_id' => $propertyTypeFilter->id, 'value' => 'land', 'name' => ['az' => 'Torpaq', 'ru' => 'Земля', 'en' => 'Land'], 'sort_order' => 5, 'is_active' => true]);
        $optCommercial = FilterOption::create(['filter_id' => $propertyTypeFilter->id, 'value' => 'commercial', 'name' => ['az' => 'Obyekt', 'ru' => 'Коммерческий объект', 'en' => 'Commercial'], 'sort_order' => 6, 'is_active' => true]);

        // D. Tikilinin növü (building_type)
        $buildingTypeFilter = Filter::create([
            'key' => FilterKey::BuildingType,
            'name' => ['az' => 'Tikilinin növü', 'ru' => 'Тип постройки', 'en' => 'Building Type'],
            'sort_order' => 4,
            'is_active' => true,
            'is_searchable' => true,
        ]);
        $optNewBuilding = FilterOption::create(['filter_id' => $buildingTypeFilter->id, 'value' => 'new_building', 'name' => ['az' => 'Yeni tikili', 'ru' => 'Новостройка', 'en' => 'New Building'], 'sort_order' => 1, 'is_active' => true]);
        $optOldBuilding = FilterOption::create(['filter_id' => $buildingTypeFilter->id, 'value' => 'old_building', 'name' => ['az' => 'Köhnə tikili', 'ru' => 'Вторичка', 'en' => 'Old Building'], 'sort_order' => 2, 'is_active' => true]);

        // E. Təmir vəziyyəti (repair_type)
        $repairTypeFilter = Filter::create([
            'key' => FilterKey::RepairType,
            'name' => ['az' => 'Təmir', 'ru' => 'Ремонт', 'en' => 'Repair Status'],
            'sort_order' => 5,
            'is_active' => true,
            'is_searchable' => true,
        ]);
        $optRepaired = FilterOption::create(['filter_id' => $repairTypeFilter->id, 'value' => 'repaired', 'name' => ['az' => 'Təmirli', 'ru' => 'С ремонтом', 'en' => 'Repaired'], 'sort_order' => 1, 'is_active' => true]);
        $optUnrepaired = FilterOption::create(['filter_id' => $repairTypeFilter->id, 'value' => 'unrepaired', 'name' => ['az' => 'Təmirsiz', 'ru' => 'Без ремонта', 'en' => 'Unrepaired'], 'sort_order' => 2, 'is_active' => true]);

        // F. İstilik Sistemi (heating_system)
        $heatingFilter = Filter::create([
            'key' => FilterKey::HeatingSystem,
            'name' => ['az' => 'İstilik Sistemi', 'ru' => 'Система отопления', 'en' => 'Heating System'],
            'sort_order' => 6,
            'is_active' => true,
            'is_searchable' => true,
        ]);
        $fOptKombi = FilterOption::create(['filter_id' => $heatingFilter->id, 'value' => 'kombi', 'name' => ['az' => 'Kombi', 'ru' => 'Комби', 'en' => 'Combi'], 'sort_order' => 1, 'is_active' => true]);
        $fOptCentral = FilterOption::create(['filter_id' => $heatingFilter->id, 'value' => 'central', 'name' => ['az' => 'Mərkəzi İstilik', 'ru' => 'Центральное', 'en' => 'Central'], 'sort_order' => 2, 'is_active' => true]);
        $fOptFloorHeating = FilterOption::create(['filter_id' => $heatingFilter->id, 'value' => 'floor_heating', 'name' => ['az' => 'İsti döşəmə', 'ru' => 'Теплый пол', 'en' => 'Floor Heating'], 'sort_order' => 3, 'is_active' => true]);

        // G. Pəncərə Baxışı (window_view)
        $windowViewFilter = Filter::create([
            'key' => FilterKey::WindowView,
            'name' => ['az' => 'Pəncərə Baxışı', 'ru' => 'Вид из окон', 'en' => 'Window View'],
            'sort_order' => 7,
            'is_active' => true,
            'is_searchable' => true,
        ]);
        $fOptSeaView = FilterOption::create(['filter_id' => $windowViewFilter->id, 'value' => 'sea_view', 'name' => ['az' => 'Dənizə baxış (Panorama)', 'ru' => 'На море', 'en' => 'Sea view'], 'sort_order' => 1, 'is_active' => true]);
        $fOptCityView = FilterOption::create(['filter_id' => $windowViewFilter->id, 'value' => 'city_view', 'name' => ['az' => 'Şəhərə baxış', 'ru' => 'На город', 'en' => 'City view'], 'sort_order' => 2, 'is_active' => true]);
        $fOptYardView = FilterOption::create(['filter_id' => $windowViewFilter->id, 'value' => 'yard_view', 'name' => ['az' => 'Həyətə baxış', 'ru' => 'Во двор', 'en' => 'Yard view'], 'sort_order' => 3, 'is_active' => true]);

        // 5. Agentliklər və Rieltorlar (6 agentlik + 4 müstəqil rieltor)
        $this->call(AgencyAndAgentSeeder::class);

        // 5.1. Bloqlar (30 qısa məqalə)
        $this->call(BlogSeeder::class);

        $agency = Agency::where('slug', 'fox-real-estate')->firstOrFail();
        $agent = Agent::where('agency_id', $agency->id)->first();

        // 6. Sample Properties (FilterOptions və Subfilterlər ilə)
        $p1 = Property::create([
            'code' => '102450',
            'title' => 'Nəsimi rayonunda 3 otaqlı yeni tikili mənzil',
            'slug' => 'nasimi-rayonunda-3-otaqli-yeni-tikili-menzil',
            'description' => 'Mənzil tam təmirlidir, bütün əşyalarla birlikdə satılır. Geniş zal, 2 yataq otağı, ayrı mətbəx və 2 sanitar qovşağı var. Qaz, su, işıq daimidir. Kupça var, ipotekaya yararlıdır.',
            'has_document' => true,
            'has_mortgage' => true,
            'has_internal_credit' => false,
            'price' => 245000.00,
            'currency' => 'AZN',
            'area' => 125,
            'rooms' => 3,
            'floor' => 8,
            'total_floors' => 16,
            'landmark' => 'Port Baku, Sahil bağı yaxınlığı',
            'address' => 'Nizami küçəsi 45, Sahil m/s yaxınlığı',
            'latitude' => 40.3705,
            'longitude' => 49.8450,
            'agency_id' => $agency->id,
            'agent_id' => $agent->id,
            'user_id' => $agencyOwner->id,
            'seller_type' => SellerType::Agency,
            'status' => PropertyStatus::Published,
            'is_featured' => true,
            'is_vip' => true,
            'views_count' => 142,
        ]);
        $p1->amenities()->attach([1, 2, 3, 4, 5, 6, 7]);
        $p1->filterOptions()->attach([
            $baku->id,
            $nasimi->id,
            $optApartment->id,
            $optSale->id,
            $optNewBuilding->id,
            $optRepaired->id,
            $fOptKombi->id,
            $fOptSeaView->id,
        ]);
        PropertyImage::create(['property_id' => $p1->id, 'url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80', 'sort_order' => 1]);
        PropertyImage::create(['property_id' => $p1->id, 'url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80', 'sort_order' => 2]);

        $p2 = Property::create([
            'code' => '102451',
            'title' => 'Yasamal rayonunda 2 otaqlı kirayə mənzil',
            'slug' => 'yasamal-rayonunda-2-otaqli-kiraye-menzil',
            'description' => 'Tələbələr və ya gənc ailələr üçün ideal mənzil. Hər cür məişət texnikası ilə təchiz olunub. Əsas yola yaxın məsafədə.',
            'has_document' => true,
            'has_mortgage' => false,
            'has_internal_credit' => false,
            'price' => 850.00,
            'currency' => 'AZN',
            'area' => 70,
            'rooms' => 2,
            'floor' => 4,
            'total_floors' => 9,
            'landmark' => 'Universitet yaxınlığı, Park',
            'address' => 'H. Cavid prospekti',
            'latitude' => 40.3740,
            'longitude' => 49.8130,
            'agency_id' => null,
            'agent_id' => null,
            'user_id' => $admin->id,
            'seller_type' => SellerType::Owner,
            'status' => PropertyStatus::Published,
            'is_featured' => true,
            'is_vip' => false,
            'views_count' => 89,
        ]);
        $p2->amenities()->attach([1, 4, 6, 7, 8]);
        $p2->filterOptions()->attach([
            $baku->id,
            $yasamal->id,
            $optApartment->id,
            $optRentMonthly->id,
            $optOldBuilding->id,
            $optRepaired->id,
            $fOptCentral->id,
            $fOptCityView->id,
        ]);
        PropertyImage::create(['property_id' => $p2->id, 'url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80', 'sort_order' => 1]);

        $p3 = Property::create([
            'code' => '102452',
            'title' => 'Nərimanovda Premium Yaşayış Kompleksində 4 otaqlı podmayaq mənzil',
            'slug' => 'nerimanovda-premium-yasayis-kompleksinde-4-otaqli-podmayaq-menzil',
            'description' => 'Möhtəşəm layihə. Daxili faizsiz kreditlə və ya ipoteka ilə əldə etmək mümkündür. Qaz və çıxarış var.',
            'has_document' => true,
            'has_mortgage' => true,
            'has_internal_credit' => true,
            'price' => 320000.00,
            'currency' => 'AZN',
            'area' => 178,
            'rooms' => 4,
            'floor' => 12,
            'total_floors' => 18,
            'landmark' => 'Mərkəz yanı',
            'address' => 'Təbriz küçəsi 98',
            'latitude' => 40.3950,
            'longitude' => 49.8650,
            'agency_id' => $agency->id,
            'agent_id' => $agent->id,
            'user_id' => $agencyOwner->id,
            'seller_type' => SellerType::Complex,
            'status' => PropertyStatus::Published,
            'is_featured' => true,
            'is_vip' => true,
            'views_count' => 210,
        ]);
        $p3->amenities()->attach([1, 2, 3, 4, 5]);
        $p3->filterOptions()->attach([
            $baku->id,
            $narimanov->id,
            $optApartment->id,
            $optSale->id,
            $optNewBuilding->id,
            $optUnrepaired->id,
            $fOptFloorHeating->id,
            $fOptCityView->id,
        ]);
        PropertyImage::create(['property_id' => $p3->id, 'url' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80', 'sort_order' => 1]);
    }
}
