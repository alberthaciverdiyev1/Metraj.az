<?php

namespace App\Modules\PropertyRequest\Services;

use App\Modules\PropertyRequest\DTOs\PropertyRequestFilterDTO;
use App\Modules\PropertyRequest\Enums\RequestStatus;
use App\Modules\PropertyRequest\Enums\RequestType;
use App\Modules\PropertyRequest\Models\PropertyRequest;
use App\Modules\PropertyRequest\Models\PropertyRequestImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PropertyRequestService
{
    public function paginate(PropertyRequestFilterDTO $filter, int $perPage = 18): LengthAwarePaginator
    {
        $query = PropertyRequest::query()
            ->published()
            ->with(['city', 'district', 'images', 'user']);

        if ($filter->category) {
            if ($filter->category === 'buy') {
                $query->where('request_type', RequestType::Buy);
            } elseif ($filter->category === 'rent_monthly' || $filter->category === 'rent') {
                $query->where('request_type', RequestType::RentMonthly);
            } elseif ($filter->category === 'rent_daily' || $filter->category === 'daily') {
                $query->where('request_type', RequestType::RentDaily);
            } elseif ($filter->category === 'roommate') {
                $query->whereIn('request_type', [RequestType::RoommateHave, RequestType::RoommateNeed]);
            }
        } elseif ($filter->requestType) {
            $query->where('request_type', $filter->requestType);
        }

        if ($filter->propertyType) {
            $query->where('property_type', $filter->propertyType);
        }

        if ($filter->minBudget !== null) {
            $query->where('budget_max', '>=', $filter->minBudget);
        }

        if ($filter->maxBudget !== null) {
            $query->where('budget_max', '<=', $filter->maxBudget);
        }

        if ($filter->cityId) {
            $query->where('city_id', $filter->cityId);
        }

        if ($filter->districtId) {
            $query->where('district_id', $filter->districtId);
        }

        if ($filter->rooms) {
            $query->where('rooms', $filter->rooms);
        }

        if ($filter->hasDeed !== null) {
            $query->where('has_deed', $filter->hasDeed);
        }

        if ($filter->mortgageEligible !== null) {
            $query->where('mortgage_eligible', $filter->mortgageEligible);
        }

        if ($filter->genderPreference) {
            $query->where(function ($q) use ($filter) {
                $q->where('gender_preference', $filter->genderPreference)
                  ->orWhere('gender_preference', 'any')
                  ->orNull('gender_preference');
            });
        }

        if ($filter->search) {
            $search = '%' . $filter->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('description', 'like', $search)
                  ->orWhere('location_note', 'like', $search)
                  ->orWhere('property_type', 'like', $search);
            });
        }

        match ($filter->sort) {
            'budget_asc' => $query->orderBy('budget_max', 'asc'),
            'budget_desc' => $query->orderBy('budget_max', 'desc'),
            'views' => $query->orderBy('views_count', 'desc'),
            default => $query->latest('id'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    public function store(array $data, array $uploadedImages = []): PropertyRequest
    {
        return DB::transaction(function () use ($data, $uploadedImages) {
            if (empty($data['user_id']) && auth()->check()) {
                $data['user_id'] = auth()->id();
            }

            $data['status'] = RequestStatus::Published;

            $request = PropertyRequest::create($data);

            if (!empty($uploadedImages)) {
                $sort = 0;
                foreach ($uploadedImages as $file) {
                    if ($file instanceof UploadedFile) {
                        $path = $file->store('property_requests', 'public');
                        PropertyRequestImage::create([
                            'property_request_id' => $request->id,
                            'image_path' => $path,
                            'sort_order' => $sort,
                            'is_main' => $sort === 0,
                        ]);
                        $sort++;
                    }
                }
            }

            return $request->fresh(['images', 'city', 'district']);
        });
    }

    public function incrementViews(PropertyRequest $request): void
    {
        $request->increment('views_count');
    }
}
