<?php

namespace Modules\Town\Http\Entities;

use Database\Factories\TownFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\District\Http\Entities\District;

class Town extends Model
{
    use SoftDeletes,HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'towns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'name',
        'district_id',
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
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public static function newFactory(): TownFactory{
        return TownFactory::new();
    }
}
