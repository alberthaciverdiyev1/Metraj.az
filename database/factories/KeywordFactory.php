<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Base\Http\Entities\Subway;
use Modules\City\Http\Entities\City;
use Modules\District\Http\Entities\District;
use Modules\Keyword\Http\Entities\Keyword;
use Modules\Town\Http\Entities\Town;

class KeywordFactory extends Factory
{
    protected $model = Keyword::class;
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'name' => $this->faker->word,
            'city_id' => City::inRandomOrder()->first()->id,
            'town_id' => Town::inRandomOrder()->first()->id,
            'district_id' => District::inRandomOrder()->first()->id,
            'subway_id' => Subway::inRandomOrder()->first()->id,
            'ad_type' => $this->faker->randomElement(['sale', 'rent']),
            'property_type' => $this->faker->randomElement(['apartment', 'house', 'villa']),
            'number_of_rooms' => $this->faker->numberBetween(1, 10),
            'number_of_floors' => $this->faker->numberBetween(1, 5),
            'in_credit' => $this->faker->boolean,
            'document' => $this->faker->boolean,
        ];
    }
}
