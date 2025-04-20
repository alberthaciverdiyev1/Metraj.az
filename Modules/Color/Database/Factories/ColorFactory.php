<?php

namespace Modules\Color\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Color\Http\Entities\Color;

class ColorFactory extends Factory
{
    protected $model = Color::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->colorName(),
            "slug" => $this->faker->slug(),
            "code" => $this->faker->hexColor(),
            "is_active" => $this->faker->randomElement([true, false]),
            "image" => $this->faker->imageUrl(),
            "created_at" => $this->faker->dateTime(),
            "updated_at" => $this->faker->dateTime(),
        ];
    }
}
