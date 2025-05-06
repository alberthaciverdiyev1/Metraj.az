<?php

namespace Modules\Base\Http\Entities;

use Database\Factories\SubwayFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subway extends Model
{
    use SoftDeletes,HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subways';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Relations
     */
    // Subways may have many properties related to them
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public static function newFactory(): SubwayFactory{
        return SubwayFactory::new();
    }
}
