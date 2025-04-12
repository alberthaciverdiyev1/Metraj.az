<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Dealer\Http\Entities\Dealer;

class DealerFactory extends Factory
{
    protected $model = Dealer::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomFloat(),
            'address' => $this->faker->address(),
            'google_map_location' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
