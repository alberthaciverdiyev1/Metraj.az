<?php

namespace Database\Seeders;

use App\Modules\Location\Models\City;
use App\Modules\Property\Models\QuickSearch;
use Illuminate\Database\Seeder;

class QuickSearchSeeder extends Seeder
{
    public function run(): void
    {
        $girne = City::where('slug', 'girne')->first();
        $lefkosa = City::where('slug', 'lefkosa')->first();
        $iskele = City::where('slug', 'iskele')->first();

        $searches = [
            [
                'title' => [
                    'az' => 'Girnədə yeni tikili 2+1 mənzillər',
                    'tr' => 'Girne’de yeni bina 2+1 daireler',
                    'en' => '2+1 new apartments in Kyrenia',
                    'ru' => 'Новостройки 2+1 квартиры в Гирне',
                ],
                'slug' => 'girnede-yeni-tikili-2-1-menziller',
                'city_id' => $girne?->id,
                'property_type' => 'apartment',
                'building_type' => 'new_building',
                'rooms' => 2,
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => [
                    'az' => 'Kiprdə ucuz evlər',
                    'tr' => 'Kıbrıs’ta uygun fiyatlı evler',
                    'en' => 'Affordable houses in Cyprus',
                    'ru' => 'Недорогие дома на Кипре',
                ],
                'slug' => 'kibrisda-ucuz-evler',
                'deal_type' => 'sale',
                'max_price' => 120000,
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => [
                    'az' => 'İskeledə lüks villalar',
                    'tr' => 'İskele’de lüks villalar',
                    'en' => 'Luxury villas in Iskele',
                    'ru' => 'Элитные виллы в Искеле',
                ],
                'slug' => 'iskelede-luks-villalar',
                'city_id' => $iskele?->id,
                'property_type' => 'villa',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => [
                    'az' => 'Lefkoşada kirayə mənzillər',
                    'tr' => 'Lefkoşa’da kiralık daireler',
                    'en' => 'Rental apartments in Nicosia',
                    'ru' => 'Квартиры в аренду в Никосии',
                ],
                'slug' => 'lefkosada-kiraye-menziller',
                'city_id' => $lefkosa?->id,
                'deal_type' => 'rent_monthly',
                'property_type' => 'apartment',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => [
                    'az' => 'Girnədə ipotekaya yararlı mənzillər',
                    'tr' => 'Girne’de krediye uygun daireler',
                    'en' => 'Mortgage eligible apartments in Kyrenia',
                    'ru' => 'Квартиры под ипотеку в Гирне',
                ],
                'slug' => 'girnede-ipotekaya-yararli-menziller',
                'city_id' => $girne?->id,
                'has_mortgage' => true,
                'property_type' => 'apartment',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($searches as $search) {
            QuickSearch::updateOrCreate(
                ['slug' => $search['slug']],
                $search
            );
        }
    }
}
