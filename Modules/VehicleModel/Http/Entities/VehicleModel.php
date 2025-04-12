<?php


namespace Modules\VehicleModel\Http\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Brand\Http\Entities\Brand;

class VehicleModel extends Model
{
    protected $table = 'models';
    protected $fillable = [
        'name',
        'model_id',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
