<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IndicatorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'name' => $this->faker->city,
            'icon' => $this->faker->imageUrl(),
        ];
    }
}
