<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create cities table
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->jsonb('name'); // {"az": "Bakı", "ru": "Баку", "en": "Baku"}
            $table->string('slug')->index();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Create districts table
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->jsonb('name'); // {"az": "Yasamal", "ru": "Ясамал", "en": "Yasamal"}
            $table->string('slug')->index();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Add city_id and district_id to properties table
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->after('rooms')->constrained('cities')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('city_id')->constrained('districts')->nullOnDelete();
            $table->index(['city_id', 'district_id']);
        });

        // 4. Migrate existing data from filter_options
        $cityMap = []; // old_option_id => new_city_id
        $districtMap = []; // old_option_id => new_district_id

        $oldCities = DB::table('filter_options')
            ->where('filter_id', 1)
            ->whereNull('parent_id')
            ->get();

        foreach ($oldCities as $c) {
            $cityId = DB::table('cities')->insertGetId([
                'name' => $c->name,
                'slug' => $c->value,
                'sort_order' => $c->sort_order,
                'is_active' => $c->is_active,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $cityMap[$c->id] = $cityId;
        }

        $oldDistricts = DB::table('filter_options')
            ->where('filter_id', 1)
            ->whereNotNull('parent_id')
            ->get();

        foreach ($oldDistricts as $d) {
            $parentCityId = $cityMap[$d->parent_id] ?? null;
            if ($parentCityId) {
                $districtId = DB::table('districts')->insertGetId([
                    'city_id' => $parentCityId,
                    'name' => $d->name,
                    'slug' => $d->value,
                    'sort_order' => $d->sort_order,
                    'is_active' => $d->is_active,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $districtMap[$d->id] = $districtId;
            }
        }

        // Migrate property connections
        foreach ($cityMap as $oldCityId => $newCityId) {
            $propIds = DB::table('property_filter_options')
                ->where('filter_option_id', $oldCityId)
                ->pluck('property_id');

            if ($propIds->isNotEmpty()) {
                DB::table('properties')
                    ->whereIn('id', $propIds)
                    ->update(['city_id' => $newCityId]);
            }
        }

        foreach ($districtMap as $oldDistrictId => $newDistrictId) {
            $propIds = DB::table('property_filter_options')
                ->where('filter_option_id', $oldDistrictId)
                ->pluck('property_id');

            if ($propIds->isNotEmpty()) {
                DB::table('properties')
                    ->whereIn('id', $propIds)
                    ->update(['district_id' => $newDistrictId]);
            }
        }

        // Clean up old location filter & options
        DB::table('property_filter_options')
            ->whereIn('filter_option_id', array_merge(array_keys($cityMap), array_keys($districtMap)))
            ->delete();

        DB::table('filter_options')->where('filter_id', 1)->delete();
        DB::table('filters')->where('key', 'location')->delete();
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['city_id', 'district_id']);
        });

        Schema::dropIfExists('districts');
        Schema::dropIfExists('cities');
    }
};
