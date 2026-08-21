<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();      // Məs: "Bazar", "Məsləhət", "Xəbər"
            $table->string('cover_image')->nullable();   // Şəkil URL
            $table->string('excerpt')->nullable();       // Qısa mətn (kartda göstərilir)
            $table->text('content');                     // Tam məzmun (qısa saxlanılır)
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
