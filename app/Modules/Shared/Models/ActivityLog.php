<?php

namespace App\Modules\Shared\Models;

use App\Modules\Shared\Jobs\ProcessActivityLogJob;
use App\Modules\Shared\Services\GeoIpService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $country_code
 * @property string|null $country_name
 * @property string|null $city
 * @property string|null $region
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $isp
 * @property string|null $user_agent
 * @property string|null $device_type
 * @property string|null $browser
 * @property string|null $os
 * @property string|null $method
 * @property string|null $url
 * @property string|null $referer
 * @property string|null $action
 * @property string|null $model_type
 * @property int|null $model_id
 * @property array|null $payload
 * @property int|null $duration_ms
 * @property int|null $status_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Modules\Shared\Models\User|null $user
 */
class ActivityLog extends Model
{
    use HasFactory;

    protected $connection = 'logs';

    protected $with = ['user'];

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip_address',
        'country_code',
        'country_name',
        'city',
        'region',
        'latitude',
        'longitude',
        'isp',
        'user_agent',
        'device_type',
        'browser',
        'os',
        'method',
        'url',
        'referer',
        'action',
        'model_type',
        'model_id',
        'payload',
        'duration_ms',
        'status_code',
        'created_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'duration_ms' => 'integer',
        'status_code' => 'integer',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFlagEmojiAttribute(): string
    {
        return GeoIpService::flagEmoji($this->country_code);
    }

    public function getLocationTextAttribute(): string
    {
        $flag = $this->flag_emoji;
        $city = $this->city && $this->city !== 'Naməlum' ? $this->city : '';
        $country = $this->country_name ?: $this->country_code;

        if ($city && $country && $city !== $country) {
            return "{$flag} {$city}, {$country}";
        }

        return "{$flag} " . ($city ?: $country ?: 'Naməlum Məkan');
    }

    public function getHasCoordinatesAttribute(): bool
    {
        return !empty($this->latitude) && !empty($this->longitude) && abs($this->latitude) > 0.001;
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        if (!$this->has_coordinates) {
            return null;
        }

        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }

    /**
     * Dispatch an activity log asynchronously via queue/afterResponse (0ms delay to user).
     */
    public static function logAsync(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $payload = null,
        ?int $userId = null,
        ?int $statusCode = 200
    ): void {
        $request = request();

        $logData = [
            'user_id' => $userId ?? auth()->id(),
            'ip_address' => $request?->header('CF-Connecting-IP') ?? $request?->ip(),
            'cf_country' => $request?->header('CF-IPCountry'),
            'user_agent' => $request?->userAgent(),
            'method' => $request?->method() ?? 'CLI',
            'url' => $request?->fullUrl() ?? 'CLI',
            'referer' => $request?->header('referer'),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'payload' => $payload,
            'status_code' => $statusCode,
            'created_at' => now()->toDateTimeString(),
        ];

        try {
            dispatch(new ProcessActivityLogJob($logData))->afterResponse();
        } catch (\Throwable $e) {
            // Fallback immediate dispatch if outside HTTP context
            dispatch(new ProcessActivityLogJob($logData));
        }
    }
}
