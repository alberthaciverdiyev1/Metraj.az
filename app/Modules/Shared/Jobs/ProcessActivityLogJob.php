<?php

namespace App\Modules\Shared\Jobs;

use App\Modules\Shared\Models\ActivityLog;
use App\Modules\Shared\Services\GeoIpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessActivityLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 15;

    public function __construct(
        public array $logData
    ) {}

    public function handle(): void
    {
        try {
            $ip = $this->logData['ip_address'] ?? null;
            $cfCountry = $this->logData['cf_country'] ?? null;
            $userAgent = $this->logData['user_agent'] ?? null;

            // 1. Resolve Geo Location
            $geo = GeoIpService::resolve($ip, $cfCountry);

            // 2. Parse User Agent for Device & Browser
            $device = GeoIpService::parseUserAgent($userAgent);

            // 3. Create Activity Log in logs database
            ActivityLog::create([
                'user_id' => $this->logData['user_id'] ?? null,
                'ip_address' => $ip,
                'country_code' => $geo['country_code'] ?? null,
                'country_name' => $geo['country_name'] ?? null,
                'city' => $geo['city'] ?? null,
                'region' => $geo['region'] ?? null,
                'latitude' => $geo['latitude'] ?? null,
                'longitude' => $geo['longitude'] ?? null,
                'isp' => $geo['isp'] ?? null,
                'user_agent' => $userAgent,
                'device_type' => $device['device_type'] ?? null,
                'browser' => $device['browser'] ?? null,
                'os' => $device['os'] ?? null,
                'method' => $this->logData['method'] ?? 'GET',
                'url' => $this->logData['url'] ?? null,
                'referer' => $this->logData['referer'] ?? null,
                'action' => $this->logData['action'] ?? 'page_view',
                'model_type' => $this->logData['model_type'] ?? null,
                'model_id' => $this->logData['model_id'] ?? null,
                'payload' => $this->logData['payload'] ?? null,
                'duration_ms' => $this->logData['duration_ms'] ?? null,
                'status_code' => $this->logData['status_code'] ?? null,
                'created_at' => $this->logData['created_at'] ?? now(),
            ]);
        } catch (\Throwable $e) {
            Log::channel('single')->error('Failed to process activity log job: ' . $e->getMessage());
        }
    }
}
