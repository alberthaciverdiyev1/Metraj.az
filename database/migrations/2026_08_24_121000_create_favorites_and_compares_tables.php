<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('session_id', 100)->nullable()->index();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'property_id']);
            $table->index(['session_id', 'property_id']);
        });

        Schema::create('compares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('session_id', 100)->nullable()->index();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'property_id']);
            $table->index(['session_id', 'property_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compares');
        Schema::dropIfExists('favorites');
    }
};
