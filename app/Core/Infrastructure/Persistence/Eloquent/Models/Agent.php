<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'is_active',  // Aktivlik statusu (true/false)
    ];

    /**
     * Məlumat tiplərinin çevrilməsi
     */
    protected $casts = [
        'is_active' => 'boolean', // Aktivlik statusu
    ];

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
