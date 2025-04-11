<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ad_id')->unsigned()->index();
            $table->string('slug')->unique();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->foreignId('model_id')->constrained('models')->cascadeOnDelete();
            $table->foreignId('color_id')->constrained('colors')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('dealer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('vin_code')->nullable();
            $table->integer('engine_capacity')->unsigned();
            $table->integer('engine_power')->unsigned();
            $table->tinyText('condition');
            $table->tinyText('phone_1');
            $table->tinyText('phone_2')->nullable();
            $table->tinyText('phone_3')->nullable();
            $table->tinyText('mail');
            $table->tinyText('fuel_type')->index();
            $table->tinyText('gear')->index();
            $table->tinyText('gearbox')->index();
            $table->tinyText('body_type')->index();
            $table->tinyText('assembled_market');
            $table->tinyInteger('seats')->unsigned();
            $table->integer('mileage')->default(0);
            $table->text('note_to_admin')->nullable();
            $table->boolean('barter_is_possible')->default(false);
            $table->boolean('is_credit')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
