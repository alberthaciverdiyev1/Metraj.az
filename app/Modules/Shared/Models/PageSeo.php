<?php

namespace App\Modules\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $page_key
 * @property string $page_name
 * @property string|null $route_name
 * @property array|null $title
 * @property array|null $description
 * @property array|null $keywords
 * @property string|null $canonical_url
 * @property string|null $og_image
 * @property int $sort_order
 */
class PageSeo extends Model
{
    protected $table = 'page_seos';

    protected $fillable = [
        'page_key',
        'page_name',
        'route_name',
        'title',
        'description',
        'keywords',
        'canonical_url',
        'og_image',
        'sort_order',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'keywords' => 'array',
    ];

    public const CACHE_KEY_ALL = 'page_seos_all';

    protected static ?array $memoizedAll = null;
    protected static ?self $memoizedCurrent = null;

    protected static function booted(): void
    {
        static::saved(function () {
            static::$memoizedAll = null;
            static::$memoizedCurrent = null;
            Cache::forget(self::CACHE_KEY_ALL);
        });

        static::deleted(function () {
            static::$memoizedAll = null;
            static::$memoizedCurrent = null;
            Cache::forget(self::CACHE_KEY_ALL);
        });
    }

    /**
     * Bütün səhifə SEO parametrlərini açar üzrə assosiativ massiv kimi qaytarır
     */
    public static function allCached(): array
    {
        if (static::$memoizedAll !== null) {
            return static::$memoizedAll;
        }

        $cached = Cache::get(self::CACHE_KEY_ALL);
        if (is_array($cached) && !empty($cached) && reset($cached) instanceof self) {
            return static::$memoizedAll = $cached;
        }

        $all = self::orderBy('sort_order')->get()->keyBy('page_key')->all();
        if (empty($all)) {
            self::ensureDefaults();
            $all = self::orderBy('sort_order')->get()->keyBy('page_key')->all();
        }
        Cache::put(self::CACHE_KEY_ALL, $all, 86400);

        return static::$memoizedAll = $all;
    }

