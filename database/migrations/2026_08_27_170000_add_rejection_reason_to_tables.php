<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable();
        });

        Schema::table('property_requests', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable();
        });

        Schema::table('roommate_listings', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });

        Schema::table('property_requests', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });

        Schema::table('roommate_listings', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
