<?php


namespace Modules\Brand\Http\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\VehicleModel\Http\Entities\VehicleModel;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}