    /**
     * Cari route və ya səhifə açarı üzrə PageSeo tapır
     */
    public static function findForCurrentRoute(?string $currentRoute = null): ?self
    {
        if ($currentRoute === null && static::$memoizedCurrent !== null) {
            return static::$memoizedCurrent;
        }

        $all = self::allCached();
        $routeName = $currentRoute ?: request()->route()?->getName();

        if ($routeName) {
            foreach ($all as $pageSeo) {
                if ($pageSeo->route_name === $routeName || $pageSeo->page_key === $routeName) {
                    return static::$memoizedCurrent = $pageSeo;
                }
            }
        }

        $path = trim(request()->path(), '/');
        if ($path === '' || $path === '/') {
            return static::$memoizedCurrent = ($all['home'] ?? null);
        }

        return null;
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

    /**
     * Standart səhifələri avtomatik ilkinləşdirir
     */
    public static function ensureDefaults(): void
    {
        $defaultPages = [
            [
                'page_key' => 'home',
                'page_name' => 'Ana Səhifə',
                'route_name' => 'home',
                'sort_order' => 1,
                'title' => [
                    'tr' => 'KibrisKare - Kuzey Kıbrıs Emlak İlanları ve Satılık Evler',
                    'az' => 'KibrisKare - Şimali Kipr Əmlak Elanları və Satılıq Evlər',
                    'en' => 'KibrisKare - Northern Cyprus Real Estate & Property Listings',
                    'ru' => 'KibrisKare - Недвижимость на Северном Кипре',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs genelinde binlerce satılık ve kiralık villa, daire, arsa ve iş yeri ilanları.',
                    'az' => 'Şimali Kipr üzrə minlərlə satılıq və kirayə villa, mənzil, torpaq və kommersiya elanları.',
                    'en' => 'Thousands of villas, apartments, land and commercial properties for sale and rent in Northern Cyprus.',
                    'ru' => 'Тысячи предложений вилл, квартир и участков на продажу и аренду на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'listing',
                'page_name' => 'Elanlar (Axtarış)',
                'route_name' => 'listing',
                'sort_order' => 2,
                'title' => [
                    'tr' => 'Kuzey Kıbrıs Satılık ve Kiralık Emlak İlanları - KibrisKare',
                    'az' => 'Şimali Kipr Satılıq və Kirayə Əmlak Elanları - KibrisKare',
                    'en' => 'Northern Cyprus Properties For Sale & Rent - KibrisKare',
                    'ru' => 'Объявления недвижимости на Северном Кипре - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Girne, Lefkoşa, Gazimağusa ve İskele bölgelerindeki en güncel satılık ve kiralık emlak ilanları.',
                    'az' => 'Girnə, Lefkoşa, Qazimağusa və İskele bölgələrindəki ən aktual satılıq və kirayə əmlak elanları.',
                    'en' => 'Latest real estate listings for sale and rent in Kyrenia, Nicosia, Famagusta and Iskele.',
                    'ru' => 'Актуальные объявления в Кирении, Никосии, Фамагусте и Искеле.',
                ],
            ],
            [
                'page_key' => 'requests',
                'page_name' => 'Əmlak Tələbləri',
                'route_name' => 'requests.index',
                'sort_order' => 3,
                'title' => [
                    'tr' => 'Alıcı ve Kiracı Talepleri - KibrisKare',
                    'az' => 'Alıcı və Kirayəçi Tələbləri - KibrisKare',
                    'en' => 'Buyer & Tenant Property Requests - KibrisKare',
                    'ru' => 'Запросы покупателей и арендаторов - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs emlak pazarında alıcı ve kiracıların paylaştığı güncel gayrimenkul talepleri.',
                    'az' => 'Şimali Kipr əmlak bazarında alıcı və kirayəçilərin paylaşdığı aktual daşınmaz əmlak tələbləri.',
                    'en' => 'Current real estate requests submitted by active buyers and tenants across Northern Cyprus.',
                    'ru' => 'Актуальные запросы на покупку и аренду недвижимости на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'agencies',
                'page_name' => 'Əmlak Agentlikləri',
                'route_name' => 'agencies.list',
                'sort_order' => 4,
                'title' => [
                    'tr' => 'Kuzey Kıbrıs Güvenilir Emlak Acenteleri ve Ofisleri - KibrisKare',
                    'az' => 'Şimali Kipr Etibarlı Əmlak Agentlikləri və Ofisləri - KibrisKare',
                    'en' => 'Trusted Real Estate Agencies in Northern Cyprus - KibrisKare',
                    'ru' => 'Агентства недвижимости Северного Кипра - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs genelinde hizmet veren profesyonel ve kurumsal emlak acentelerini keşfedin.',
                    'az' => 'Şimali Kipr üzrə fəaliyyət göstərən peşəkar və korporativ əmlak agentliklərini kəşf edin.',
                    'en' => 'Discover professional and licensed real estate agencies operating in Northern Cyprus.',
                    'ru' => 'Профессиональные агентства недвижимости, работающие на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'blog',
                'page_name' => 'Bloq & Xəbərlər',
                'route_name' => 'blog.list',
                'sort_order' => 5,
                'title' => [
                    'tr' => 'Kıbrıs Emlak Rehberi ve Haberler - KibrisKare Blog',
                    'az' => 'Kipr Əmlak Bələdçisi və Xəbərləri - KibrisKare Bloq',
                    'en' => 'Cyprus Real Estate Guide & News - KibrisKare Blog',
                    'ru' => 'Блог о недвижимости Северного Кипра - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs gayrimenkul yatırımı, yaşam rehberi ve emlak piyasası analizleri.',
                    'az' => 'Şimali Kipr daşınmaz əmlak investisiyası, yaşayış bələdçisi və bazar analizləri.',
                    'en' => 'Northern Cyprus property investment, lifestyle guides and real estate market trends.',
                    'ru' => 'Инвестиции в недвижимость Северного Кипра, аналитика и полезные статьи.',
                ],
            ],
            [
                'page_key' => 'about',
                'page_name' => 'Haqqımızda',
                'route_name' => 'about-us',
                'sort_order' => 6,
                'title' => [
                    'tr' => 'Hakkımızda - KibrisKare.com',
                    'az' => 'Haqqımızda - KibrisKare.com',
                    'en' => 'About Us - KibrisKare.com',
                    'ru' => 'О нас - KibrisKare.com',
                ],
                'description' => [
                    'tr' => 'KibrisKare.com hakkında bilgi, misyonumuz, vizyonumuz ve hizmetlerimiz.',
                    'az' => 'KibrisKare.com haqqında məlumat, missiyamız, baxışımız və təqdim etdiyimiz xidmətlər.',
                    'en' => 'Learn about KibrisKare.com, our mission, vision and real estate solutions.',
                    'ru' => 'Информация о портале KibrisKare.com, наша миссия и услуги.',
                ],
            ],
            [
                'page_key' => 'contact',
                'page_name' => 'Əlaqə',
                'route_name' => 'contact',
                'sort_order' => 7,
                'title' => [
                    'tr' => 'İletişim - KibrisKare.com',
                    'az' => 'Əlaqə - KibrisKare.com',
                    'en' => 'Contact Us - KibrisKare.com',
                    'ru' => 'Контакты - KibrisKare.com',
                ],
                'description' => [
                    'tr' => 'KibrisKare müşteri hizmetleri, ofis adresi, telefon ve mesaj formu ile bize ulaşın.',
                    'az' => 'KibrisKare müştəri xidmətləri, ofis ünvanı, telefon və müraciət forması ilə bizimlə əlaqə saxlayın.',
                    'en' => 'Get in touch with KibrisKare customer support, office address, phone and inquiry form.',
                    'ru' => 'Свяжитесь со службой поддержки KibrisKare, адрес офиса и телефон.',
                ],
            ],
            [
                'page_key' => 'faq',
                'page_name' => 'Tez-tez Verilən Suallar (FAQ)',
                'route_name' => 'faq',
                'sort_order' => 8,
                'title' => [
                    'tr' => 'Sıkça Sorulan Sorular (SSS) - KibrisKare',
                    'az' => 'Tez-tez Verilən Suallar (FAQ) - KibrisKare',
                    'en' => 'Frequently Asked Questions (FAQ) - KibrisKare',
                    'ru' => 'Часто задаваемые вопросы (FAQ) - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs’ta ev alırken, kiralarken ve ilan verirken en çok sorulan soruların yanıtları.',
                    'az' => 'Şimali Kiprdə ev alarkən, kirayələyərkən və elan yerləşdirərkən ən çox verilən sualların cavabları.',
                    'en' => 'Answers to the most common questions about buying, renting and listing property in Northern Cyprus.',
                    'ru' => 'Ответы на популярные вопросы о покупке, аренде и публикации объявлений на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'compare',
                'page_name' => 'Müqayisə',
                'route_name' => 'compare',
                'sort_order' => 9,
                'title' => [
                    'tr' => 'Emlak Karşılaştırma - KibrisKare',
                    'az' => 'Əmlak Müqayisəsi - KibrisKare',
                    'en' => 'Property Comparison - KibrisKare',
                    'ru' => 'Сравнение недвижимости - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Seçtiğiniz gayrimenkullerin özelliklerini, fiyatlarını ve konumlarını yan yana karşılaştırın.',
                    'az' => 'Seçdiyiniz daşınmaz əmlakların parametrlərini, qiymətlərini və yerləşməsini müqayisə edin.',
                    'en' => 'Compare property features, prices and locations side by side.',
                    'ru' => 'Сравните характеристики, цены и расположение выбранных объектов недвижимости.',
                ],
            ],
        ];

        foreach ($defaultPages as $pageData) {
            self::firstOrCreate(
                ['page_key' => $pageData['page_key']],
                $pageData
            );
        }
    }
}
