<?php

namespace Modules\Feature\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Feature\Http\Entities\Feature;

class FeatureFactory extends Factory
{
    protected $model = Feature::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->name(),
            "slug" => $this->faker->slug(),
            "is_active" => $this->faker->boolean(true),
        ];
    }
}
