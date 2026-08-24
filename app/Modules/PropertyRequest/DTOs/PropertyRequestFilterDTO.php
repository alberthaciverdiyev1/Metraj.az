<?php

namespace App\Modules\PropertyRequest\DTOs;

use App\Modules\PropertyRequest\Enums\RequestType;

class PropertyRequestFilterDTO
{
    public function __construct(
        public ?string $category = null, // buy, rent_monthly, rent_daily, roommate
        public ?RequestType $requestType = null,
        public ?string $propertyType = null,
        public ?float $minBudget = null,
        public ?float $maxBudget = null,
        public ?int $cityId = null,
        public ?int $districtId = null,
        public ?string $rooms = null,
        public ?bool $hasDeed = null,
        public ?bool $mortgageEligible = null,
        public ?string $genderPreference = null,
        public ?string $search = null,
        public ?string $sort = 'newest',
    ) {}

    public static function fromArray(array $data): self
    {
        $category = $data['type'] ?? ($data['category'] ?? null);
        $requestType = !empty($data['request_type']) ? RequestType::tryFrom($data['request_type']) : null;
        
        return new self(
            category: $category,
            requestType: $requestType,
            propertyType: !empty($data['property_type']) ? $data['property_type'] : null,
            minBudget: isset($data['min_budget']) && is_numeric($data['min_budget']) ? (float)$data['min_budget'] : null,
            maxBudget: isset($data['max_budget']) && is_numeric($data['max_budget']) ? (float)$data['max_budget'] : null,
            cityId: !empty($data['city_id']) ? (int)$data['city_id'] : null,
            districtId: !empty($data['district_id']) ? (int)$data['district_id'] : null,
            rooms: !empty($data['rooms']) ? (string)$data['rooms'] : null,
            hasDeed: isset($data['has_deed']) ? filter_var($data['has_deed'], FILTER_VALIDATE_BOOLEAN) : null,
            mortgageEligible: isset($data['mortgage_eligible']) ? filter_var($data['mortgage_eligible'], FILTER_VALIDATE_BOOLEAN) : null,
            genderPreference: !empty($data['gender_preference']) ? $data['gender_preference'] : null,
            search: !empty($data['search']) ? trim($data['search']) : null,
            sort: $data['sort'] ?? 'newest',
        );
    }
}
