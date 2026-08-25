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
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();

            // Global Skriptlər (Raw HTML/JS)
            $table->mediumText('head_scripts')->nullable();
            $table->mediumText('body_scripts')->nullable();
            $table->mediumText('footer_scripts')->nullable();

            // Qlobal Standart Meta Məlumatlar
            $table->json('default_meta_title')->nullable();
            $table->json('default_meta_description')->nullable();
            $table->json('default_meta_keywords')->nullable();
            $table->string('og_image')->nullable();

            $table->timestamps();
        });

        Schema::create('page_seos', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->string('page_name');
            $table->string('route_name')->nullable()->index();
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->json('keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_seos');
        Schema::dropIfExists('seo_settings');
    }
};
