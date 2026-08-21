<?php

namespace App\Modules\Inquiry\Models;

use App\Modules\Shared\Models\User;
use App\Modules\Property\Models\Property;
use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $property_id
 * @property int|null $agency_id
 * @property int|null $agent_id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $message
 * @property string $type
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Modules\Property\Models\Property|null $property
 * @property-read \App\Modules\Agency\Models\Agency|null $agency
 * @property-read \App\Modules\Agency\Models\Agent|null $agent
 * @property-read \App\Modules\Shared\Models\User|null $user
 */
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
