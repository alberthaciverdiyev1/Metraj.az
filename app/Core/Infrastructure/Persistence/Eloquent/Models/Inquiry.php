<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kütləvi doldurula bilən sütunlar (Mass Assignable)
     */
    protected $fillable = [
        'property_id', // Müraciət edilən əmlakın ID-si
        'agency_id',   // Müraciət ünvanlanan agentliyin ID-si
        'agent_id',    // Təyin edilmiş rieltorun ID-si
        'user_id',     // Müraciət edən istifadəçinin ID-si (sistemdə qeydiyyatlıdırsa)
        'name',        // Müştərinin adı və soyadı
        'phone',       // Müştərinin əlaqə nömrəsi
        'email',       // Müştərinin e-poçt ünvanı
        'message',     // Müştərinin yazdığı mesaj / istək
        'type',        // Müraciət növü (Ümumi sorğu, Baxış istəyi, Qiymət təklifi)
        'status',      // Müraciətin icra vəziyyəti (Yeni, Əlaqə saxlanıldı, Baxış təyin edildi, Bağlandı)
        'notes',       // Rieltorun daxili qeydləri
    ];

    /**
     * Müraciət edilən əmlak
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Müraciətin aid olduğu agentlik
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Müraciətə cavabdeh olan rieltor
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Müraciət edən sistem istifadəçisi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
