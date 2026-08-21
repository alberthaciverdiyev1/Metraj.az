<?php

namespace App\Console\Commands;

use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use App\Modules\Location\Models\Amenity;
use App\Modules\Location\Models\FilterOption;
use App\Modules\Property\Models\Property;
use App\Modules\Property\Models\PropertyImage;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('app:seed-properties')]
#[Description('Seed 100 random realistic properties with images and filters')]
class SeedProperties extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting property seeding...');

        $agencies = Agency::where('status', 'active')->get();
        $agencyAgents = Agent::with('user')->whereNotNull('agency_id')->where('is_active', true)->get();
        $independentAgents = Agent::with('user')->whereNull('agency_id')->where('is_active', true)->get();
        $adminUser = User::where('email', 'admin@metraj.az')->first();
        $agencyOwner = User::where('email', 'agency@metraj.az')->first();

        if (!$adminUser || !$agencyOwner) {
            $this->error('Admin and Agency Owner users must exist. Please run DatabaseSeeder first.');
            return 1;
        }

        $this->info("Found {$agencies->count()} agencies, {$agencyAgents->count()} agency agents, {$independentAgents->count()} independent agents.");

        // Unsplash apartment and villa image pool
        $imagePool = [
            'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1527030280862-64139fbe04ca?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=800&q=80',
        ];

        $cities = \App\Modules\Location\Models\City::with('districts')->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name['az'] ?? $c->slug,
                'rayons' => $c->districts->map(fn ($d) => ['id' => $d->id, 'name' => $d->name['az'] ?? $d->slug])->toArray(),
            ];
        })->toArray();

        $dealTypes = [
            ['id' => 12, 'value' => 'sale', 'label' => 'Alış'],
            ['id' => 13, 'value' => 'rent_monthly', 'label' => 'Kirayə (Aylıq)'],
            ['id' => 14, 'value' => 'rent_daily', 'label' => 'Kirayə (Günlük)']
        ];

        $propertyTypes = [
            ['id' => 15, 'label' => 'Mənzil'],
            ['id' => 16, 'label' => 'Həyət evi / Bağ evi'],
            ['id' => 17, 'label' => 'Ofis'],
            ['id' => 18, 'label' => 'Qaraj'],
            ['id' => 19, 'label' => 'Torpaq'],
            ['id' => 20, 'label' => 'Obyekt']
        ];

        $buildingTypes = [21, 22]; // Yeni tikili, Köhnə tikili
        $conditions = [23, 24]; // Təmirli, Təmirsiz

        $landmarks = ['Milli Park yaxınlığı', '28 Mall yaxınlığı', 'Gənclik Mall yaxınlığı', 'Dəniz Mall yaxınlığı', 'Sahil bağı', 'Heydər Əliyev Mərkəzi', 'BDU yaxınlığı', 'Port Baku'];
        $streets = ['Nizami küçəsi', 'Təbriz küçəsi', 'H. Cavid prospekti', 'N. Nərimanov prospekti', 'S. Vurğun küçəsi', 'Füzuli küçəsi', 'Rəşid Behbudov küçəsi', 'A. Şaiq küçəsi'];

        for ($i = 0; $i < 1000; $i++) {
            $code = strval(rand(100000, 999999));

            // Randomize city
            $cityObj = $cities[array_rand($cities)];
            $cityName = $cityObj['name'];
            $cityId = $cityObj['id'];

            $rayonName = '';
            $rayonId = null;

            if ($cityId === 1 && !empty($cityObj['rayons'])) {
                $rayonObj = $cityObj['rayons'][array_rand($cityObj['rayons'])];
                $rayonName = $rayonObj['name'] . ' r.';
                $rayonId = $rayonObj['id'];
            }

            // Randomize deal type
            $dealObj = $dealTypes[array_rand($dealTypes)];
            $dealId = $dealObj['id'];
            $dealValue = $dealObj['value'];

            // Randomize property type
            $propObj = $propertyTypes[array_rand($propertyTypes)];
            $propId = $propObj['id'];
            $propLabel = $propObj['label'];

            // Price, area, rooms
            $rooms = rand(1, 5);
            $area = rand(40, 250);
            $isRent = str_contains($dealValue, 'rent');

            if ($isRent) {
                if ($dealValue === 'rent_daily') {
                    $price = rand(40, 250);
                } else {
                    $price = rand(400, 3000);
                }
            } else {
                $price = rand(70000, 650000);
            }

            $totalFloors = rand(5, 24);
            $floor = rand(1, $totalFloors);

            $street = $streets[array_rand($streets)];
            $streetNo = rand(1, 150);
            $landmark = $landmarks[array_rand($landmarks)];

            $address = $cityName . ($rayonName ? ', ' . $rayonName : '') . ', ' . $street . ' ' . $streetNo;
            $title = $cityName . ($rayonName ? ', ' . $rayonName : '') . ' - ' . $rooms . ' otaqlı ' . mb_strtolower($propLabel) . ' (' . $dealObj['label'] . ')';
            $slug = Str::slug($title) . '-' . uniqid();

            $hasDoc = rand(0, 1) === 1;
            $hasMortgage = !$isRent && rand(0, 1) === 1;
            $hasInternalCredit = !$isRent && rand(0, 1) === 1;

            $desc = "Təcili satılır/kirayə verilir! " . $rooms . " otaqlı, ümumi sahəsi " . $area . " m² olan geniş " . mb_strtolower($propLabel) . ". " .
                    "Yerləşdiyi yer əla infrastruktura malikdir: yaxınlığında məktəblər, uşaq bağçaları, ticarət mərkəzi və " . $landmark . " var. " .
                    "Mənzildə qaz, su, işıq və istilik sistemi daimidir. Əla təmirlidir və bütün zəruri yaşayış şəraiti ilə təmin olunmuşdur.";

            // Realtor link: 40% agentliyə bağlı rieltor, 30% müstəqil rieltor, 30% birbaşa sahib
            $roll = rand(1, 10);
            if ($roll <= 4 && $agencies->isNotEmpty() && $agencyAgents->isNotEmpty()) {
                $agency = $agencies->random();
                $agent = $agencyAgents->where('agency_id', $agency->id)->random();
                $propAgencyId = $agency->id;
                $propAgentId = $agent->id;
                $propUserId = $agent->user_id ?: ($agency->owner_id ?: $adminUser->id);
            } elseif ($roll <= 7 && $independentAgents->isNotEmpty()) {
                $agent = $independentAgents->random();
                $propAgencyId = null;
                $propAgentId = $agent->id;
                $propUserId = $agent->user_id ?: $adminUser->id;
            } else {
                $propAgencyId = null;
                $propAgentId = null;
                $propUserId = $adminUser->id;
            }

            $isComplex = rand(0, 10) < 2;
            $sellerType = $isComplex 
                ? \App\Modules\Property\Enums\SellerType::Complex 
                : ($propAgencyId ? \App\Modules\Property\Enums\SellerType::Agency : \App\Modules\Property\Enums\SellerType::Owner);

            $property = Property::create([
                'code' => $code,
                'title' => $title,
                'slug' => $slug,
                'description' => $desc,
                'has_document' => $hasDoc,
                'has_mortgage' => $hasMortgage,
                'has_internal_credit' => $hasInternalCredit,
                'price' => $price,
                'currency' => 'AZN',
                'area' => $area,
                'rooms' => $rooms,
                'floor' => $floor,
                'total_floors' => $totalFloors,
                'city_id' => $cityId,
                'district_id' => $rayonId,
                'landmark' => $landmark,
                'address' => $address,
                'latitude' => 40.35 + (rand(0, 1000) / 10000),
                'longitude' => 49.82 + (rand(0, 1000) / 10000),
                'agency_id' => $propAgencyId,
                'agent_id' => $propAgentId,
                'user_id' => $propUserId,
                'seller_type' => $sellerType,
                'status' => PropertyStatus::Published,
                'is_featured' => rand(0, 10) < 3,
                'is_vip' => rand(0, 10) < 2,
                'views_count' => rand(10, 800),
            ]);

            // 1. Attach amenities (random 3-6 amenities from 1-9)
            $amenityCount = rand(3, 6);
            $amenityIds = array_rand(range(1, 9), $amenityCount);
            $property->amenities()->attach(array_map(fn($id) => $id + 1, (array)$amenityIds));

            // 2. Attach Filter Options (Dynamic feature filters only)
            $attachOptions = [
                $dealId,
                $propId,
                $buildingTypes[array_rand($buildingTypes)],
                $conditions[array_rand($conditions)],
            ];

            $property->filterOptions()->attach($attachOptions);

            // 3. Create images
            $imgCount = rand(2, 5);
            $shuffledPool = $imagePool;
            shuffle($shuffledPool);
            for ($k = 0; $k < $imgCount; $k++) {
                PropertyImage::create([
                    'property_id' => $property->id,
                    'url' => $shuffledPool[$k],
                    'sort_order' => $k + 1
                ]);
            }
        }

        $this->info('Successfully seeded 100 properties!');
        return 0;
    }
}
