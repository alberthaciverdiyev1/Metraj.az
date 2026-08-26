<?php

namespace App\Modules\Roommate\Services;

use App\Modules\Roommate\DTOs\RoommateFilterDTO;
use App\Modules\Roommate\Enums\RoommateStatus;
use App\Modules\Roommate\Models\RoommateImage;
use App\Modules\Roommate\Models\RoommateListing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RoommateService
{
    public function paginate(RoommateFilterDTO $filter, int $perPage = 18): LengthAwarePaginator
    {
        $query = RoommateListing::query()
            ->published()
            ->with(['city', 'district', 'images', 'user']);

        if ($filter->listingType) {
            $query->where('listing_type', $filter->listingType);
        }

        if ($filter->genderPreference) {
            $query->where(function ($q) use ($filter) {
                $q->where('gender_preference', $filter->genderPreference)
                  ->orWhere('gender_preference', 'any');
            });
        }

        if ($filter->minPrice !== null) {
            $query->where('price', '>=', $filter->minPrice);
        }

        if ($filter->maxPrice !== null) {
            $query->where('price', '<=', $filter->maxPrice);
        }

        if ($filter->cityId) {
            $query->where('city_id', $filter->cityId);
        }

        if ($filter->districtId) {
            $query->where('district_id', $filter->districtId);
        }

        if ($filter->billsIncluded !== null) {
            $query->where('bills_included', $filter->billsIncluded);
        }

        if ($filter->smokerAllowed !== null) {
            $query->where('smoker_allowed', $filter->smokerAllowed);
        }

        if ($filter->petAllowed !== null) {
            $query->where('pet_allowed', $filter->petAllowed);
        }

        if ($filter->search) {
            $search = '%' . $filter->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('description', 'like', $search)
                  ->orWhere('location_note', 'like', $search);
            });
        }

        match ($filter->sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'views' => $query->orderBy('views_count', 'desc'),
            default => $query->latest('id'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    public function store(array $data, array $uploadedImages = []): RoommateListing
    {
        return DB::transaction(function () use ($data, $uploadedImages) {
            if (empty($data['user_id']) && auth()->check()) {
                $data['user_id'] = auth()->id();
            }

            $data['status'] = RoommateStatus::Published;

            $listing = RoommateListing::create($data);

            if (!empty($uploadedImages)) {
                $optimizer = app(\App\Modules\Shared\Services\ImageOptimizerService::class);
                $sort = 0;
                foreach ($uploadedImages as $file) {
                    if ($file instanceof UploadedFile) {
                        $path = $optimizer->saveWithWatermark($file, 'roommates');
                        RoommateImage::create([
                            'roommate_listing_id' => $listing->id,
                            'image_path' => $path,
                            'sort_order' => $sort,
                            'is_main' => $sort === 0,
                        ]);
                        $sort++;
                    }
                }
            }

            return $listing->fresh(['images', 'city', 'district']);
        });
    }

    public function incrementViews(RoommateListing $listing): void
    {
        $listing->increment('views_count');
    }
}
