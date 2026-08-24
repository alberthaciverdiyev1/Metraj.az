<?php

namespace App\Modules\Location\Services;

use App\Modules\Location\Enums\FilterKey;
use App\Modules\Location\Models\Amenity;
use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Location\Models\Filter;
use App\Modules\Location\Models\FilterOption;
use Illuminate\Database\Eloquent\Collection;

/**
 * Şəhər, rayon, filtr və təchizat (amenity) kimi seçim məlumatlarını
 * form və API-lər üçün hazırlayan servis.
 */
class LocationService
{
    /**
     * Aktiv şəhərləri aktiv rayonları ilə birlikdə qaytarır.
     *
     * @return Collection<int, City>
     */
    public function activeCities(): Collection
    {
        return City::with('activeDistricts')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Bütün filtrləri options-ları ilə key-ləyərək qaytarır (elan formu üçün).
     *
     * @return Collection<string, Filter>
     */
    public function allFiltersKeyed(): Collection
    {
        return Filter::with('options')->get()->keyBy(fn (Filter $filter) => $filter->key->value ?? (string) $filter->key);
    }

    /**
     * Əsas axtarışda göstərilən əmlak növü seçimləri.
     *
     * @return Collection<int, FilterOption>
     */
    public function propertyTypeOptions(): Collection
    {
        $filterId = Filter::where('key', FilterKey::PropertyType->value)->value('id');

        if (!$filterId) {
            return new Collection();
        }

        return FilterOption::where('filter_id', $filterId)->get();
    }

    /**
     * Əlavə filtr pəncərəsində göstərilən dinamik filtrlər
     * (əsas axtarışda olan deal_type və property_type istisna olunur).
     *
     * @return Collection<int, Filter>
     */
    public function dynamicFilters(): Collection
    {
        return Filter::with('options')
            ->where('is_active', true)
            ->whereNotIn('key', [
                FilterKey::DealType->value,
                FilterKey::PropertyType->value,
            ])
            ->get();
    }

    /**
     * Bütün təchizatlar (amenities) ad sırası ilə.
     *
     * @return Collection<int, Amenity>
     */
    public function amenities(): Collection
    {
        return Amenity::orderBy('name')->get();
    }

    /**
     * Təchizatlar (amenities) səhifələmə ilə (Load More üçün).
     */
    public function paginateAmenities(int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Amenity::where('is_active', true)->orWhereNull('is_active')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Bir filtr seçimini ID ilə qaytarır.
     */
    public function filterOptionById(int $id): ?FilterOption
    {
        return FilterOption::find($id);
    }

    /**
     * Şəhər + rayon adlarından lokasiya etiketi qurur (elan başlığı üçün).
     */
    public function locationLabel(int $cityId, ?int $districtId = null): string
    {
        $city = City::find($cityId);
        $district = $districtId ? District::find($districtId) : null;

        if ($district) {
            return $district->name['az'] ?? $district->name['tr'] ?? '';
        }

        return $city?->name['az'] ?? $city?->name['tr'] ?? '';
    }
}
