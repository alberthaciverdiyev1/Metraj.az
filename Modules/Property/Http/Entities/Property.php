<?php

namespace Modules\Property\Http\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'properties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'add_no',
        'slug',
        'town_id',
        'subway_id',
        'district_id',
        'city_id',
        'address',
        'property_type',
        'add_type',
        'number_of_floors',
        'number_of_rooms',
        'floor_located',
        'area',
        'field_area',
        'advertiser',
        'advertiser_name',
        'phone_1',
        'phone_2',
        'phone_3',
        'phone_4',
        'mail',
        'description',
        'in_credit',
        'document',
        'note_to_admin',
        'building_type',
        'has_video',
        'google_map_location',
        'is_active',
        'is_premium',
        'user_id',
        'realtor_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'in_credit' => 'boolean',
        'has_video' => 'boolean',
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relations
     */

    public function city()
    {
        return $this->belongsTo(\Modules\City\Http\Entities\City::class);
    }

    public function town()
    {
        return $this->belongsTo(\Modules\Town\Http\Entities\Town::class);
    }

    public function district()
    {
        return $this->belongsTo(\Modules\District\Http\Entities\District::class);
    }

    public function subway()
    {
        return $this->belongsTo(\Modules\Base\Http\Entities\Subway::class);
    }

    public function user()
    {
        return $this->belongsTo(\Modules\User\Http\Entities\User::class);
    }

    public function realtor()
    {
        return $this->belongsTo(\Modules\User\Http\Entities\User::class, 'realtor_id');
    }

    public function media()
    {
        return $this->morphMany(\Modules\Media\Http\Entities\Media::class, 'imageable');
    }
}
