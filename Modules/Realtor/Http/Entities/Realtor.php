<?php

namespace Modules\Realtor\Http\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Realtor extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'realtors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'name',
        'address',
        'user_id',
        'city_id',
        'district_id',
        'subway_id',
        'town_id',
        'phone_1',
        'phone_2',
        'phone_3',
        'phone_4',
        'email',
        'is_active',
        'is_premium',
        'is_confirmed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
        'is_confirmed' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relations
     */

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function city()
    {
        return $this->belongsTo(\Modules\City\Http\Entities\City::class);
    }

    public function district()
    {
        return $this->belongsTo(\Modules\District\Http\Entities\District::class);
    }

    public function subway()
    {
        return $this->belongsTo(\Modules\Base\Http\Entities\Subway::class);
    }

    public function town()
    {
        return $this->belongsTo(\Modules\Town\Http\Entities\Town::class);
    }

    public function properties()
    {
        return $this->hasMany(\Modules\Property\Http\Entities\Property::class, 'realtor_id');
    }
}
