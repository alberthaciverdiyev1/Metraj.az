<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Request Type: buy (Almaq istəyirəm), rent_monthly (Kirayə axtarıram), rent_daily (Günlük axtarıram), roommate_have (Otaq verirəm), roommate_need (Otaq axtarıram)
            $table->string('request_type')->default('buy')->index();
            
            // Əmlak Növü: Mənzil, Həyət evi, Villa, Torpaq, Obyekt, Ofis
            $table->string('property_type')->nullable()->index();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            
            // Budget / Price
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2);
            $table->string('currency', 10)->default('AZN');
            $table->boolean('bills_included')->default(false); // Kirayə / Otaq üçün
            
            // Rooms & Area (for purchase & rent)
            $table->string('rooms')->nullable(); // 1, 2, 3, 4, 5+
            $table->decimal('area_min', 8, 2)->nullable();
            $table->decimal('area_max', 8, 2)->nullable();
            
            // Location
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->string('location_note')->nullable(); // Metro, qəsəbə və ya nişangah
            
            // Specific Requirements
            $table->boolean('has_deed')->nullable(); // Kupçalı (Alqı-satqı üçün)
            $table->boolean('mortgage_eligible')->nullable(); // İpotekaya yararlı (Alqı-satqı üçün)
            $table->string('repair_status')->nullable(); // Təmirli, Təmirsiz, Fərqi yoxdur
            $table->string('furnished_status')->nullable(); // Əşyalı, Əşyasız, Fərqi yoxdur
            $table->string('occupancy_type')->nullable(); // Ailə, Tələbə, Tək, Şirkət
            
            // Roommate specific preferences
            $table->string('gender_preference')->nullable(); // any, female, male
            $table->string('occupation_preference')->nullable(); // any, student, working
            $table->boolean('smoker_allowed')->nullable();
            $table->boolean('pet_allowed')->nullable();
            $table->string('stay_duration')->nullable(); // Uzunmüddətli, 6 ay+
            $table->date('move_in_date')->nullable();
            $table->json('amenities')->nullable(); // Təchizatlar
            
            // Contact Details
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_whatsapp')->nullable();
            $table->string('contact_email')->nullable();
            
            // Status & Stats
            $table->string('status')->default('published')->index();
            $table->unsignedBigInteger('views_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('property_request_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_request_id')->constrained('property_requests')->cascadeOnDelete();
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_request_images');
        Schema::dropIfExists('property_requests');
    }
};
