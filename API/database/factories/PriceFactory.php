<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Price\Http\Entities\Price;

class PriceFactory extends Factory
{
    protected $model = Price::class;

    public function definition(): array
    {
        return [
//            "amount" => $this->faker->randomFloat(),
//            ""
        ];
    }
}
