<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('address')->nullable();
            $table->string('google_map_url')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('payment_username')->nullable();
            $table->string('payment_password')->nullable();
            $table->string('otp_login')->nullable();
            $table->string('otp_password')->nullable();
            $table->string('otp_sender_name')->nullable();
            $table->boolean('is_offline')->default(false);

            $table->longText('about')->nullable();
            $table->longText('terms_and_conditions')->nullable();
            $table->longText('privacy_and_policy')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');

    }
};
