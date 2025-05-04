<?php

namespace Modules\Keyword\Http\Entities;

use Database\Factories\KeywordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keyword extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'keywords';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'name',
        'city_id',
        'town_id',
        'district_id',
        'subway_id',
        'ad_type',
        'property_type',
        'number_of_rooms',
        'number_of_floors',
        'in_credit',
        'document',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'city_id' => 'integer',
        'town_id' => 'integer',
        'district_id' => 'integer',
        'subway_id' => 'integer',
        'number_of_rooms' => 'integer',
        'number_of_floors' => 'integer',
        'in_credit' => 'boolean',
        'document' => 'boolean',
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

    public static function newFactory(): KeywordFactory
    {
        return KeywordFactory::new();
    }
}
