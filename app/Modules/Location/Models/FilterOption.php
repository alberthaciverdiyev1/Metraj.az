<?php

namespace App\Modules\Location\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $filter_id
 * @property int|null $parent_id
 * @property string $value
 * @property array<string, string> $name
 * @property string|null $icon
 * @property int $sort_order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $hierarchical_name
 * @property-read \App\Modules\Location\Models\Filter $filter
 * @property-read \App\Modules\Location\Models\FilterOption|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Location\Models\FilterOption> $children
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Property\Models\Property> $properties
 */
use App\Modules\Shared\Concerns\HasLocalizedName;

class FilterOption extends Model
{
    use HasFactory, SoftDeletes, HasLocalizedName;

    /**
     * Kütləvi doldurula bilən sütunlar (Mass Assignable)
     */
    protected $fillable = [
        'filter_id',     // Aid olduğu filtrin ID-si
        'parent_id',     // Üst filtr seçiminin ID-si (Sonsuz subfilter iyerarxiyası üçün)
        'value',         // Seçimin unikal dəyəri / slug-ı (Məs: baku, yasamal, elmler_akademiyasi)
        'name',          // Seçimin adı (Çoxdilli JSON: {"az": "Bakı", "ru": "Баку"})
        'icon',          // Seçimin ikon adı
        'sort_order',    // Sıralama nömrəsi
        'is_active',     // Aktivlik vəziyyəti (true/false)
    ];

    /**
     * Məlumat tiplərinin çevrilməsi
     */
    protected $casts = [
        'name' => 'array',        // Çoxdilli ad JSON formatında
        'is_active' => 'boolean', // Aktivlik
        'sort_order' => 'integer',// Sıralama
    ];

    /**
     * Aid olduğu ana filtr
     */
    public function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class);
    }

    /**
     * Üst filtr seçimi (Parent Option)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Birbaşa alt seçimlər (Direct Sub-options / Children)
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Rekursiv sonsuz alt seçimlər (Recursive Infinite Children)
     */
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Yalnız aktiv alt seçimlər
     */
    public function activeChildren(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->where('is_active', true)->orderBy('sort_order', 'asc');
    }

    /**
     * Bu seçimə malik olan bütün əmlaklar
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'property_filter_options');
    }

    /**
     * Tam iyerarxik adı qaytarır (Məs: Bakı > Yasamal > Elmlər m/s)
     */
    public function getHierarchicalNameAttribute(): string
    {
        $azName = $this->name['az'] ?? $this->value;
        if ($this->parent) {
            return $this->parent->hierarchical_name . ' ➔ ' . $azName;
        }
        return $azName;
    }
}
