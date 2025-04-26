<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('realtors', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('address')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('subway_id')->nullable()->constrained('subways')->nullOnDelete();
            $table->foreignId('town_id')->nullable()->constrained('towns')->nullOnDelete();

            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->string('phone_3')->nullable();
            $table->string('phone_4')->nullable();
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_confirmed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realtors');
    }
};
