<?php

namespace App\Modules\Location\Models;

use App\Modules\Location\Enums\FilterKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filter extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kütləvi doldurula bilən sütunlar (Mass Assignable)
     */
    protected $fillable = [
        'key',           // Filtrin unikal açar sözü (FilterKey Enum-dan: location, property_type, deal_type, ...)
        'name',          // Filtrin adı (Çoxdilli JSON: {"az": "Yerləşmə", "ru": "Расположение"})
        'sort_order',    // Sıralama nömrəsi
        'is_active',     // Aktivlik vəziyyəti (true/false)
        'is_searchable', // Əsas axtarış blokunda göstərilsinmi (true/false)
    ];

    /**
     * Məlumat tiplərinin çevrilməsi
     */
    protected $casts = [
        'key' => FilterKey::class,       // Açar söz FilterKey Enum-a çevrilir
        'name' => 'array',              // Çoxdilli ad JSON formatında
        'is_active' => 'boolean',       // Aktivlik
        'is_searchable' => 'boolean',   // Axtarışa yararlıq
        'sort_order' => 'integer',      // Sıralama
    ];

    /**
     * Filtrin daxilindəki bütün seçimlər / bəndlər (Filter Options)
     */
    public function options(): HasMany
    {
        return $this->hasMany(FilterOption::class)->orderBy('sort_order', 'asc');
    }

    /**
     * Yalnız ən üst səviyyəli ana seçimlər (Root options: parent_id IS NULL)
     */
    public function rootOptions(): HasMany
    {
        return $this->hasMany(FilterOption::class)->whereNull('parent_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Yalnız aktiv ən üst səviyyəli ana seçimlər və onların alt budaqları
     */
    public function activeRootOptions(): HasMany
    {
        return $this->hasMany(FilterOption::class)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with('allChildren')
            ->orderBy('sort_order', 'asc');
    }

    /**
     * Yalnız aktiv seçimlər
     */
    public function activeOptions(): HasMany
    {
        return $this->hasMany(FilterOption::class)->where('is_active', true)->orderBy('sort_order', 'asc');
    }
}
