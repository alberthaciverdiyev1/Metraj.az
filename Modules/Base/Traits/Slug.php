<?php

namespace Modules\Base\Traits;

use Illuminate\Support\Str;

trait Slug
{
    public static function bootSlug(): void
    {
        static::creating(function ($model) {
            $model->slug = Str::slug(($model->getAttribute('title') ?? $model->getAttribute('name')) . '-' . rand(10, 1000000), '-');
        });

        static::updating(function ($model) {
            $model->slug = Str::slug(($model->getAttribute('title') ?? $model->getAttribute('name')) . '-' . rand(10, 1000000), '-');
        });
    }

    public function slug(): ?string
    {
        return $this->getAttribute('slug');
    }
}
