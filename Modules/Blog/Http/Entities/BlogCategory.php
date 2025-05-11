<?php

namespace Modules\Blog\Http\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blog\Database\Factories\BlogCategoryFactory;
use Modules\Blog\Database\Factories\BlogFactory;

class BlogCategory extends Model
{
    use HasFactory,SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];

    public static function newFactory(): BlogCategoryFactory
    {
        return BlogCategoryFactory::new();
    }
}
