<?php

namespace App\Modules\Location\Models;

use App\Modules\Property\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $icon
 * @property string|null $category
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Property\Models\Property> $properties
 */
class Amenity extends Model
{
    use HasFactory, \App\Modules\Shared\Concerns\HasLocalizedName;

    /**
     * Kütləvi doldurula bilən sütunlar (Mass Assignable)
     */
    protected $fillable = [
        'name',      // Xüsusiyyətin çoxdilli adı: {"az": "...", "tr": "...", "en": "...", "ru": "..."}
        'icon',      // İkon adı (Məs: flame, truck, home)
        'category',  // Kateqoriya (Kommunal, Bina, İnteryer, Eksteryer)
        'is_active', // Aktivlik vəziyyəti (true/false)
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'localized_name',
    ];

    /**
     * Bu xüsusiyyətə malik bütün əmlaklar
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'property_amenity');
    }
}
