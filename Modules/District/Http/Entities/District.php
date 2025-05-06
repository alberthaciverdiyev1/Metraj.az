<?php

namespace Modules\District\Http\Entities;

use Database\Factories\DistrictFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\City\Http\Entities\City;

class District extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'districts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'name',
        'city_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'city_id' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public static function newFactory(): DistrictFactory
    {
        return DistrictFactory::new();
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
