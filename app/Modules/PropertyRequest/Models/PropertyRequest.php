<?php

namespace App\Modules\PropertyRequest\Models;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\PropertyRequest\Enums\RequestStatus;
use App\Modules\PropertyRequest\Enums\RequestType;
use App\Modules\Shared\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PropertyRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'request_type',
        'property_type',
        'title',
        'slug',
        'description',
        'budget_min',
        'budget_max',
        'currency',
        'bills_included',
        'rooms',
        'area_min',
        'area_max',
        'city_id',
        'district_id',
        'location_note',
        'has_deed',
        'mortgage_eligible',
        'repair_status',
        'furnished_status',
        'occupancy_type',
        'gender_preference',
        'occupation_preference',
        'smoker_allowed',
        'pet_allowed',
        'stay_duration',
        'move_in_date',
        'amenities',
        'contact_name',
        'contact_phone',
        'contact_whatsapp',
        'contact_email',
        'status',
        'views_count',
    ];

    protected $casts = [
        'request_type' => RequestType::class,
        'status' => RequestStatus::class,
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'bills_included' => 'boolean',
        'has_deed' => 'boolean',
        'mortgage_eligible' => 'boolean',
        'smoker_allowed' => 'boolean',
        'pet_allowed' => 'boolean',
        'move_in_date' => 'date',
        'amenities' => 'array',
        'views_count' => 'integer',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (PropertyRequest $request) {
            if (empty($request->slug)) {
                $baseSlug = Str::slug($request->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $request->slug = $slug;
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
        return $this->hasMany(PropertyRequestImage::class)->orderBy('sort_order', 'asc');
    }

    public function getFirstImageUrlAttribute(): ?string
    {
        $main = $this->images->firstWhere('is_main', true) ?? $this->images->first();
        if ($main) {
            return $main->url;
        }

        return null;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', RequestStatus::Published);
    }

    public function getFormattedBudgetAttribute(): string
    {
        $symbol = match (strtoupper($this->currency ?? 'GBP')) {
            'USD' => '$',
            'EUR' => '€',
            'AZN' => '₼',
            'TRY' => '₺',
            'RUB' => '₽',
            'AED' => 'AED',
            default => '£',
        };

        if ($this->budget_min && $this->budget_min > 0 && $this->budget_min < $this->budget_max) {
            return number_format((float)$this->budget_min, 0, '.', ' ') . ' - ' . number_format((float)$this->budget_max, 0, '.', ' ') . ' ' . $symbol;
        }

        return number_format((float)$this->budget_max, 0, '.', ' ') . ' ' . $symbol;
    }
}
