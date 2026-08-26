<?php

namespace App\Modules\Property\Models;

use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Enums\SellerType;
use App\Modules\Shared\Models\User;
use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Location\Models\Amenity;
use App\Modules\Location\Models\FilterOption;
use App\Modules\Inquiry\Models\Inquiry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $code
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $video
 * @property string $price
 * @property string $currency
 * @property array<string, int|float>|null $prices
 * @property bool $has_document
 * @property bool $has_mortgage
 * @property bool $has_internal_credit
 * @property int|null $area
 * @property int|null $land_area
 * @property int|null $rooms
 * @property int|null $floor
 * @property int|null $total_floors
 * @property string|null $landmark
 * @property string|null $address
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int|null $agency_id
 * @property int|null $agent_id
 * @property int|null $user_id
 * @property int|null $city_id
 * @property int|null $district_id
 * @property \App\Modules\Property\Enums\PropertyStatus $status
 * @property \App\Modules\Property\Enums\SellerType $seller_type
 * @property bool $is_featured
 * @property bool $is_vip
 * @property int $views_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $first_image_url
 * @property-read string|null $video_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Property\Models\PropertyImage> $images
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Inquiry\Models\Inquiry> $inquiries
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Location\Models\FilterOption> $filterOptions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Location\Models\Amenity> $amenities
 * @property-read \App\Modules\Shared\Models\User|null $user
 * @property-read \App\Modules\Agency\Models\Agency|null $agency
 * @property-read \App\Modules\Agency\Models\Agent|null $agent
 * @property-read \App\Modules\Location\Models\City|null $city
 * @property-read \App\Modules\Location\Models\District|null $district
 */
class Property extends Model
{
    use SoftDeletes;

    /**
     * Kütləvi doldurula bilən sütunlar (Mass Assignable)
     */
    protected $fillable = [
        // Əsas identifikasiya və başlıq
        'code',                // Elanın unikal nömrəsi (Məs: 102450)
        'title',               // Elanın başlığı
        'slug',                // URL üçün unikal slug (Məs: nasimide-3-otaqli-menzil)
        'description',         // Əmlak haqqında ətraflı təsvir/mətn
        'video',               // Əmlakın video faylının yolu (Məs: properties/videos/xyz.mp4)

        // Satıcı növü (Enum: owner, agency, complex)
        'seller_type',         // Satıcı növü (Mülkiyyətçi / Agentlik / Kompleks)

        // Sənəd və Maliyyə statusları
        'has_document',        // Çıxarış var (Kupça) - true/false
        'has_mortgage',        // İpotekaya yararlıdır - true/false
        'has_internal_credit', // Daxili kredit mümkündür - true/false

        // Qiymət parametrləri
        'price',               // Əmlakın əsas qiyməti (GBP / Funt Sterlinq)
        'currency',            // Əsas valyuta növü (GBP, AZN, USD, EUR)
        'prices',              // Bütün valyutalarda qiymətlər (JSON: GBP, USD, EUR, AZN, TRY, RUB, AED)

        // Ölçü və Mərtəbə parametrləri
        'area',                // Əmlakın sahəsi (m² ilə)
        'land_area',           // Torpaq sahəsi (sot ilə)
        'rooms',               // Otaq sayı (1, 2, 3, 4, 5+)
        'floor',               // Yerləşdiyi mərtəbə
        'total_floors',        // Binanın ümumi mərtəbə sayı

        // Yerləşmə və Ünvan (Şəhər, Rayon, Nişangah və Ünvan)
        'city_id',             // Aid olduğu şəhərin ID-si
        'district_id',         // Aid olduğu rayonun / bölqənin ID-si
        'landmark',            // Nişangah / Yaxınlıqdakı tanınmış obyekt (Məs: Port Baku, BDU)
        'address',             // Dəqiq küçə və bina ünvanı
        'latitude',            // Xəritə üçün enlik koordinatı
        'longitude',           // Xəritə üçün uzunluq koordinatı

        // Sahiblik və Əlaqə
        'agency_id',           // Elanın aid olduğu daşınmaz əmlak agentliyinin ID-si (varsa)
        'agent_id',            // Elana cavabdeh olan rieltorun / agentin ID-si (varsa)
        'user_id',             // Elanı əlavə edən istifadəçinin ID-si

        // Moderasiya və Statistika
        'status',              // Elanın statusu (Qaralama, Təsdiq gözləyir, Dərc olunub, Satılıb və s.)
        'is_featured',         // Seçilmiş / Önə çıxarılan elan - true/false
        'is_vip',              // VIP elan statusu - true/false
        'views_count',         // Baxış sayı
    ];

