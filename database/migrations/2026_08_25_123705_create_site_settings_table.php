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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            // Əlaqə məlumatları (Contacts)
            $table->string('phone')->nullable();
            $table->string('phone_secondary')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('support_email')->nullable();
            $table->json('address')->nullable();

            // İş saatları (Working Hours)
            $table->string('working_hours_mon_fri')->nullable();
            $table->string('working_hours_sat')->nullable();
            $table->string('working_hours_sun')->nullable();

            // Xəritə koordinatları (Map Coordinates)
            $table->decimal('map_latitude', 10, 7)->nullable();
            $table->decimal('map_longitude', 10, 7)->nullable();

            // Sosial şəbəkələr (Social Links)
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('telegram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('twitter_url')->nullable();

            // Sayt & Footer mətnləri (Site & Footer Texts)
            $table->json('tagline')->nullable();
            $table->json('footer_description')->nullable();
            $table->string('copyright_text')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
