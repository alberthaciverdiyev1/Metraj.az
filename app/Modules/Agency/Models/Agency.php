<?php

namespace App\Modules\Agency\Models;

use App\Modules\Agency\Enums\AgencyStatus;
use App\Modules\Shared\Models\User;
use App\Modules\Property\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Agency extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kütləvi doldurula bilən sütunlar (Mass Assignable)
     */
    protected $fillable = [
        'owner_id',     // Agentliyin sahibi / rəhbərinin istifadəçi ID-si
        'name',         // Agentliyin tam adı (Məs: Fox Real Estate MMC)
        'slug',         // URL üçün unikal slug
        'description',  // Agentlik haqqında ətraflı məlumat
        'logo',         // Loqo şəklinin fayl yolu / URL
        'banner',       // Profil banner şəkli
        'phone',        // Rəsmi əlaqə telefonu
        'whatsapp',     // Rəsmi WhatsApp nömrəsi
        'email',        // Rəsmi e-poçt ünvanı
        'website',      // Rəsmi veb-sayt linki
        'address',      // Fiziki ofis ünvanı
        'status',       // Agentliyin statusu (Təsdiq gözləyir, Aktiv, Dondurulub və s.)
        'is_verified',  // Rəsmi təsdiqlənmiş / yoxlanılmış agentlik nişanı
    ];

    /**
     * Məlumat tiplərinin çevrilməsi (Type Casting)
     */
    protected $casts = [
        'status' => AgencyStatus::class, // Agentlik statusu Enum-a çevrilir
        'is_verified' => 'boolean',      // Təsdiqlənmiş agentlik (true/false)
    ];

    /**
     * Model hadisələrinin qeydiyyatı
     */
    protected static function boot()
    {
        parent::boot();

        // Agentlik yaradılarkən avtomatik slug generasiyası
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name) . '-' . Str::random(5);
            }
        });
    }

    /**
     * Agentliyin rəhbəri / sahibi ilə əlaqə
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Agentliyə bağlı rieltorlar / agentlər siyahısı
     */
    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }

    /**
     * Agentliyin yerləşdirdiyi bütün əmlak elanları
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
