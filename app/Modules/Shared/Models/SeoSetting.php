<?php

namespace App\Modules\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string|null $head_scripts
 * @property string|null $body_scripts
 * @property string|null $footer_scripts
 * @property array|null $default_meta_title
 * @property array|null $default_meta_description
 * @property array|null $default_meta_keywords
 * @property string|null $og_image
 */
class SeoSetting extends Model
{
    protected $table = 'seo_settings';

    protected $fillable = [
        'head_scripts',
        'body_scripts',
        'footer_scripts',
        'default_meta_title',
        'default_meta_description',
        'default_meta_keywords',
        'og_image',
    ];

    protected $casts = [
        'default_meta_title' => 'array',
        'default_meta_description' => 'array',
        'default_meta_keywords' => 'array',
    ];

    public const CACHE_KEY = 'seo_settings_singleton';

    protected static ?self $memoized = null;

    protected static function booted(): void
    {
        static::saved(function () {
            static::$memoized = null;
            Cache::forget(self::CACHE_KEY);
        });

        static::deleted(function () {
            static::$memoized = null;
            Cache::forget(self::CACHE_KEY);
        });
    }

    public static function current(): self
    {
        if (static::$memoized !== null) {
            return static::$memoized;
        }

        $cached = Cache::get(self::CACHE_KEY);
        if ($cached instanceof self) {
            return static::$memoized = $cached;
        }

        $setting = self::firstOrCreate(
            ['id' => 1],
            [
                'head_scripts' => null,
                'body_scripts' => null,
                'footer_scripts' => null,
                'default_meta_title' => [
                    'tr' => 'KibrisKare - Kuzey Kıbrıs Emlak İlanları ve Satılık/Kiralık Evler',
                    'az' => 'KibrisKare - Şimali Kipr Əmlak Elanları və Satılıq/Kirayə Evlər',
                    'en' => 'KibrisKare - Northern Cyprus Real Estate & Property Listings',
                    'ru' => 'KibrisKare - Недвижимость на Северном Кипре: аренда и продажа',
                ],
                'default_meta_description' => [
                    'tr' => 'Kuzey Kıbrıs genelinde satılık daire, villa, arsa ve kiralık emlak ilanlarını en güvenilir acentelerden inceleyin.',
                    'az' => 'Şimali Kipr üzrə satılıq mənzil, villa, torpaq və kirayə əmlak elanlarını ən etibarlı agentliklərdən nəzərdən keçirin.',
                    'en' => 'Explore apartments, villas, land and rentals for sale across Northern Cyprus from trusted real estate agencies.',
                    'ru' => 'Ищите квартиры, виллы, земельные участки и аренду недвижимости по всему Северному Кипру.',
                ],
                'default_meta_keywords' => [
                    'tr' => 'kıbrıs emlak, girne satılık ev, lefkoşa kiralık daire, kktc arsa, kıbrıs villa',
                    'az' => 'kipr emlak, girne satiliq ev, lefkosa kiraye ev, kktc torpaq, kipr villa',
                    'en' => 'cyprus real estate, kyrenia property for sale, nicosia rent apartment, north cyprus villa',
                    'ru' => 'недвижимость северный кипр, купить дом кирения, снять квартиру никосия',
                ],
                'og_image' => null,
            ]
        );

        Cache::put(self::CACHE_KEY, $setting, 86400);

        return static::$memoized = $setting;
    }

    public function getTrans(string $field, ?string $locale = null, string $default = ''): string
    {
        $locale = $locale ?: app()->getLocale();
        $values = $this->{$field};

        if (is_array($values)) {
            return $values[$locale] ?? $values['tr'] ?? $values['az'] ?? reset($values) ?: $default;
        }

        return (string) ($values ?: $default);
    }
}
