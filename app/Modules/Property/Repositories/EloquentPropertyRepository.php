<?php

namespace App\Modules\Property\Repositories;

use App\Modules\Property\DTOs\CreatePropertyDTO;
use App\Modules\Property\DTOs\PropertyFilterDTO;
use App\Modules\Location\Enums\FilterKey;
use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Repositories\PropertyRepositoryInterface;
use App\Modules\Property\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentPropertyRepository implements PropertyRepositoryInterface
{
    public function findById(int $id): ?Property
    {
        return Property::with(['agency', 'agent', 'amenities', 'filterOptions.filter', 'images'])->find($id);
    }

    public function findBySlug(string $slug): ?Property
    {
        return Property::with(['agency', 'agent', 'amenities', 'filterOptions.filter', 'images'])
            ->where('slug', $slug)
            ->first();
    }

    public function findByCode(string $code): ?Property
    {
        return Property::with(['agency', 'agent', 'amenities', 'filterOptions.filter', 'images'])
            ->where('code', $code)
            ->first();
    }

    public function findPublishedBySlug(string $slug): ?Property
    {
        return Property::with(['agency', 'agent', 'amenities', 'filterOptions.filter', 'images'])
            ->where('slug', $slug)
            ->where('status', PropertyStatus::Published)
            ->first();
    }

    public function create(CreatePropertyDTO $dto): Property
    {
        $property = Property::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'code' => $dto->code,
            'slug' => $dto->slug,
            'has_document' => $dto->hasDocument,
            'has_mortgage' => $dto->hasMortgage,
            'has_internal_credit' => $dto->hasInternalCredit,
            'price' => $dto->price,
            'currency' => $dto->currency,
            'prices' => $dto->prices,
            'views_count' => $dto->viewsCount,
            'area' => $dto->area,
            'land_area' => $dto->landArea,
            'rooms' => $dto->rooms,
            'floor' => $dto->floor,
            'total_floors' => $dto->totalFloors,
            'city_id' => $dto->cityId,
            'district_id' => $dto->districtId,
            'landmark' => $dto->landmark,
            'address' => $dto->address,
            'latitude' => $dto->latitude,
            'longitude' => $dto->longitude,
            'agency_id' => $dto->agencyId,
            'agent_id' => $dto->agentId,
            'user_id' => $dto->userId,
            'seller_type' => $dto->sellerType ?? ($dto->agencyId ? \App\Modules\Property\Enums\SellerType::Agency : \App\Modules\Property\Enums\SellerType::Owner),
            'status' => $dto->status,
            'is_featured' => $dto->isFeatured,
            'is_vip' => $dto->isVip,
        ]);

        if (!empty($dto->filterOptionIds)) {
            $property->filterOptions()->sync($dto->filterOptionIds);
        }

        if (!empty($dto->amenityIds)) {
            $property->amenities()->sync($dto->amenityIds);
        }

        return $property->load([
            'agency', 'agent', 'amenities', 'filterOptions.filter', 'images',
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $property = Property::find($id);
        if (!$property) {
            return false;
        }

        if (isset($data['filter_option_ids'])) {
            $property->filterOptions()->sync($data['filter_option_ids']);
            unset($data['filter_option_ids']);
        }

        if (isset($data['amenity_ids'])) {
            $property->amenities()->sync($data['amenity_ids']);
            unset($data['amenity_ids']);
        }

        return $property->update($data);
    }

    public function delete(int $id): bool
    {
        return (bool) Property::destroy($id);
    }

    public function paginate(PropertyFilterDTO $filter, int $perPage = 15): LengthAwarePaginator
    {
        $query = Property::query()->with(['agency', 'agent', 'amenities', 'filterOptions.filter', 'images']);

        if ($filter->status) {
            $query->where('status', $filter->status);
        }

        if (!empty($filter->search)) {
            $search = $filter->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('landmark', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if (!empty($filter->code)) {
            $query->where('code', 'like', "%{$filter->code}%");
        }

        if ($filter->propertyType) {
            $query->whereHas('filterOptions', function ($q) use ($filter) {
                $q->where('value', $filter->propertyType->value);
            });
        }

        if ($filter->dealType) {
            $query->whereHas('filterOptions', function ($q) use ($filter) {
                $q->where('value', $filter->dealType->value);
            });
        }

        if ($filter->buildingType) {
            $query->whereHas('filterOptions', function ($q) use ($filter) {
                $q->where('value', $filter->buildingType->value);
            });
        }

        if ($filter->repairType) {
            $query->whereHas('filterOptions', function ($q) use ($filter) {
                $q->where('value', $filter->repairType->value);
            });
        }

        if ($filter->sellerType) {
            $query->where('seller_type', $filter->sellerType->value);
        }

        if ($filter->onlyComplexes) {
            $query->where('seller_type', \App\Modules\Property\Enums\SellerType::Complex->value);
        }

        if ($filter->hasDocument !== null) {
            $query->where('has_document', $filter->hasDocument);
        }

        if ($filter->hasMortgage !== null) {
            $query->where('has_mortgage', $filter->hasMortgage);
        }

        if ($filter->hasInternalCredit !== null) {
            $query->where('has_internal_credit', $filter->hasInternalCredit);
        }

        if ($filter->minPrice !== null) {
            $query->where('price', '>=', $filter->minPrice);
        }

        if ($filter->maxPrice !== null) {
            $query->where('price', '<=', $filter->maxPrice);
        }

        if ($filter->minArea !== null) {
            $query->where('area', '>=', $filter->minArea);
        }

        if ($filter->maxArea !== null) {
            $query->where('area', '<=', $filter->maxArea);
        }

        if ($filter->rooms !== null) {
            if ($filter->rooms >= 5) {
                $query->where('rooms', '>=', 5);
            } else {
                $query->where('rooms', $filter->rooms);
            }
        }

        if ($filter->minFloor !== null) {
            $query->where('floor', '>=', $filter->minFloor);
        }

        if ($filter->maxFloor !== null) {
            $query->where('floor', '<=', $filter->maxFloor);
        }

        if ($filter->notFirstFloor) {
            $query->where('floor', '>', 1);
        }

        if ($filter->notLastFloor) {
            $query->whereColumn('floor', '<', 'total_floors');
        }

        if ($filter->onlyLastFloor) {
            $query->whereColumn('floor', '=', 'total_floors');
        }

        if ($filter->cityId !== null) {
            $query->where('city_id', $filter->cityId);
        }

        if ($filter->districtId !== null) {
            $query->where('district_id', $filter->districtId);
        }

        if (!empty($filter->landmark)) {
            $query->where('landmark', 'like', "%{$filter->landmark}%");
        }

        if ($filter->agencyId !== null) {
            $query->where('agency_id', $filter->agencyId);
        }

        if ($filter->agentId !== null) {
            $query->where('agent_id', $filter->agentId);
        }

        if (!empty($filter->filterOptionIds)) {
            foreach ($filter->filterOptionIds as $optionId) {
                $query->whereHas('filterOptions', function ($q) use ($optionId) {
                    $q->where('filter_options.id', $optionId);
                });
            }
        }

        if ($filter->isFeatured !== null) {
            $query->where('is_featured', $filter->isFeatured);
        }

        if ($filter->isVip !== null) {
            $query->where('is_vip', $filter->isVip);
        }

        $sortBy = in_array($filter->sortBy, ['price', 'created_at', 'views_count', 'area']) 
            ? $filter->sortBy 
            : 'created_at';

        $query->orderBy($sortBy, $filter->sortDirection);

        return $query->paginate($perPage);
    }

    public function similar(Property $property, int $limit = 3): Collection
    {
        $propertyTypeOpt = $property->filterOptions
            ->first(fn ($opt) => $opt->filter?->key?->value === FilterKey::PropertyType->value);

        $query = Property::with(['images', 'city', 'district', 'agency', 'agent.user', 'filterOptions.filter'])
            ->where('id', '!=', $property->id)
            ->where('status', PropertyStatus::Published);

        if ($propertyTypeOpt) {
            $query->whereHas('filterOptions', fn ($q) => $q->where('filter_options.id', $propertyTypeOpt->id));
        } elseif ($property->city_id) {
            $query->where('city_id', $property->city_id);
        }

        $similar = $query->latest('id')->limit($limit)->get();

        // Əgər limit qədər deyilsə, digər sonuncu dərc edilmiş elanlarla tamamlayırıq
        if ($similar->count() < $limit) {
            $excludeIds = $similar->pluck('id')->push($property->id)->toArray();
            $fillCount = $limit - $similar->count();

            $more = Property::with(['images', 'city', 'district', 'agency', 'agent.user', 'filterOptions.filter'])
                ->whereNotIn('id', $excludeIds)
                ->where('status', PropertyStatus::Published)
                ->latest('id')
                ->limit($fillCount)
                ->get();

            $similar = $similar->concat($more);
        }

        return $similar;
    }

    public function getFeatured(int $limit = 6): Collection
    {
        return Property::with(['agency', 'agent', 'amenities', 'filterOptions.filter', 'images'])
            ->where('is_featured', true)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getVip(int $limit = 6): Collection
    {
        return Property::with(['agency', 'agent', 'amenities', 'filterOptions.filter', 'images'])
            ->where('is_vip', true)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function incrementViews(int $id): void
    {
        Property::where('id', $id)->increment('views_count');
    }
}
