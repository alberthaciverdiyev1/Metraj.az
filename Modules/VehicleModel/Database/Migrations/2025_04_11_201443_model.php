<?php

namespace Modules\y\VehicleModel\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('models');
    }
};
