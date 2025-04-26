<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Brand\Http\Entities\Brand;
use Modules\City\Http\Entities\City;
use Modules\Color\Http\Entities\Color;
use Modules\Feature\Http\Entities\Feature;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        City::factory()->count(100)->create();
        Feature::factory()->count(10)->create();


    }
}
