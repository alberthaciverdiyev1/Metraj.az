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
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->foreignid('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('town_id')->nullable()->constrained('towns')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('subway_id')->nullable()->constrained('subways')->nullOnDelete();

            $table->string('ad_type')->nullable();
            $table->string('property_type')->nullable();
            $table->integer('number_of_rooms')->nullable();
            $table->integer('number_of_floors')->nullable();
            $table->boolean('in_credit')->default(false);
            $table->boolean('document')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
