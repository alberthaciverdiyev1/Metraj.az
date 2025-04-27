<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\City\Http\Entities\City;
use Modules\District\Http\Entities\District;

class DistrictFactory extends Factory
{
    protected $model = District::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'name' => $this->faker->city,
            'city_id' => City::inRandomOrder()->first()->id,
        ];
    }
}
