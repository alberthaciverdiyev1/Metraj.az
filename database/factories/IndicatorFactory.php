<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Indicator\Http\Entities\Indicator;

class IndicatorFactory extends Factory
{
    protected $model = Indicator::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'name' => $this->faker->city,
            'icon' => $this->faker->imageUrl(),
        ];
    }
}
