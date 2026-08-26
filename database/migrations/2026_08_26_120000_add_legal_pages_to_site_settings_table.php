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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->json('user_agreement')->nullable()->after('copyright_text');
            $table->json('privacy_policy')->nullable()->after('user_agreement');
            $table->json('terms_of_use')->nullable()->after('privacy_policy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['user_agreement', 'privacy_policy', 'terms_of_use']);
        });
    }
};
