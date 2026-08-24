<?php

namespace App\Modules\Roommate\Models;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Roommate\Enums\GenderPreference;
use App\Modules\Roommate\Enums\OccupationPreference;
use App\Modules\Roommate\Enums\RoommateListingType;
use App\Modules\Roommate\Enums\RoommateStatus;
use App\Modules\Shared\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RoommateListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'listing_type',
        'title',
        'slug',
        'description',
        'price',
        'currency',
        'bills_included',
        'city_id',
        'district_id',
        'location_note',
        'latitude',
        'longitude',
        'gender_preference',
        'occupation_preference',
        'smoker_allowed',
        'pet_allowed',
        'stay_duration',
        'available_from',
        'total_roommates',
        'amenities',
        'contact_name',
        'contact_phone',
        'contact_whatsapp',
        'contact_email',
        'status',
        'views_count',
    ];

    protected $casts = [
        'listing_type' => RoommateListingType::class,
        'gender_preference' => GenderPreference::class,
        'occupation_preference' => OccupationPreference::class,
        'status' => RoommateStatus::class,
        'price' => 'decimal:2',
        'bills_included' => 'boolean',
        'smoker_allowed' => 'boolean',
        'pet_allowed' => 'boolean',
        'available_from' => 'date',
        'total_roommates' => 'integer',
        'amenities' => 'array',
        'views_count' => 'integer',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (RoommateListing $listing) {
            if (empty($listing->slug)) {
                $baseSlug = Str::slug($listing->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $listing->slug = $slug;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoommateImage::class)->orderBy('sort_order', 'asc');
    }

    public function mainImage()
    {
        return $this->hasOne(RoommateImage::class)->ofMany([
            'is_main' => 'max',
            'sort_order' => 'min',
            'id' => 'min',
        ]);
    }

    public function getFirstImageUrlAttribute(): string
    {
        $main = $this->images->firstWhere('is_main', true) ?? $this->images->first();
        if ($main) {
            return $main->url;
        }

        return 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', RoommateStatus::Published);
    }

    public function getFormattedPriceAttribute(): string
    {
        $symbol = match ($this->currency) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'TRY' => '₺',
            default => '₼',
        };

        return number_format((float) $this->price, 0, '.', ' ') . ' ' . $symbol;
    }
}
