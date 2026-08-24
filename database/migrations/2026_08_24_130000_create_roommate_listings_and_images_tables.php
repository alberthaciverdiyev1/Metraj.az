<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roommate_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Listing Type: have_room (Evim var, yoldaş axtarıram), need_room (Ev axtarıram, yoldaş axtarıram)
            $table->string('listing_type')->default('have_room')->index();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            
            $table->decimal('price', 10, 2);
            $table->string('currency', 10)->default('AZN');
            $table->boolean('bills_included')->default(false);
            
            // Location
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->string('location_note')->nullable(); // Metro, nişangah və ya ünvan
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            // Preferences & Rules
            // any, female, male
            $table->string('gender_preference')->default('any')->index();
            // any, student, working
            $table->string('occupation_preference')->default('any')->index();
            $table->boolean('smoker_allowed')->default(false);
            $table->boolean('pet_allowed')->default(false);
            $table->string('stay_duration')->nullable(); // Uzunmüddətli, 6 ay+ və s.
            $table->date('available_from')->nullable();
            $table->integer('total_roommates')->nullable(); // Evdə ümumi neçə nəfər qalacaq
            $table->json('amenities')->nullable(); // Wi-Fi, Kondisioner, Paltaryuyan və s.
            
            // Contact
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_whatsapp')->nullable();
            $table->string('contact_email')->nullable();
            
            // Status: pending, published, rejected, closed
            $table->string('status')->default('published')->index();
            $table->unsignedBigInteger('views_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roommate_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roommate_listing_id')->constrained('roommate_listings')->cascadeOnDelete();
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roommate_images');
        Schema::dropIfExists('roommate_listings');
    }
};
