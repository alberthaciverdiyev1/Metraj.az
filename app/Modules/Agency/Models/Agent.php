<?php

namespace App\Modules\Agency\Models;

use App\Modules\Shared\Models\User;
use App\Modules\Property\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $agency_id
 * @property int|null $user_id
 * @property string|null $position
 * @property string|null $phone
 * @property string|null $whatsapp
 * @property string|null $avatar
 * @property string|null $banner
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string|null $avatar_url
 * @property-read string|null $banner_url
 * @property-read \App\Modules\Agency\Models\Agency|null $agency
 * @property-read \App\Modules\Shared\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Property\Models\Property> $properties
 */
class Agent extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kütləvi doldurula bilən sütunlar (Mass Assignable)
     */
    protected $fillable = [
        'agency_id',  // Aid olduğu agentliyin ID-si
        'user_id',    // Sistem istifadəçi hesabı ilə əlaqə ID-si
        'position',   // Vəzifəsi (Məs: Baş Rieltor, Satış Meneceri)
        'phone',      // Şəxsi / İş telefonu
        'whatsapp',   // WhatsApp nömrəsi
        'avatar',     // Profil şəkli
        'banner',     // Banner şəkli (Üzlük)
        'is_active',  // Aktivlik statusu (true/false)
    ];

    /**
     * Məlumat tiplərinin çevrilməsi
     */
    protected $casts = [
        'is_active' => 'boolean', // Aktivlik statusu
    ];

    /**
     * Avatarların tam URL-ni qaytarır (lokal storage və ya xarici link).
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://') || str_starts_with($this->avatar, '/')) {
            return $this->avatar;
        }

        return asset('storage/' . $this->avatar);
    }

    /**
     * Banner şəkillərinin tam URL-ni qaytarır (lokal storage və ya xarici link).
     */
    public function getBannerUrlAttribute(): ?string
    {
        if (empty($this->banner)) {
            return null;
        }

        if (str_starts_with($this->banner, 'http://') || str_starts_with($this->banner, 'https://') || str_starts_with($this->banner, '/')) {
            return $this->banner;
        }

        return asset('storage/' . $this->banner);
    }

    /**
     * Agentin aid olduğu şirkət / agentlik
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Agentin sistem istifadəçi hesabı
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Agentin cavabdeh olduğu əmlak elanları
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
