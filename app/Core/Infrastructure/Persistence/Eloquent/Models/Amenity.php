<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use HasFactory;

    /**
     * Kütləvi doldurula bilən sütunlar (Mass Assignable)
     */
    protected $fillable = [
        'name',      // Xüsusiyyətin adı (Məs: Qaz, Lift, Parkinq, Mərkəzi istilik)
        'icon',      // İkon adı (Məs: flame, truck, home)
        'category',  // Kateqoriya (Kommunal, Bina, İnteryer, Eksteryer)
        'is_active', // Aktivlik vəziyyəti (true/false)
    ];

    /**
     * Bu xüsusiyyətə malik bütün əmlaklar
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'property_amenity');
    }
}
