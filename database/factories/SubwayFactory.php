<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Base\Http\Entities\Subway;

class SubwayFactory extends Factory
{
    protected $model = Subway::class;
    public function definition(): array
    {
        return [
            'slug'=> $this->faker->unique()->slug(),
            'name'=> $this->faker->unique()->name(),
        ];
    }
}
