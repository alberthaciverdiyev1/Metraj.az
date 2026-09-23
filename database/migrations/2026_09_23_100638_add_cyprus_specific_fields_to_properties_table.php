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
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'city_id')) {
                $table->foreignId('city_id')->nullable()->after('rooms')->constrained('cities')->nullOnDelete();
            }
            if (!Schema::hasColumn('properties', 'district_id')) {
                $table->foreignId('district_id')->nullable()->after('city_id')->constrained('districts')->nullOnDelete();
            }

            $table->unsignedSmallInteger('bathrooms')->nullable()->after('district_id');
            $table->string('deed_type', 50)->nullable()->after('has_internal_credit'); // Koçan Türü (turkish, exchange, allocation, foreign)
            $table->string('furnished_status', 30)->nullable()->after('deed_type'); // Eşya Durumu (furnished, unfurnished, semi_furnished)
            $table->boolean('in_complex')->default(false)->after('furnished_status'); // Site İçerisinde (true/false)
            $table->string('building_age', 50)->nullable()->after('in_complex'); // Bina Yaşı (0, 1-5, under_construction)
            $table->boolean('exchangeable')->default(false)->after('building_age'); // Takas Durumu (true/false)
            $table->integer('zoning_ratio')->nullable()->after('exchangeable'); // İmar Oranı (%)
            $table->integer('floors_allowed')->nullable()->after('zoning_ratio'); // Kat İzni

            $table->index('city_id');
            $table->index('district_id');
            $table->index('deed_type');
            $table->index('furnished_status');
            $table->index('in_complex');
            $table->index('bathrooms');
        });

        if (!Schema::hasTable('property_filter_options')) {
            Schema::create('property_filter_options', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained()->cascadeOnDelete();
                $table->foreignId('filter_option_id')->constrained()->cascadeOnDelete();
                $table->unique(['property_id', 'filter_option_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_filter_options');

        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['city_id']);
            $table->dropIndex(['district_id']);
            $table->dropIndex(['deed_type']);
            $table->dropIndex(['furnished_status']);
            $table->dropIndex(['in_complex']);
            $table->dropIndex(['bathrooms']);

            $table->dropConstrainedForeignId('district_id');
            $table->dropConstrainedForeignId('city_id');

            $table->dropColumn([
                'bathrooms',
                'deed_type',
                'furnished_status',
                'in_complex',
                'building_age',
                'exchangeable',
                'zoning_ratio',
                'floors_allowed',
            ]);
        });
    }
};
