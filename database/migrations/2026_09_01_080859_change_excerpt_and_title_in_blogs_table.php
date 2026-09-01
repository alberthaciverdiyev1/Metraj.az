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
        Schema::table('blogs', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->change();
            $table->text('cover_image')->nullable()->change();
            $table->text('title')->change();
            $table->text('meta_title')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('excerpt', 255)->nullable()->change();
            $table->string('cover_image', 255)->nullable()->change();
            $table->string('title', 255)->change();
            $table->string('meta_title', 255)->nullable()->change();
        });
    }
};
