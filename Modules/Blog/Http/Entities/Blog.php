<?php

namespace Modules\Blog\Http\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blog\Database\Factories\BlogFactory;
use Modules\Blog\Traits\BlogRelations;

class Blog extends Model
{
    use HasFactory,SoftDeletes,BlogRelations;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blogs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'author_name',
        'category_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];
//    public static function newFactory(): BlogFactory
//    {
//        return BlogFactory::new();
//    }
}
