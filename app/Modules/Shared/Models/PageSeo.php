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
        $path = trim(request()->path(), '/');

        // 1. Specific checks for deal_type / listing subpaths (Satılıq vs Kirayə vs Günlük)
        $first = request()->route('first') ?? (explode('/', $path)[0] ?? null);
        $second = request()->route('second') ?? (explode('/', $path)[1] ?? null);
        $dealType = request('deal_type');

        if (in_array($first, ['satilik', 'satis', 'satiq', 'sale'], true) || $dealType === 'sale') {
            if (isset($all['listing_sale'])) {
                return static::$memoizedCurrent = $all['listing_sale'];
            }
        }

        if ($first === 'kira' || in_array($first, ['kiralik', 'kiraye', 'kiraya', 'rent'], true) || in_array($dealType, ['rent', 'rent_monthly', 'rent_daily'])) {
            if ($second === 'gunluk' || in_array($second, ['gundelik', 'daily'], true) || $dealType === 'rent_daily') {
                if (isset($all['listing_rent_daily'])) {
                    return static::$memoizedCurrent = $all['listing_rent_daily'];
                }
            }
            if ($second === 'ayliq' || in_array($second, ['aylik', 'monthly'], true) || $dealType === 'rent_monthly') {
                if (isset($all['listing_rent_monthly'])) {
                    return static::$memoizedCurrent = $all['listing_rent_monthly'];
                }
            }
            if (isset($all['listing_rent_monthly'])) {
                return static::$memoizedCurrent = $all['listing_rent_monthly'];
            }
        }

        // 2. Specific route matches
        if ($routeName) {
            foreach ($all as $pageSeo) {
                if ($pageSeo->route_name === $routeName || $pageSeo->page_key === $routeName) {
                    return static::$memoizedCurrent = $pageSeo;
                }
            }
        }

        // 3. Path-based fallbacks for all navbar routes
        if ($path === '' || $path === '/') {
            return static::$memoizedCurrent = ($all['home'] ?? null);
        }

        if ($path === 'satilik' || str_starts_with($path, 'satilik/')) {
            return static::$memoizedCurrent = ($all['listing_sale'] ?? $all['listing'] ?? null);
        }
        if (str_starts_with($path, 'kira/gunluk')) {
            return static::$memoizedCurrent = ($all['listing_rent_daily'] ?? $all['listing_rent_monthly'] ?? null);
        }
        if (str_starts_with($path, 'kira')) {
            return static::$memoizedCurrent = ($all['listing_rent_monthly'] ?? null);
        }
        if ($path === 'listing' || $path === 'properties') {
            return static::$memoizedCurrent = ($all['listing'] ?? null);
        }
        if (str_starts_with($path, 'axtariram/elave-et') || str_starts_with($path, 'requests/create')) {
            return static::$memoizedCurrent = ($all['requests_create'] ?? $all['requests'] ?? null);
        }
        if (str_starts_with($path, 'axtariram') || str_starts_with($path, 'requests')) {
            return static::$memoizedCurrent = ($all['requests'] ?? null);
        }
        if (str_starts_with($path, 'otaq-yoldasi/elave-et') || str_starts_with($path, 'roommates/create')) {
            return static::$memoizedCurrent = ($all['roommates_create'] ?? $all['roommates'] ?? null);
        }
        if (str_starts_with($path, 'otaq-yoldasi') || str_starts_with($path, 'roommates')) {
            return static::$memoizedCurrent = ($all['roommates'] ?? null);
        }
        if (str_starts_with($path, 'agencies') || str_starts_with($path, 'agentlik')) {
            return static::$memoizedCurrent = ($all['agencies'] ?? null);
        }
        if (str_starts_with($path, 'blog')) {
            return static::$memoizedCurrent = ($all['blog'] ?? null);
        }
        if (str_starts_with($path, 'contact') || str_starts_with($path, 'elaqe')) {
            return static::$memoizedCurrent = ($all['contact'] ?? null);
        }
        if (str_starts_with($path, 'about') || str_starts_with($path, 'haqqimizda')) {
            return static::$memoizedCurrent = ($all['about'] ?? null);
        }
        if (str_starts_with($path, 'faq') || str_starts_with($path, 'suallar')) {
            return static::$memoizedCurrent = ($all['faq'] ?? null);
        }
        if (str_starts_with($path, 'compare') || str_starts_with($path, 'muqayise')) {
            return static::$memoizedCurrent = ($all['compare'] ?? null);
        }
        if (str_starts_with($path, 'favorites') || str_starts_with($path, 'sevimliler')) {
            return static::$memoizedCurrent = ($all['favorites'] ?? null);
        }
        if (str_starts_with($path, 'add-property') || str_starts_with($path, 'elan-yerlesdir')) {
            return static::$memoizedCurrent = ($all['add_property'] ?? null);
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
                'page_key' => 'listing_sale',
                'page_name' => 'Satılıq Əmlaklar',
                'route_name' => 'listing.path1',
                'sort_order' => 2,
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
                'page_key' => 'listing',
                'page_name' => 'Bütün Əmlak Elanları (Axtarış Kataloqu)',
                'route_name' => 'listing',
                'sort_order' => 5,
                'title' => [
                    'tr' => 'Kuzey Kıbrıs Tüm Emlak İlanları Kataloğu - KibrisKare',
                    'az' => 'Şimali Kipr Bütün Əmlak Elanları Kataloqu - KibrisKare',
                    'en' => 'All Real Estate & Property Catalog in Northern Cyprus',
                    'ru' => 'Каталог всех объектов недвижимости на Северном Кипре',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs genelinde tüm satılık ve kiralık gayrimenkulleri filtreleyin ve karşılaştırın.',
                    'az' => 'Şimali Kipr üzrə bütün satılıq və kirayə daşınmaz əmlakları filtrləyin və müqayisə edin.',
                    'en' => 'Filter, search and compare all properties for sale and rent in Northern Cyprus.',
                    'ru' => 'Поиск, фильтрация и сравнение всех объектов недвижимости на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'requests',
                'page_name' => 'Əmlak Tələbləri (Axtarıram)',
                'route_name' => 'requests.index',
                'sort_order' => 6,
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
                'sort_order' => 7,
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
                'sort_order' => 8,
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
                'sort_order' => 9,
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
                'sort_order' => 10,
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
                'sort_order' => 11,
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
                'sort_order' => 12,
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
                'sort_order' => 13,
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
                'sort_order' => 14,
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
                'sort_order' => 15,
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
                'sort_order' => 16,
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
                'sort_order' => 17,
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
            self::firstOrCreate(
                ['page_key' => $pageData['page_key']],
                $pageData
            );
        }
    }
}
