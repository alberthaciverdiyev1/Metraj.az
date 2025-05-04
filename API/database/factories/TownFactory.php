<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\District\Http\Entities\District;
use Modules\Town\Http\Entities\Town;

class TownFactory extends Factory
{
    protected $model = Town::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'name' => $this->faker->city,
            'district_id' => District::inRandomOrder()->first()->id,
        ];
    }
}
