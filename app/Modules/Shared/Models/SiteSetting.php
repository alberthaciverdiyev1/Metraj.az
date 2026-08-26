<?php

namespace App\Modules\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string|null $phone
 * @property string|null $phone_secondary
 * @property string|null $whatsapp
 * @property string|null $email
 * @property string|null $support_email
 * @property array|null $address
 * @property string|null $working_hours_mon_fri
 * @property string|null $working_hours_sat
 * @property string|null $working_hours_sun
 * @property float|null $map_latitude
 * @property float|null $map_longitude
 * @property string|null $facebook_url
 * @property string|null $instagram_url
 * @property string|null $linkedin_url
 * @property string|null $youtube_url
 * @property string|null $telegram_url
 * @property string|null $tiktok_url
 * @property string|null $twitter_url
 * @property array|null $tagline
 * @property array|null $footer_description
 * @property string|null $copyright_text
 * @property array|null $user_agreement
 * @property array|null $privacy_policy
 * @property array|null $terms_of_use
 */
class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'phone',
        'phone_secondary',
        'whatsapp',
        'email',
        'support_email',
        'address',
        'working_hours_mon_fri',
        'working_hours_sat',
        'working_hours_sun',
        'map_latitude',
        'map_longitude',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'telegram_url',
        'tiktok_url',
        'twitter_url',
        'tagline',
        'footer_description',
        'copyright_text',
        'user_agreement',
        'privacy_policy',
        'terms_of_use',
    ];

    protected $casts = [
        'address' => 'array',
        'tagline' => 'array',
        'footer_description' => 'array',
        'user_agreement' => 'array',
        'privacy_policy' => 'array',
        'terms_of_use' => 'array',
        'map_latitude' => 'float',
        'map_longitude' => 'float',
    ];

    public const CACHE_KEY = 'site_settings_singleton';

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

    /**
     * Tək parametrlər qeydini (Singleton) keşdən və ya DB-dən gətirir
     */
    public static function current(): self
    {
        if (static::$memoized !== null) {
            return static::$memoized;
        }

        $cached = Cache::get(self::CACHE_KEY);
        if ($cached instanceof self) {
            return static::$memoized = $cached;
        }

        $setting = self::find(1);
        if (!$setting) {
            $setting = self::create([
                'id' => 1,
                'phone' => '+90 (548) 888-8888',
                'phone_secondary' => '+90 (392) 815 00 00',
                'whatsapp' => '+905488888888',
                'email' => 'info@kibriskare.com',
                'support_email' => 'support@kibriskare.com',
                'address' => [
                    'tr' => 'Girne, Kuzey Kıbrıs Türk Cumhuriyeti',
                    'az' => 'Girnə, Şimali Kipr',
                    'en' => 'Kyrenia, Northern Cyprus',
                    'ru' => 'Кирения, Северный Кипр',
                ],
                'working_hours_mon_fri' => '09:00 – 19:00',
                'working_hours_sat' => '10:00 – 18:00',
                'working_hours_sun' => 'Online 7/24',
                'map_latitude' => 35.3382440,
                'map_longitude' => 33.3186270,
                'facebook_url' => 'https://facebook.com',
                'instagram_url' => 'https://instagram.com',
                'linkedin_url' => 'https://linkedin.com',
                'youtube_url' => 'https://youtube.com',
                'telegram_url' => 'https://t.me',
                'tiktok_url' => 'https://tiktok.com',
                'twitter_url' => 'https://x.com',
                'tagline' => [
                    'tr' => 'Kuzey Kıbrıs Emlak İlanları Platformu',
                    'az' => 'Şimali Kipr Əmlak Elanları Platforması',
                    'en' => 'Northern Cyprus Real Estate Platform',
                    'ru' => 'Платформа недвижимости Северного Кипра',
                ],
                'footer_description' => [
                    'tr' => 'Kuzey Kıbrıs genelinde binlerce satılık ve kiralık emlak ilanını tek platformda keşfedin. Güvenilir emlak acenteleri ve sahibinden ilanlar.',
                    'az' => 'Şimali Kipr üzrə minlərlə satılıq və kirayə əmlak elanını vahid platformada kəşf edin. Etibarlı agentliklər və birbaşa sahibindən elanlar.',
                    'en' => 'Discover thousands of properties for sale and rent across Northern Cyprus on a single platform. Trusted agencies and owner listings.',
                    'ru' => 'Откройте для себя тысячи объявлений о продаже и аренде недвижимости по всему Северному Кипру на единой платформе.',
                ],
                'copyright_text' => 'KibrisKare.com',
            ]);
        }

        Cache::put(self::CACHE_KEY, $setting, 86400);

        return static::$memoized = $setting;
    }

    /**
     * Çoxdilli sahədən cari dilə uyğun mətni qaytarır
     */
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
