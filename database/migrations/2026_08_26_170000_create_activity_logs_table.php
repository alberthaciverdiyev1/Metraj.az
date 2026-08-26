<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('method', 10)->nullable();
            $table->text('url')->nullable();
            $table->string('action')->nullable(); // Məs: 'view', 'login', 'create', 'update', 'delete'
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('payload')->nullable(); // Request parameters, model changes, etc.
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
