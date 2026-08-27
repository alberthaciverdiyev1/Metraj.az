<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'logs';

    public function up(): void
    {
        Schema::connection('logs')->table('activity_logs', function (Blueprint $table) {
            $table->string('country_code', 10)->nullable()->after('ip_address');
            $table->string('country_name', 100)->nullable()->after('country_code');
            $table->string('city', 100)->nullable()->after('country_name');
            $table->string('region', 100)->nullable()->after('city');
            $table->decimal('latitude', 10, 7)->nullable()->after('region');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('isp', 150)->nullable()->after('longitude');
            $table->string('device_type', 50)->nullable()->after('user_agent');
            $table->string('browser', 50)->nullable()->after('device_type');
            $table->string('os', 50)->nullable()->after('browser');
            $table->integer('duration_ms')->nullable()->after('payload');
            $table->integer('status_code')->nullable()->after('duration_ms');
            $table->text('referer')->nullable()->after('url');

            $table->index('country_code');
            $table->index('city');
            $table->index('action');
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::connection('logs')->table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['country_code']);
            $table->dropIndex(['city']);
            $table->dropIndex(['action']);
            $table->dropIndex(['model_type', 'model_id']);
            $table->dropIndex(['created_at']);

            $table->dropColumn([
                'country_code',
                'country_name',
                'city',
                'region',
                'latitude',
                'longitude',
                'isp',
                'device_type',
                'browser',
                'os',
                'duration_ms',
                'status_code',
                'referer',
            ]);
        });
    }
};
