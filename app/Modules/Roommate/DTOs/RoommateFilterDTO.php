<?php

namespace App\Modules\Roommate\DTOs;

use App\Modules\Roommate\Enums\GenderPreference;
use App\Modules\Roommate\Enums\RoommateListingType;

class RoommateFilterDTO
{
    public function __construct(
        public ?RoommateListingType $listingType = null,
        public ?GenderPreference $genderPreference = null,
        public ?float $minPrice = null,
        public ?float $maxPrice = null,
        public ?int $cityId = null,
        public ?int $districtId = null,
        public ?bool $billsIncluded = null,
        public ?bool $smokerAllowed = null,
        public ?bool $petAllowed = null,
        public ?string $search = null,
        public ?string $sort = 'newest',
    ) {}

    public static function fromArray(array $data): self
    {
        $listingType = !empty($data['listing_type']) ? RoommateListingType::tryFrom($data['listing_type']) : null;
        $gender = !empty($data['gender_preference']) ? GenderPreference::tryFrom($data['gender_preference']) : (!empty($data['gender']) ? GenderPreference::tryFrom($data['gender']) : null);
        
        return new self(
            listingType: $listingType,
            genderPreference: $gender,
            minPrice: isset($data['min_price']) && is_numeric($data['min_price']) ? (float) $data['min_price'] : null,
            maxPrice: isset($data['max_price']) && is_numeric($data['max_price']) ? (float) $data['max_price'] : null,
            cityId: !empty($data['city_id']) ? (int) $data['city_id'] : null,
            districtId: !empty($data['district_id']) ? (int) $data['district_id'] : null,
            billsIncluded: isset($data['bills_included']) ? filter_var($data['bills_included'], FILTER_VALIDATE_BOOLEAN) : null,
            smokerAllowed: isset($data['smoker_allowed']) ? filter_var($data['smoker_allowed'], FILTER_VALIDATE_BOOLEAN) : null,
            petAllowed: isset($data['pet_allowed']) ? filter_var($data['pet_allowed'], FILTER_VALIDATE_BOOLEAN) : null,
            search: !empty($data['search']) ? trim($data['search']) : null,
            sort: $data['sort'] ?? 'newest',
        );
    }
}
