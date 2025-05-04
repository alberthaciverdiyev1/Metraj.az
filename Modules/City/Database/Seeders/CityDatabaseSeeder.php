<?php

namespace Modules\City\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\City\Http\Entities\City;

class CityDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        City::factory()->count(100)->create();
    }
}
