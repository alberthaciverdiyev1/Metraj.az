<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\District\Http\Entities\District;
use Modules\User\Http\Entities\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'name' => $this->faker->city,
            'district_id' => District::inRandomOrder()->first()->id,
        ];
    }
}
