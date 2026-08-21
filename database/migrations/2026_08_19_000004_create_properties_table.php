<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->index(); // Elanın unikal nömrəsi
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Qiymət və Maliyyə statusları
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('AZN');
            $table->boolean('has_document')->default(false); // Çıxarış var (Kupça)
            $table->boolean('has_mortgage')->default(false); // İpoteka var
            $table->boolean('has_internal_credit')->default(false); // Daxili kredit

            // Ölçü və Mərtəbə
            $table->unsignedInteger('area')->nullable();
            $table->unsignedInteger('land_area')->nullable();
            $table->unsignedSmallInteger('rooms')->nullable();
            $table->unsignedSmallInteger('floor')->nullable();
            $table->unsignedSmallInteger('total_floors')->nullable();

            // Nişangah, Dəqiq Ünvan və Koordinatlar
            $table->string('landmark')->nullable(); // Nişangah (Məs: Port Baku yanı)
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Əlaqə və Sahiblik
            $table->foreignId('agency_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Status və Statistika
            $table->string('status')->default('pending_approval');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_vip')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // İndekslər
            $table->index(['price', 'area', 'rooms', 'floor']);
            $table->index(['has_document', 'has_mortgage', 'has_internal_credit']);
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_vip');
        });

        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('url'); // Şəkil linki/path
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('property_amenity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->unique(['property_id', 'amenity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_amenity');
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('properties');
    }
};