    /**
     * Məlumat tiplərinin avtomatik çevrilməsi (Type Casting)
     */
    protected $casts = [
        'status' => PropertyStatus::class,             // Elan statusu Enum-a çevrilir
        'seller_type' => SellerType::class,           // Satıcı növü Enum-a çevrilir
        'has_document' => 'boolean',                  // Kupça var (true/false)
        'has_mortgage' => 'boolean',                  // İpoteka var (true/false)
        'has_internal_credit' => 'boolean',           // Daxili kredit var (true/false)
        'price' => 'decimal:2',                       // Qiymət onluq kəsr kimi saxlanılır
        'prices' => 'array',                          // Bütün valyuta qiymətləri massiv/JSON kimi
        'latitude' => 'decimal:8',                    // Xəritə enliyi dəqiq koordinat kimi
        'longitude' => 'decimal:8',                   // Xəritə uzunluğu dəqiq koordinat kimi
        'is_featured' => 'boolean',                   // Seçilmiş elan (true/false)
        'is_vip' => 'boolean',                        // VIP elan (true/false)
        'views_count' => 'integer',                   // Baxış sayı tam ədəd kimi
    ];

    /**
     * Cari dilə uyğun elan başlığı (Lokallaşdırılmış)
     */
    public function getTitleAttribute($value): string
    {
        if (is_array($value)) {
            $locale = app()->getLocale();
            return $value[$locale] ?? $value['tr'] ?? $value['az'] ?? $value['en'] ?? $value['ru'] ?? (string) reset($value);
        }

        if (is_string($value) && str_starts_with(trim($value), '{')) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $locale = app()->getLocale();
                return $decoded[$locale] ?? $decoded['tr'] ?? $decoded['az'] ?? $decoded['en'] ?? $decoded['ru'] ?? (string) reset($decoded);
            }
        }

        return (string) $value;
    }

    /**
     * Elan başlığını çoxdilli JSON kimi saxlayır.
     * Düz mətn göndərildikdə mövcud çoxdilli JSON qorunur və yalnız cari dil
     * güncəllənir — beləliklə admin redaktəsi digər dilləri silmir.
     */
    public function setTitleAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['title'] = json_encode($value, JSON_UNESCAPED_UNICODE);
            return;
        }

        $str = (string) $value;
        if (str_starts_with(trim($str), '{') && is_array(json_decode($str, true))) {
            $this->attributes['title'] = $str;
            return;
        }

        $existing = $this->attributes['title'] ?? null;
        $decoded = is_array($existing)
            ? $existing
            : (is_string($existing) ? json_decode($existing, true) : null);

        if (is_array($decoded) && !empty($decoded)) {
            $decoded[app()->getLocale()] = $str;
            $this->attributes['title'] = json_encode($decoded, JSON_UNESCAPED_UNICODE);
            return;
        }

        $this->attributes['title'] = json_encode([
            'az' => $str,
            'tr' => $str,
            'en' => $str,
            'ru' => $str,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Cari dilə uyğun elan təsviri (Lokallaşdırılmış)
     */
    public function getDescriptionAttribute($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            $locale = app()->getLocale();
            return $value[$locale] ?? $value['tr'] ?? $value['az'] ?? $value['en'] ?? $value['ru'] ?? (string) reset($value);
        }

        if (is_string($value) && str_starts_with(trim($value), '{')) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $locale = app()->getLocale();
                return $decoded[$locale] ?? $decoded['tr'] ?? $decoded['az'] ?? $decoded['en'] ?? $decoded['ru'] ?? (string) reset($decoded);
            }
        }

        return (string) $value;
    }

    /**
     * Elan təsvirini çoxdilli JSON kimi saxlayır.
     * Düz mətn göndərildikdə mövcud çoxdilli JSON qorunur və yalnız cari dil
     * güncəllənir — beləliklə admin redaktəsi digər dilləri silmir.
     */
    public function setDescriptionAttribute($value): void
    {
        if ($value === null) {
            $this->attributes['description'] = null;
            return;
        }

        if (is_array($value)) {
            $this->attributes['description'] = json_encode($value, JSON_UNESCAPED_UNICODE);
            return;
        }

        $str = (string) $value;
        if (str_starts_with(trim($str), '{') && is_array(json_decode($str, true))) {
            $this->attributes['description'] = $str;
            return;
        }

        $existing = $this->attributes['description'] ?? null;
        $decoded = is_array($existing)
            ? $existing
            : (is_string($existing) ? json_decode($existing, true) : null);

        if (is_array($decoded) && !empty($decoded)) {
            $decoded[app()->getLocale()] = $str;
            $this->attributes['description'] = json_encode($decoded, JSON_UNESCAPED_UNICODE);
            return;
        }

        $this->attributes['description'] = json_encode([
            'az' => $str,
            'tr' => $str,
            'en' => $str,
            'ru' => $str,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Model hadisələrinin qeydiyyatı (Model Boot Lifecycle)
     */
    protected static function boot()
    {
        parent::boot();

        // Yeni elan yaradılan zaman avtomatik icra olunur
        static::creating(function ($model) {
            // Əgər slug boşdursa, başlıqdan unikal slug yaradılır
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title) . '-' . Str::random(6);
            }
            // Əgər elan kodu boşdursa, 6 rəqəmli unikal kod generasiya olunur
            // (code kolonu UNIQUE-dur; çakışma olarsa UniqueConstraintViolationException
            //  fırlanmaması üçün mövcud kod yoxlanıb təkrar yaradılır)
            if (empty($model->code)) {
                $model->code = static::generateUniqueCode();
            }
        });
    }

    /**
     * 6 rəqəmli unikal elan kodu generasiya edir.
     * Bütün oluşturma yolları (Filament, web controller, seeder) üçün tək qaynaqdır.
     */
    public static function generateUniqueCode(): string
    {
        do {
            $code = (string) mt_rand(100000, 999999);
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * Elanı əlavə edən istifadəçi ilə əlaqə
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Elanın aid olduğu agentlik ilə əlaqə (Varsa)
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Elana cavabdeh olan rieltor / agent ilə əlaqə (Varsa)
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Elanın aid olduğu şəhər
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Elanın aid olduğu rayon / bölqə
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Əmlakın xüsusiyyətləri / təchizatları ilə əlaqə (Qaz, Lift, Parkinq və s.) - Çoxun çoxa əlaqəsi
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity');
    }

    /**
     * Bu elan üzrə daxil olan müştəri müraciətləri (Leads / Inquiries)
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Əmlakın şəkilləri (ayrı bir cədvəldə, ardıcıllıqla)
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order', 'asc');
    }

    /**
     * Əmlaka təyin edilmiş bütün dinamik filtrlər və seçimlər (Əmlak növü, Alqı-satqı, Tikili, Təmir, Şəhər, Rayon və s.)
     */
    public function filterOptions(): BelongsToMany
    {
        return $this->belongsToMany(FilterOption::class, 'property_filter_options');
    }

    /**
     * Əmlakın ilk şəklinin URL-i
     */
    public function getFirstImageUrlAttribute(): string
    {
        $firstImage = $this->images->first();
        if ($firstImage && !empty($firstImage->url)) {
            return $firstImage->url;
        }
        return \App\Modules\Property\Models\PropertyImage::FALLBACK_IMAGE;
    }

    /**
     * Əmlakın video URL-i (yerli storage və ya xarici link)
     */
    public function getVideoUrlAttribute(): ?string
    {
        if (empty($this->video)) {
            return null;
        }

        if (str_starts_with($this->video, 'http://') || str_starts_with($this->video, 'https://') || str_starts_with($this->video, '/')) {
            return $this->video;
        }

        return asset('storage/' . $this->video);
    }

    /**
     * Müəyyən bir açara (Key) aid olan filtr seçimini qaytarır
     */
    public function getFilterOption(string $key): ?FilterOption
    {
        return $this->filterOptions->first(fn ($option) => $option->filter?->key?->value === $key);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function compares(): HasMany
    {
        return $this->hasMany(Compare::class);
    }
}
