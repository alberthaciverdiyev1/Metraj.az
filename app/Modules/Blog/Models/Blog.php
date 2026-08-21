<?php

namespace App\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property-read string $formatted_date
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static> published()
 */
class Blog extends Model
{
    use HasFactory;

    /**
     * Kütləvi doldurula bilən sütunlar (Mass Assignable)
     */
    protected $fillable = [
        'title',        // Bloq başlığı
        'slug',         // URL üçün unikal slug
        'category',     // Kategoriya (Məs: Bazar, Məsləhət, Xəbər)
        'cover_image',  // Üzlük / başlıq şəkli
        'excerpt',      // Qısa mətn (kartda göstərilir)
        'content',      // Tam məzmun
        'published_at', // Dərc tarixi
    ];

    /**
     * Məlumat tiplərinin çevrilməsi
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Model hadisələrinin qeydiyyatı
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    /**
     * Dərc olunmuş bloqlar üçün scoup
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * Tarix formatı
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->published_at?->format('d M Y') ?? '';
    }
}
