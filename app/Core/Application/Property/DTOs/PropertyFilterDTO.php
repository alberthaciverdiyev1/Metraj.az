<?php

namespace App\Core\Application\Property\DTOs;

use App\Core\Domain\Property\Enums\BuildingType;
use App\Core\Domain\Property\Enums\DealType;
use App\Core\Domain\Property\Enums\PropertyStatus;
use App\Core\Domain\Property\Enums\PropertyType;
use App\Core\Domain\Property\Enums\RepairType;
use App\Core\Domain\Property\Enums\SellerType;

readonly class PropertyFilterDTO
{
    public function __construct(
        public ?PropertyType $propertyType = null,
        public ?DealType $dealType = null,
        public ?BuildingType $buildingType = null,
        public ?RepairType $repairType = null,
        public ?SellerType $sellerType = null,
        public ?PropertyStatus $status = PropertyStatus::Published,
        public ?bool $hasDocument = null,
        public ?bool $hasMortgage = null,
        public ?bool $hasInternalCredit = null,
        public ?float $minPrice = null,
        public ?float $maxPrice = null,
        public ?int $minArea = null,
        public ?int $maxArea = null,
        public ?int $rooms = null,
        public ?int $minFloor = null,
        public ?int $maxFloor = null,
        public bool $notFirstFloor = false,
        public bool $notLastFloor = false,
        public bool $onlyLastFloor = false,
        public ?int $cityId = null,
        public ?int $districtId = null,
        public ?string $landmark = null,
        public ?int $agencyId = null,
        public ?int $agentId = null,
        public ?string $code = null,
        public bool $onlyComplexes = false,
        public ?bool $isFeatured = null,
        public ?bool $isVip = null,
        public array $filterOptionIds = [],
        public ?string $search = null,
        public string $sortBy = 'created_at',
        public string $sortDirection = 'desc',
    ) {}

    public static function fromArray(array $data): self
    {
        $filterOptionIds = [];
        if (!empty($data['filter_options']) && is_array($data['filter_options'])) {
            $filterOptionIds = array_map('intval', array_filter($data['filter_options']));
        }

        return new self(
            propertyType: !empty($data['property_type']) ? PropertyType::tryFrom($data['property_type']) : null,
            dealType: !empty($data['deal_type']) ? DealType::tryFrom($data['deal_type']) : null,
            buildingType: !empty($data['building_type']) ? BuildingType::tryFrom($data['building_type']) : null,
            repairType: !empty($data['repair_type']) ? RepairType::tryFrom($data['repair_type']) : null,
            sellerType: !empty($data['seller_type']) ? SellerType::tryFrom($data['seller_type']) : null,
            status: !empty($data['status']) ? PropertyStatus::tryFrom($data['status']) : PropertyStatus::Published,
            hasDocument: isset($data['has_document']) && $data['has_document'] !== '' ? (bool) $data['has_document'] : null,
            hasMortgage: isset($data['has_mortgage']) && $data['has_mortgage'] !== '' ? (bool) $data['has_mortgage'] : null,
            hasInternalCredit: isset($data['has_internal_credit']) && $data['has_internal_credit'] !== '' ? (bool) $data['has_internal_credit'] : null,
            minPrice: isset($data['min_price']) && $data['min_price'] !== '' ? (float) $data['min_price'] : null,
            maxPrice: isset($data['max_price']) && $data['max_price'] !== '' ? (float) $data['max_price'] : null,
            minArea: isset($data['min_area']) && $data['min_area'] !== '' ? (int) $data['min_area'] : null,
            maxArea: isset($data['max_area']) && $data['max_area'] !== '' ? (int) $data['max_area'] : null,
            rooms: isset($data['rooms']) && $data['rooms'] !== '' ? (int) $data['rooms'] : null,
            minFloor: isset($data['min_floor']) && $data['min_floor'] !== '' ? (int) $data['min_floor'] : null,
            maxFloor: isset($data['max_floor']) && $data['max_floor'] !== '' ? (int) $data['max_floor'] : null,
            notFirstFloor: (bool) ($data['not_first_floor'] ?? false),
            notLastFloor: (bool) ($data['not_last_floor'] ?? false),
            onlyLastFloor: (bool) ($data['only_last_floor'] ?? false),
            cityId: isset($data['city_id']) && $data['city_id'] !== '' ? (int) $data['city_id'] : null,
            districtId: isset($data['district_id']) && $data['district_id'] !== '' ? (int) $data['district_id'] : null,
            landmark: $data['landmark'] ?? null,
            agencyId: isset($data['agency_id']) && $data['agency_id'] !== '' ? (int) $data['agency_id'] : null,
            agentId: isset($data['agent_id']) && $data['agent_id'] !== '' ? (int) $data['agent_id'] : null,
            code: !empty($data['code']) ? trim($data['code']) : null,
            onlyComplexes: (bool) ($data['only_complexes'] ?? false),
            isFeatured: isset($data['is_featured']) ? (bool) $data['is_featured'] : null,
            isVip: isset($data['is_vip']) ? (bool) $data['is_vip'] : null,
            filterOptionIds: $filterOptionIds,
            search: $data['q'] ?? ($data['search'] ?? null),
            sortBy: $data['sort_by'] ?? 'created_at',
            sortDirection: in_array(strtolower($data['sort_direction'] ?? ''), ['asc', 'desc']) 
                ? strtolower($data['sort_direction']) 
                : 'desc',
        );
    }
}
