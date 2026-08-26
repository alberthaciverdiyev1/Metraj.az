<?php

namespace App\Modules\Shared\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $method
 * @property string|null $url
 * @property string|null $action
 * @property string|null $model_type
 * @property int|null $model_id
 * @property array|null $payload
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Modules\Shared\Models\User|null $user
 */
class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'method',
        'url',
        'action',
        'model_type',
        'model_id',
        'payload',
        'created_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper method to easily log an action.
     */
    public static function log(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $payload = null,
        ?int $userId = null
    ): self {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'payload' => $payload,
            'created_at' => now(),
        ]);
    }
}
