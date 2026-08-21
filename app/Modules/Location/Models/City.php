<?php

namespace App\Modules\Location\Models;

use App\Modules\Property\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class)->orderBy('sort_order', 'asc');
    }

    public function activeDistricts(): HasMany
    {
        return $this->hasMany(District::class)->where('is_active', true)->orderBy('sort_order', 'asc');
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
