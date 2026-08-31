<?php

namespace App\Modules\Shared\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property array $question
 * @property array $answer
 * @property string $category
 * @property int $sort_order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read string $localized_question
 * @property-read string $localized_answer
 */
class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'question' => 'array',
        'answer' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getQuestionTrans(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $fallback = config('app.fallback_locale', 'az');

        if (!empty($this->question[$locale])) {
            return (string) $this->question[$locale];
        }

        if (!empty($this->question['tr'])) {
            return (string) $this->question['tr'];
        }

        if (!empty($this->question[$fallback])) {
            return (string) $this->question[$fallback];
        }

        return is_array($this->question) ? (string) reset($this->question) : '';
    }

    public function getAnswerTrans(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $fallback = config('app.fallback_locale', 'az');

        if (!empty($this->answer[$locale])) {
            return (string) $this->answer[$locale];
        }

        if (!empty($this->answer['tr'])) {
            return (string) $this->answer['tr'];
        }

        if (!empty($this->answer[$fallback])) {
            return (string) $this->answer[$fallback];
        }

        return is_array($this->answer) ? (string) reset($this->answer) : '';
    }

    public function getLocalizedQuestionAttribute(): string
    {
        return $this->getQuestionTrans();
    }

    public function getLocalizedAnswerAttribute(): string
    {
        return $this->getAnswerTrans();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }
}
