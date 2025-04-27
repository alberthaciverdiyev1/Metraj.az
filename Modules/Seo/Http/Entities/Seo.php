<?php

namespace Modules\Seo\Http\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seo extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'meta_tags',
        'other_codes',
        'page',
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
    // SEO, çoğu zaman bir sayfa ile ilişkilendirilebilir. İlgili sayfa modeli ile ilişki kurmak istenirse:
    public function pageable()
    {
        return $this->morphTo();
    }
}
