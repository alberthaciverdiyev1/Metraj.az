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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('add_no')->nullable();
            $table->string('slug')->unique();
            $table->foreignId('town_id')->nullable()->constrained('towns')->nullOnDelete();
            $table->foreignId('subway_id')->nullable()->constrained('subways')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->string('address')->nullable();
            $table->string('property_type')->nullable();
            $table->string('add_type')->nullable();
            $table->integer('number_of_floors')->nullable();
            $table->integer('number_of_rooms')->nullable();
            $table->integer('floor_located')->nullable();
            $table->integer('area')->nullable();
            $table->integer('field_area')->nullable();
            $table->string('advertiser')->nullable();
            $table->string('advertiser_name')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('phone_3')->nullable();
            $table->string('phone_4')->nullable();
            $table->string('mail')->nullable();
            $table->text('description')->nullable();
            $table->boolean('in_credit')->default(false);
            $table->string('document')->nullable();
            $table->text('note_to_admin')->nullable();
            $table->string('building_type')->nullable();
            $table->boolean('has_video')->default(false);
            $table->string('google_map_location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_premium')->default(false);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('realtor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
