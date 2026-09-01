<?php

namespace App\Modules\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $page_key
 * @property string $page_name
 * @property string|null $route_name
 * @property array|null $h1
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
        'h1',
        'title',
        'description',
        'keywords',
        'canonical_url',
        'og_image',
        'sort_order',
    ];

    protected $casts = [
        'h1' => 'array',
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

    protected static ?string $memoizedRequestKey = null;

    /**
     * Cari route və ya səhifə açarı üzrə PageSeo tapır
     */
    public static function findForCurrentRoute(?string $currentRoute = null): ?self
    {
        $requestKey = ($currentRoute ?: '') . '|' . request()->path();
        if ($currentRoute === null && static::$memoizedCurrent !== null && static::$memoizedRequestKey === $requestKey) {
            return static::$memoizedCurrent;
        }

        $all = self::allCached();
        $routeName = $currentRoute ?: request()->route()?->getName();
        $path = trim(request()->path(), '/');

        // Strip locale prefix if present (e.g. tr/hakkimizda -> hakkimizda)
        $segments = explode('/', $path);
        if (!empty($segments[0]) && in_array(strtolower($segments[0]), ['az', 'tr', 'en', 'ru'], true)) {
            array_shift($segments);
        }
        $cleanPath = implode('/', $segments);

        // 1. Home Page
        if ($cleanPath === '' || $cleanPath === '/') {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['home'] ?? null);
        }

        // 2. Specific static sections & modules (before wildcard routes)
        if (str_starts_with($cleanPath, 'requests') || str_starts_with($cleanPath, 'telebler') || str_starts_with($cleanPath, 'ariyorum')) {
            static::$memoizedRequestKey = $requestKey;
            if (str_contains($cleanPath, 'create') || str_contains($cleanPath, 'elave-et') || str_contains($cleanPath, 'ilan-ver')) {
                return static::$memoizedCurrent = ($all['requests_create'] ?? null);
            }
            return static::$memoizedCurrent = ($all['requests'] ?? null);
        }

        if (str_starts_with($cleanPath, 'roommates') || str_starts_with($cleanPath, 'otaq-yoldasi') || str_starts_with($cleanPath, 'oda-arkadasi')) {
            static::$memoizedRequestKey = $requestKey;
            if (str_contains($cleanPath, 'create') || str_contains($cleanPath, 'elave-et') || str_contains($cleanPath, 'ilan-ver')) {
                return static::$memoizedCurrent = ($all['roommates_create'] ?? null);
            }
            return static::$memoizedCurrent = ($all['roommates'] ?? null);
        }

        if (str_starts_with($cleanPath, 'emlak-ofisleri') || str_starts_with($cleanPath, 'agencies') || str_starts_with($cleanPath, 'agentlikler') || str_starts_with($cleanPath, 'acenteler')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['agencies'] ?? null);
        }

        if (str_starts_with($cleanPath, 'blog') || str_starts_with($cleanPath, 'meqaleler') || str_starts_with($cleanPath, 'bloq')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['blog'] ?? null);
        }

        if (str_starts_with($cleanPath, 'contact') || str_starts_with($cleanPath, 'elaqe') || str_starts_with($cleanPath, 'iletisim')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['contact'] ?? null);
        }

        if (str_starts_with($cleanPath, 'about') || str_starts_with($cleanPath, 'haqqimizda') || str_starts_with($cleanPath, 'hakkimizda')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['about'] ?? null);
        }

        if (str_starts_with($cleanPath, 'faq') || str_starts_with($cleanPath, 'suallar') || str_starts_with($cleanPath, 'sss') || str_starts_with($cleanPath, 'sikca-sorulan-sorular')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['faq'] ?? null);
        }

        if (str_starts_with($cleanPath, 'compare') || str_starts_with($cleanPath, 'muqayise') || str_starts_with($cleanPath, 'karsilastir')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['compare'] ?? null);
        }

        if (str_starts_with($cleanPath, 'favorites') || str_starts_with($cleanPath, 'secilmisler') || str_starts_with($cleanPath, 'favoriler')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['favorites'] ?? null);
        }

        if (str_starts_with($cleanPath, 'add-property') || str_starts_with($cleanPath, 'elan-yerlesdir') || str_starts_with($cleanPath, 'ilan-ver')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['add_property'] ?? null);
        }

        // 3. Deal type / Listing subpaths (Satılıq vs Kirayə vs Günlük)
        $first = request()->route('first') ?? ($segments[0] ?? null);
        $second = request()->route('second') ?? ($segments[1] ?? null);
        $dealType = request('deal_type');

        if (in_array($first, ['satilik', 'satis', 'satiq', 'sale'], true) || $dealType === 'sale') {
            if (isset($all['listing_sale'])) {
                static::$memoizedRequestKey = $requestKey;
                return static::$memoizedCurrent = $all['listing_sale'];
            }
        }

        if ($first === 'kira' || in_array($first, ['kiralik', 'kiraye', 'kiraya', 'rent'], true) || in_array($dealType, ['rent', 'rent_monthly', 'rent_daily'])) {
            if ($second === 'gunluk' || in_array($second, ['gundelik', 'daily'], true) || $dealType === 'rent_daily') {
                if (isset($all['listing_rent_daily'])) {
                    static::$memoizedRequestKey = $requestKey;
                    return static::$memoizedCurrent = $all['listing_rent_daily'];
                }
            }
            if ($second === 'ayliq' || in_array($second, ['aylik', 'monthly'], true) || $dealType === 'rent_monthly') {
                if (isset($all['listing_rent_monthly'])) {
                    static::$memoizedRequestKey = $requestKey;
                    return static::$memoizedCurrent = $all['listing_rent_monthly'];
                }
            }
        }

        // 4. Exact route name match (excluding generic wildcards)
        if ($routeName && !in_array($routeName, ['listing.path1', 'listing.path2', 'listing.path3'], true)) {
            foreach ($all as $pageSeo) {
                if ($pageSeo->route_name === $routeName) {
                    static::$memoizedRequestKey = $requestKey;
                    return static::$memoizedCurrent = $pageSeo;
                }
            }
        }

        // 5. Page key or URI fallback
        if (!empty($routeName) && isset($all[$routeName])) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = $all[$routeName];
        }

        static::$memoizedRequestKey = $requestKey;
        return static::$memoizedCurrent = null;
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
                'h1' => [
                    'tr' => 'Kuzey Kıbrıs Emlak, Satılık ve Kiralık Ev İlanları',
                    'az' => 'Şimali Kipr Əmlak, Satılıq və Kirayə Ev Elanları',
                    'en' => 'Northern Cyprus Real Estate, Properties for Sale & Rent',
                    'ru' => 'Недвижимость на Северном Кипре: Продажа и Аренда',
                ],
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
                'page_key' => 'listing_sale',
                'page_name' => 'Satılıq Əmlaklar',
                'route_name' => 'listing.path1',
                'sort_order' => 2,
                'h1' => [
                    'tr' => 'Kuzey Kıbrıs Satılık Evler, Villalar ve Daireler',
                    'az' => 'Şimali Kiprdə Satılıq Evlər, Villalar və Mənzillər',
                    'en' => 'Houses, Villas and Apartments for Sale in Northern Cyprus',
                    'ru' => 'Продажа домов, вилл и квартир на Северном Кипре',
                ],
                'title' => [
                    'tr' => 'Kuzey Kıbrıs Satılık Evler, Villalar ve Daireler - KibrisKare',
                    'az' => 'Şimali Kipr Satılıq Evlər, Villalar və Mənzillər - KibrisKare',
                    'en' => 'Properties For Sale in Northern Cyprus - Villas & Apartments',
                    'ru' => 'Купить недвижимость на Северном Кипре - Виллы и квартиры',
                ],
                'description' => [
                    'tr' => 'Girne, Lefkoşa, Gazimağusa ve İskele bölgelerinde en uygun fiyatlı satılık evler, lüks villalar ve yatırımlık daireler.',
                    'az' => 'Girnə, Lefkoşa, Qazimağusa və İskele bölgələrində ən sərfəli qiymətə satılıq evlər, lüks villalar və investisiya mənzilləri.',
                    'en' => 'Best priced houses, luxury villas, and investment apartments for sale in Kyrenia, Nicosia, Famagusta and Iskele.',
                    'ru' => 'Лучшие предложения по продаже домов, элитных вилл и инвестиционных квартир на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'listing_rent_monthly',
                'page_name' => 'Kirayə Əmlaklar (Aylıq)',
                'route_name' => 'listing.path2',
                'sort_order' => 3,
                'h1' => [
                    'tr' => 'Kuzey Kıbrıs Kiralık Evler ve Daireler (Aylık)',
                    'az' => 'Şimali Kiprdə Aylıq Kirayə Evlər və Mənzillər',
                    'en' => 'Long Term & Monthly Rentals in Northern Cyprus',
                    'ru' => 'Долгосрочная аренда квартир и домов на Северном Кипре',
                ],
                'title' => [
                    'tr' => 'Kuzey Kıbrıs Kiralık Evler ve Daireler (Aylık) - KibrisKare',
                    'az' => 'Şimali Kipr Kirayə Evlər və Mənzillər (Aylıq) - KibrisKare',
                    'en' => 'Long Term & Monthly Rentals in Northern Cyprus - KibrisKare',
                    'ru' => 'Аренда квартир и домов на Северном Кипре помесячно',
                ],
                'description' => [
                    'tr' => 'Kıbrıs genelinde eşyalı veya eşyasız, uygun fiyatlı aylık kiralık daireler, siteler ve müstakil evler.',
                    'az' => 'Kipr üzrə əşyalı və ya əşyasız, münasib qiymətə aylıq kirayə mənzillər, yaşayış kompleksləri və həyət evləri.',
                    'en' => 'Furnished and unfurnished monthly rental apartments, residences, and houses across Northern Cyprus.',
                    'ru' => 'Меблированные и без мебели квартиры и дома в долгосрочную аренду на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'listing_rent_daily',
                'page_name' => 'Günlük Kirayə Əmlaklar (Tətil/Qısa Müddətli)',
                'route_name' => 'listing.path2',
                'sort_order' => 4,
                'h1' => [
                    'tr' => 'Kuzey Kıbrıs Günlük Kiralık Villalar ve Tatil Evleri',
                    'az' => 'Şimali Kiprdə Günlük Kirayə Villalar və İstirahət Evləri',
                    'en' => 'Daily Vacation Rentals & Luxury Villas in Cyprus',
                    'ru' => 'Посуточная аренда вилл и апартаментов на Кипре',
                ],
                'title' => [
                    'tr' => 'Kıbrıs Günlük Kiralık Villalar ve Tatil Evleri - KibrisKare',
                    'az' => 'Kipr Günlük Kirayə Villalar və İstirahət Evləri - KibrisKare',
                    'en' => 'Holiday Homes & Daily Villa Rentals in Northern Cyprus',
                    'ru' => 'Посуточная аренда вилл и апартаментов для отдыха на Кипре',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs’ta unutulmaz bir tatil için havuzlu lüks villalar ve denize sıfır günlük kiralık daireler.',
                    'az' => 'Şimali Kiprdə unudulmaz tətil üçün hovuzlu lüks villalar və dəniz kənarı günlük kirayə evlər.',
                    'en' => 'Luxury villas with private pool and beachfront apartments for daily vacation rentals in Cyprus.',
                    'ru' => 'Элитные виллы с бассейном и апартаменты у моря в посуточную аренду на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'requests',
                'page_name' => 'Əmlak Tələbləri (Axtarıram)',
                'route_name' => 'requests.index',
                'sort_order' => 5,
                'h1' => [
                    'tr' => 'Alıcı ve Kiracı Gayrimenkul Talepleri (Arıyorum)',
                    'az' => 'Alıcı və Kirayəçi Daşınmaz Əmlak Tələbləri (Axtarıram)',
                    'en' => 'Buyer and Tenant Property Requests in Cyprus',
                    'ru' => 'Запросы клиентов на покупку и аренду недвижимости',
                ],
                'title' => [
                    'tr' => 'Alıcı ve Kiracı Talepleri (Arıyorum) - KibrisKare',
                    'az' => 'Alıcı və Kirayəçi Tələbləri (Axtarıram) - KibrisKare',
                    'en' => 'Buyer & Tenant Property Inquiries & Requests - KibrisKare',
                    'ru' => 'Запросы покупателей и арендаторов на недвижимость',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs emlak pazarında alıcı ve kiracıların paylaştığı güncel gayrimenkul talepleri.',
                    'az' => 'Şimali Kipr əmlak bazarında alıcı və kirayəçilərin paylaşdığı aktual daşınmaz əmlak tələbləri.',
                    'en' => 'Active property requests posted by buyers and tenants in Northern Cyprus.',
                    'ru' => 'Актуальные запросы клиентов на покупку и аренду недвижимости на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'requests_create',
                'page_name' => 'Tələb Yerləşdir (Axtarıram Elan Et)',
                'route_name' => 'requests.create',
                'sort_order' => 6,
                'h1' => [
                    'tr' => 'Gayrimenkul Talebi Oluştur ve Aradığın Evi Bul',
                    'az' => 'Əmlak Tələbi Yerləşdir və Axtardığın Evi Tap',
                    'en' => 'Post a Property Request in Northern Cyprus',
                    'ru' => 'Оставить заявку на подбор недвижимости на Кипре',
                ],
                'title' => [
                    'tr' => 'Gayrimenkul Talebi Oluştur (Arıyorum İlanı Ver) - KibrisKare',
                    'az' => 'Əmlak Tələbi Yerləşdir (Axtarıram Elanı Ver) - KibrisKare',
                    'en' => 'Post a Property Request - Find Your Dream Home in Cyprus',
                    'ru' => 'Оставить заявку на поиск недвижимости на Кипре',
                ],
                'description' => [
                    'tr' => 'Aradığınız evin kriterlerini ve bütçenizi paylaşın, Kıbrıs acenteleri ve sahipleri size ulaşsın.',
                    'az' => 'Axtardığınız evin parametrlərini və büdcənizi qeyd edin, Kipr agentlikləri və mülkiyyətçiləri sizə təklif göndərsin.',
                    'en' => 'Submit your property requirements and budget to receive offers directly from verified agencies and owners.',
                    'ru' => 'Укажите критерии желаемой недвижимости, и агенты свяжутся с вами с подходящими предложениями.',
                ],
            ],
            [
                'page_key' => 'roommates',
                'page_name' => 'Otaq Yoldaşı Elanları',
                'route_name' => 'roommates.index',
                'sort_order' => 7,
                'h1' => [
                    'tr' => 'Kuzey Kıbrıs Oda Arkadaşı ve Paylaşımlı Ev İlanları',
                    'az' => 'Şimali Kipr Otaq Yoldaşı və Həmyoldaş Elanları',
                    'en' => 'Roommates and Flatshare Listings in Northern Cyprus',
                    'ru' => 'Поиск соседей по комнате и совместная аренда на Кипре',
                ],
                'title' => [
                    'tr' => 'Kuzey Kıbrıs Oda Arkadaşı ve Paylaşımlı Ev İlanları - KibrisKare',
                    'az' => 'Şimali Kipr Otaq Yoldaşı və Həmyoldaş Elanları - KibrisKare',
                    'en' => 'Roommates & Flatshare in Northern Cyprus - KibrisKare',
                    'ru' => 'Поиск соседей по комнате и совместная аренда на Кипре',
                ],
                'description' => [
                    'tr' => 'Öğrenciler ve çalışanlar için Kıbrıs genelinde güvenilir oda arkadaşı ve ortak ev kiralama ilanları.',
                    'az' => 'Tələbələr və işləyənlər üçün Kipr üzrə etibarlı otaq yoldaşı və paylaşılan mənzil elanları.',
                    'en' => 'Find verified roommates and shared flats in Northern Cyprus for students and professionals.',
                    'ru' => 'Объявления о поиске соседей для совместной аренды жилья на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'roommates_create',
                'page_name' => 'Otaq Yoldaşı Elanı Əlavə Et',
                'route_name' => 'roommates.create',
                'sort_order' => 8,
                'h1' => [
                    'tr' => 'Oda Arkadaşı İlanı Ver',
                    'az' => 'Otaq Yoldaşı Elanı Yerləşdir',
                    'en' => 'Post a Roommate Listing in Northern Cyprus',
                    'ru' => 'Подать объявление о поиске соседа по комнате',
                ],
                'title' => [
                    'tr' => 'Oda Arkadaşı İlanı Ver - KibrisKare',
                    'az' => 'Otaq Yoldaşı Elanı Yerləşdir - KibrisKare',
                    'en' => 'Post a Roommate Listing - KibrisKare',
                    'ru' => 'Подать объявление о поиске соседа по комнате',
                ],
                'description' => [
                    'tr' => 'Eviniz veya odanız için en uygun oda arkadaşını hemen bulun.',
                    'az' => 'Eviniz və ya otağınız üçün ən uyğun otaq yoldaşını dərhal tapın.',
                    'en' => 'Find the ideal flatmate or roommate for your apartment or house in Cyprus.',
                    'ru' => 'Разместите объявление о поиске соседа по квартире или комнате на Кипре.',
                ],
            ],
            [
                'page_key' => 'add_property',
                'page_name' => 'Yeni Elan Yerləşdir (Əmlakını Sat / Kirayə Ver)',
                'route_name' => 'add-property',
                'sort_order' => 9,
                'h1' => [
                    'tr' => 'Ücretsiz Emlak İlanı Ver, Evini Sat veya Kirala',
                    'az' => 'Pulsuz Əmlak Elanı Yerləşdir, Evini Sat və ya Kirayə Ver',
                    'en' => 'Post Free Property Listing in Northern Cyprus',
                    'ru' => 'Подать бесплатное объявление о недвижимости на Кипре',
                ],
                'title' => [
                    'tr' => 'Ücretsiz Emlak İlanı Ver - Evini Sat veya Kirala - KibrisKare',
                    'az' => 'Pulsuz Əmlak Elanı Yerləşdir - Evini Sat və ya Kirayə Ver - KibrisKare',
                    'en' => 'Post Free Property Ad - Sell or Rent Property in Cyprus',
                    'ru' => 'Подать бесплатное объявление о продаже или аренде недвижимости',
                ],
                'description' => [
                    'tr' => 'Gayrimenkulünüzü binlerce alıcı ve kiracıya ulaştırın. Hızlı ve kolay ilan ekleme.',
                    'az' => 'Daşınmaz əmlakınızı minlərlə alıcı və kirayəçiyə çatdırın. Sürətli və asan elan yerləşdirmə.',
                    'en' => 'Reach thousands of buyers and tenants across Northern Cyprus. Easy and quick listing creation.',
                    'ru' => 'Разместите объект недвижимости и найдите покупателей или арендаторов быстро и просто.',
                ],
            ],
            [
                'page_key' => 'agencies',
                'page_name' => 'Əmlak Agentlikləri',
                'route_name' => 'agencies.list',
                'sort_order' => 10,
                'h1' => [
                    'tr' => 'Kuzey Kıbrıs Emlak Acenteleri ve Danışmanlık Ofisleri',
                    'az' => 'Şimali Kipr Əmlak Agentlikləri və Konsaltinq Ofisləri',
                    'en' => 'Real Estate Agencies and Property Brokers in Northern Cyprus',
                    'ru' => 'Агентства недвижимости и риелторские компании на Северном Кипре',
                ],
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
                'sort_order' => 11,
                'h1' => [
                    'tr' => 'Kıbrıs Emlak Rehberi, Yatırım Tavsiyeleri ve Piyasa Haberleri',
                    'az' => 'Kipr Əmlak Bələdçisi, İnvestisiya Məsləhətləri və Bazar Xəbərləri',
                    'en' => 'Cyprus Real Estate Guide, Investment News and Articles',
                    'ru' => 'Новости недвижимости и инвестиционный гид по Северному Кипру',
                ],
                'title' => [
                    'tr' => 'Kıbrıs Emlak Rehberi, Yatırım Tavsiyeleri ve Haberler - Blog',
                    'az' => 'Kipr Əmlak Bələdçisi, İnvestisiya Məsləhətləri və Xəbərlər - Bloq',
                    'en' => 'Cyprus Real Estate Guide, Investment Tips & News - Blog',
                    'ru' => 'Гид по недвижимости Кипра, инвестиции и новости - Блог',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs gayrimenkul yatırımı, yaşam rehberi ve emlak piyasası analizleri.',
                    'az' => 'Şimali Kipr daşınmaz əmlak investisiyası, yaşayış bələdçisi və bazar analizləri.',
                    'en' => 'Northern Cyprus property investment, lifestyle guides and real estate market trends.',
                    'ru' => 'Инвестиции в недвижимость Северного Кипра, аналитика и полезные статьи.',
                ],
            ],
            [
                'page_key' => 'contact',
                'page_name' => 'Əlaqə',
                'route_name' => 'contact',
                'sort_order' => 12,
                'h1' => [
                    'tr' => 'KibrisKare İletişim ve Müşteri Hizmetleri',
                    'az' => 'KibrisKare Əlaqə və Müştəri Xidmətləri',
                    'en' => 'Contact KibrisKare Customer Support',
                    'ru' => 'Контакты и служба поддержки KibrisKare',
                ],
                'title' => [
                    'tr' => 'İletişim - KibrisKare.com Müşteri Hizmetleri',
                    'az' => 'Əlaqə - KibrisKare.com Müştəri Xidmətləri',
                    'en' => 'Contact Us - KibrisKare Customer Support',
                    'ru' => 'Контакты - Служба поддержки KibrisKare',
                ],
                'description' => [
                    'tr' => 'KibrisKare müşteri hizmetleri, ofis adresi, telefon ve mesaj formu ile bize ulaşın.',
                    'az' => 'KibrisKare müştəri xidmətləri, ofis ünvanı, telefon və müraciət forması ilə bizimlə əlaqə saxlayın.',
                    'en' => 'Get in touch with KibrisKare customer support, office address, phone and inquiry form.',
                    'ru' => 'Свяжитесь со службой поддержки KibrisKare, адрес офиса и телефон.',
                ],
            ],
            [
                'page_key' => 'about',
                'page_name' => 'Haqqımızda',
                'route_name' => 'about-us',
                'sort_order' => 13,
                'h1' => [
                    'tr' => 'KibrisKare Hakkında - Vizyonumuz ve Hizmetlerimiz',
                    'az' => 'KibrisKare Haqqında - Baxışımız və Xidmətlərimiz',
                    'en' => 'About KibrisKare - Our Vision and Services',
                    'ru' => 'О компании KibrisKare - Наша миссия и услуги',
                ],
                'title' => [
                    'tr' => 'Hakkımızda - KibrisKare.com Vizyon ve Misyonumuz',
                    'az' => 'Haqqımızda - KibrisKare.com Baxış və Missiyamız',
                    'en' => 'About Us - KibrisKare.com Vision & Mission',
                    'ru' => 'О нас - KibrisKare.com Миссия и видение',
                ],
                'description' => [
                    'tr' => 'KibrisKare.com hakkında bilgi, misyonumuz, vizyonumuz ve hizmetlerimiz.',
                    'az' => 'KibrisKare.com haqqında məlumat, missiyamız, baxışımız və təqdim etdiyimiz xidmətlər.',
                    'en' => 'Learn about KibrisKare.com, our mission, vision and real estate solutions.',
                    'ru' => 'Информация о портале KibrisKare.com, наша миссия и услуги.',
                ],
            ],
            [
                'page_key' => 'faq',
                'page_name' => 'Tez-tez Verilən Suallar (FAQ / SSS)',
                'route_name' => 'faq',
                'sort_order' => 14,
                'h1' => [
                    'tr' => 'Sıkça Sorulan Sorular ve Emlak Rehberi',
                    'az' => 'Tez-tez Verilən Suallar və Əmlak Bələdçisi',
                    'en' => 'Frequently Asked Questions (FAQ)',
                    'ru' => 'Часто задаваемые вопросы о недвижимости',
                ],
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
                'page_name' => 'Əmlak Müqayisəsi',
                'route_name' => 'compares',
                'sort_order' => 15,
                'h1' => [
                    'tr' => 'Emlak Karşılaştırma Listesi',
                    'az' => 'Əmlak Müqayisəsi Siyahısı',
                    'en' => 'Property Comparison List',
                    'ru' => 'Сравнение объектов недвижимости',
                ],
                'title' => [
                    'tr' => 'Emlak Karşılaştırma Aracı - KibrisKare',
                    'az' => 'Əmlak Müqayisəsi Aləti - KibrisKare',
                    'en' => 'Property Comparison Tool - KibrisKare',
                    'ru' => 'Сравнение объектов недвижимости - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Seçtiğiniz gayrimenkullerin özelliklerini, fiyatlarını ve konumlarını yan yana karşılaştırın.',
                    'az' => 'Seçdiyiniz daşınmaz əmlakların parametrlərini, qiymətlərini və yerləşməsini müqayisə edin.',
                    'en' => 'Compare property features, prices and locations side by side.',
                    'ru' => 'Сравните характеристики, цены и расположение выбранных объектов недвижимости.',
                ],
            ],
            [
                'page_key' => 'favorites',
                'page_name' => 'Seçilmişlər (Sevimlilər)',
                'route_name' => 'favorites',
                'sort_order' => 16,
                'h1' => [
                    'tr' => 'Favori İlanlarım',
                    'az' => 'Seçilmiş Elanlarım',
                    'en' => 'My Favorite Properties',
                    'ru' => 'Мои избранные объявления',
                ],
                'title' => [
                    'tr' => 'Favori İlanlarım - KibrisKare',
                    'az' => 'Seçilmiş Elanlarım - KibrisKare',
                    'en' => 'My Favorite Properties - KibrisKare',
                    'ru' => 'Избранные объявления - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Beğendiğiniz ve kaydettiğiniz satılık ve kiralık emlak ilanlarını buradan takip edin.',
                    'az' => 'Bəyəndiyiniz və yadda saxladığınız satılıq və kirayə əmlak elanlarını buradan izləyin.',
                    'en' => 'View and track your saved and favorite properties for sale and rent.',
                    'ru' => 'Сохраненные и избранные объявления недвижимости.',
                ],
            ],
        ];

        foreach ($defaultPages as $pageData) {
            $record = self::where('page_key', $pageData['page_key'])->first();
            if ($record) {
                if (empty($record->h1)) {
                    $record->update(['h1' => $pageData['h1']]);
                }
            } else {
                self::create($pageData);
            }
        }
    }
}
