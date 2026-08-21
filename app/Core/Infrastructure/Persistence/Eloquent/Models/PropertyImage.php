<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyImage extends Model
{
    use HasFactory;

    /**
     * Elanda şəkil yoxdursa istifadə olunan standart şəkil.
     */
    public const FALLBACK_IMAGE = 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80';

    protected $fillable = [
        'property_id',
        'url',
        'sort_order',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get full image URL whether it's an external URL or uploaded local storage file
     */
    public function getUrlAttribute($value): string
    {
        if (empty($value)) {
            return self::FALLBACK_IMAGE;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '/')) {
            return $value;
        }

        return asset('storage/' . $value);
    }
}
