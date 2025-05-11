<?php

namespace Modules\Blog\Http\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Blog\Http\Entities\BlogCategory;
use Modules\Blog\Http\Entities\Tag;

trait BlogRelations
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    public function category(): HasOne
    {
        return $this->hasOne(BlogCategory::class, 'id', 'category_id');
    }
}
