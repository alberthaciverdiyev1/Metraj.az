<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('address');
            $table->string('google_map_location');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down():void{
        Schema::dropIfExists('dealers');
    }
};
