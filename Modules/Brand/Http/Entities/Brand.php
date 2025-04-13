<?php


namespace Modules\Brand\Http\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Brand\Database\Factories\BrandFactory;
use Modules\VehicleModel\Http\Entities\VehicleModel;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
    protected static function newFactory()
    {
        return BrandFactory::new();
    }

}
