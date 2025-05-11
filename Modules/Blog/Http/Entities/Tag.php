<?php

namespace Modules\Blog\Http\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blog\Database\Factories\TagFactory;

class Tag extends Model
{
    use HasFactory,SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

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
        'slug' => 'string',
    ];

    public static function newFactory(): TagFactory
    {
        return TagFactory::new();
    }

    public function blogs(): MorphToMany
    {
        return $this->morphedByMany(Blog::class, 'taggable');
    }
}
