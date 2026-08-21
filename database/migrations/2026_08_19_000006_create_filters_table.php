<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // FilterKey enum key (Məs: location, property_type, deal_type, ...)
            $table->jsonb('name'); // Dinamik çoxdilli ad: {"az": "Yerləşmə", "ru": "Расположение", "en": "Location"}
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_searchable')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('filter_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('filter_options')->cascadeOnDelete(); // Sonsuz alt-filtr / subfilter iyerarxiyası
            $table->string('value'); // Məs: baku, yasamal, elmler_akademiyasi
            $table->jsonb('name'); // Dinamik çoxdilli seçim adı: {"az": "Bakı"}
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id');
            $table->index(['filter_id', 'parent_id']);
        });

        Schema::create('property_filter_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('filter_option_id')->constrained()->cascadeOnDelete();
            $table->unique(['property_id', 'filter_option_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_filter_options');
        Schema::dropIfExists('filter_options');
        Schema::dropIfExists('filters');
    }
};
