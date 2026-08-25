<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quick_searches', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Çoxdilli: az, tr, en, ru
            $table->string('slug')->unique()->index();
            
            // Filtr parametrləri
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->string('deal_type')->nullable(); // sale, rent_monthly, rent_daily, rent
            $table->string('property_type')->nullable(); // apartment, villa, commercial, land, etc.
            $table->string('building_type')->nullable(); // new_building, old_building, etc.
            $table->string('repair_type')->nullable(); // repaired, without_repair, etc.
            $table->unsignedTinyInteger('rooms')->nullable(); // 1, 2, 3, 4, 5, etc.
            
            $table->decimal('min_price', 14, 2)->nullable();
            $table->decimal('max_price', 14, 2)->nullable();
            $table->unsignedInteger('min_area')->nullable();
            $table->unsignedInteger('max_area')->nullable();
            $table->unsignedInteger('min_land_area')->nullable();
            $table->unsignedInteger('max_land_area')->nullable();
            
            $table->boolean('has_document')->nullable();
            $table->boolean('has_mortgage')->nullable();
            $table->json('filter_options')->nullable(); // Dinamik parametr ID-ləri
            $table->string('custom_query')->nullable(); // Əlavə URL query parametrləri
            
            // SEO və Görünüş parametrləri
            $table->json('meta_description')->nullable();
            $table->boolean('is_popular')->default(true)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->unsignedBigInteger('view_count')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_searches');
    }
};
