<?php

namespace App\Modules\Property\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $listing_id
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ListingPhoneReveal extends Model
{
    use HasFactory;

    protected $table = 'listing_phone_reveals';

    protected $fillable = [
        'listing_id',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'listing_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
