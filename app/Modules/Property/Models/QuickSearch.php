<?php

namespace App\Modules\Property\Models;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class QuickSearch extends Model
{
    use HasFactory;

    protected $table = 'quick_searches';

    protected $fillable = [
        'title',
        'slug',
        'city_id',
        'district_id',
        'deal_type',
        'property_type',
        'building_type',
        'repair_type',
        'rooms',
        'min_price',
        'max_price',
        'min_area',
        'max_area',
        'min_land_area',
        'max_land_area',
        'has_document',
        'has_mortgage',
        'filter_options',
        'custom_query',
        'meta_description',
        'is_popular',
        'is_active',
        'sort_order',
        'view_count',
    ];

    protected $casts = [
        'title' => 'array',
        'meta_description' => 'array',
        'filter_options' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'has_document' => 'boolean',
        'has_mortgage' => 'boolean',
        'rooms' => 'integer',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'min_area' => 'integer',
        'max_area' => 'integer',
        'min_land_area' => 'integer',
        'max_land_area' => 'integer',
        'sort_order' => 'integer',
        'view_count' => 'integer',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function getLocalizedTitleAttribute(): string
    {
        $locale = app()->getLocale();
        if (is_array($this->title)) {
            return $this->title[$locale]
                ?? $this->title['az']
                ?? $this->title['tr']
                ?? $this->title['en']
                ?? reset($this->title)
                ?? '';
        }

        return (string) $this->title;
    }

    public function getLocalizedMetaDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        if (is_array($this->meta_description)) {
            return $this->meta_description[$locale]
                ?? $this->meta_description['az']
                ?? $this->meta_description['tr']
                ?? $this->meta_description['en']
                ?? null;
        }

        return $this->meta_description;
    }

    public function getUrlAttribute(): string
    {
        return url('/axtaris/' . $this->slug);
    }

    /**
     * Convert quick search parameters into a query array for listing filters.
     */
    public function toQueryParams(): array
    {
        $params = [];

        if (!empty($this->city_id)) {
            $params['cityId'] = (int) $this->city_id;
            $params['city_id'] = (int) $this->city_id;
        }

        if (!empty($this->district_id)) {
            $params['district_id'] = (int) $this->district_id;
            $params['districts'] = [(int) $this->district_id];
        }

        if (!empty($this->deal_type)) {
            $params['deal_type'] = $this->deal_type;
            if ($this->deal_type === 'sale') {
                $params['adType'] = 'sale';
            } elseif (in_array($this->deal_type, ['rent_monthly', 'rent_daily', 'rent'])) {
                $params['adType'] = 'rent';
                if ($this->deal_type === 'rent_daily') {
                    $params['rentType'] = 'daily';
                } elseif ($this->deal_type === 'rent_monthly') {
                    $params['rentType'] = 'monthly';
                }
            }
        }

        if (!empty($this->property_type)) {
            $params['property_type'] = $this->property_type;
            $params['buildingType'] = $this->property_type;
        }

        if (!empty($this->building_type)) {
            $params['building_type'] = $this->building_type;
        }

        if (!empty($this->repair_type)) {
            $params['repair_type'] = $this->repair_type;
            $params['propertyCondition'] = $this->repair_type;
        }

        if (!empty($this->rooms)) {
            $params['rooms'] = (int) $this->rooms;
            $params['roomCount'] = (int) $this->rooms;
        }

        if ($this->min_price !== null) {
            $params['minPrice'] = (float) $this->min_price;
            $params['min_price'] = (float) $this->min_price;
        }

        if ($this->max_price !== null) {
            $params['maxPrice'] = (float) $this->max_price;
            $params['max_price'] = (float) $this->max_price;
        }

        if ($this->min_area !== null) {
            $params['minArea'] = (int) $this->min_area;
            $params['min_area'] = (int) $this->min_area;
        }

        if ($this->max_area !== null) {
            $params['maxArea'] = (int) $this->max_area;
            $params['max_area'] = (int) $this->max_area;
        }

        if ($this->min_land_area !== null) {
            $params['fieldAreaMin'] = (int) $this->min_land_area;
            $params['min_land_area'] = (int) $this->min_land_area;
        }

        if ($this->max_land_area !== null) {
            $params['fieldAreaMax'] = (int) $this->max_land_area;
            $params['max_land_area'] = (int) $this->max_land_area;
        }

        if ($this->has_document !== null) {
            $params['has_document'] = $this->has_document ? 1 : 0;
            $params['hasDeed'] = $this->has_document ? 1 : 0;
        }

        if ($this->has_mortgage !== null) {
            $params['has_mortgage'] = $this->has_mortgage ? 1 : 0;
            $params['inCredit'] = $this->has_mortgage ? 1 : 0;
        }

        if (!empty($this->filter_options) && is_array($this->filter_options)) {
            $params['filter_options'] = $this->filter_options;
        }

        if (!empty($this->custom_query)) {
            parse_str($this->custom_query, $custom);
            $params = array_merge($params, $custom);
        }

        return $params;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('is_popular', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('view_count', 'desc');
    }
}
