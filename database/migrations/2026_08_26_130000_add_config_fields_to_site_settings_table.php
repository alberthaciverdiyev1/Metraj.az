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
            $table->unsignedInteger('listing_expiration_days')->default(30)->after('terms_of_use');
            $table->unsignedInteger('items_per_page')->default(30)->after('listing_expiration_days');
            $table->unsignedInteger('featured_limit')->default(10)->after('items_per_page');
            $table->unsignedInteger('vip_limit')->default(10)->after('featured_limit');
            $table->json('whatsapp_property_message')->nullable()->after('vip_limit');
            $table->json('whatsapp_roommate_message')->nullable()->after('whatsapp_property_message');
            $table->string('default_property_image')->nullable()->after('whatsapp_roommate_message');
            $table->string('default_blog_image')->nullable()->after('default_property_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'listing_expiration_days',
                'items_per_page',
                'featured_limit',
                'vip_limit',
                'whatsapp_property_message',
                'whatsapp_roommate_message',
                'default_property_image',
                'default_blog_image',
            ]);
        });
    }
};
