<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Brand\Http\Entities\Brand;
use Modules\City\Http\Entities\City;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Brand::factory()->count(100)->create();
        City::factory()->count(100)->create();

    }
}
