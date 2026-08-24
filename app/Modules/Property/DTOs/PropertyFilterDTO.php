<?php

namespace App\Modules\Property\DTOs;

use App\Modules\Property\Enums\BuildingType;
use App\Modules\Property\Enums\DealType;
use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Enums\PropertyType;
use App\Modules\Property\Enums\RepairType;
use App\Modules\Property\Enums\SellerType;

readonly class PropertyFilterDTO
{
    public function __construct(
        public ?PropertyType $propertyType = null,
        public ?DealType $dealType = null,
        /** @var array<int, DealType> */
        public array $dealTypes = [],
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
        public ?int $minLandArea = null,
        public ?int $maxLandArea = null,
        public ?int $rooms = null,
        public ?int $minFloor = null,
        public ?int $maxFloor = null,
        public bool $notFirstFloor = false,
        public bool $notLastFloor = false,
        public bool $onlyLastFloor = false,
        public ?int $cityId = null,
        public ?int $districtId = null,
        public array $districtIds = [],
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

    public function hasFilters(): bool
    {
        return $this->propertyType !== null
            || $this->dealType !== null
            || !empty($this->dealTypes)
            || $this->buildingType !== null
            || $this->repairType !== null
            || $this->sellerType !== null
            || $this->hasDocument !== null
            || $this->hasMortgage !== null
            || $this->hasInternalCredit !== null
            || $this->minPrice !== null
            || $this->maxPrice !== null
            || $this->minArea !== null
            || $this->maxArea !== null
            || $this->minLandArea !== null
            || $this->maxLandArea !== null
            || $this->rooms !== null
            || $this->minFloor !== null
            || $this->maxFloor !== null
            || $this->notFirstFloor
            || $this->notLastFloor
            || $this->onlyLastFloor
            || $this->cityId !== null
            || $this->districtId !== null
            || !empty($this->districtIds)
            || !empty($this->landmark)
            || $this->agencyId !== null
            || $this->agentId !== null
            || !empty($this->code)
            || $this->onlyComplexes
            || $this->isFeatured !== null
            || $this->isVip !== null
            || !empty($this->filterOptionIds)
            || !empty($this->search);
    }

    public static function fromArray(array $data): self
    {
        $filterOptionIds = [];
        if (!empty($data['filter_options']) && is_array($data['filter_options'])) {
            $filterOptionIds = array_map('intval', array_filter($data['filter_options']));
        }

        $dealType = null;
        if (!empty($data['deal_type'])) {
            $dealType = DealType::tryFrom($data['deal_type']);
        } elseif (!empty($data['adType']) && $data['adType'] !== 'all') {
            $adType = $data['adType'];
            if ($adType === 'rent') {
                $rentType = $data['rentType'] ?? null;
                $dealType = $rentType === 'daily' ? DealType::RentDaily : DealType::RentMonthly;
            } elseif ($adType === 'sale') {
                $dealType = DealType::Sale;
            } else {
                $dealType = DealType::tryFrom($adType);
            }
        }

        $dealTypes = [];
        if (!empty($data['deal_types']) && is_array($data['deal_types'])) {
            foreach ($data['deal_types'] as $dtValue) {
                $dtEnum = DealType::tryFrom($dtValue);
                if ($dtEnum !== null) {
                    $dealTypes[] = $dtEnum;
                }
            }
        }

        $sellerType = null;
        $rawSeller = $data['seller_type'] ?? ($data['advertiserType'] ?? null);
        if (!empty($rawSeller)) {
            if ($rawSeller === 'user' || $rawSeller === 'owner') {
                $sellerType = SellerType::Owner;
            } elseif ($rawSeller === 'realtor' || $rawSeller === 'agency') {
                $sellerType = SellerType::Agency;
            } elseif ($rawSeller === 'complex') {
                $sellerType = SellerType::Complex;
            } else {
                $sellerType = SellerType::tryFrom($rawSeller);
            }
        }

        $hasDocument = null;
        if (isset($data['has_document']) && $data['has_document'] !== '') {
            $hasDocument = (bool) $data['has_document'];
        } elseif (isset($data['hasDeed']) && $data['hasDeed'] !== '') {
            $hasDocument = (bool) $data['hasDeed'];
        }

        $hasMortgage = null;
        if (isset($data['has_mortgage']) && $data['has_mortgage'] !== '') {
            $hasMortgage = (bool) $data['has_mortgage'];
        } elseif (isset($data['inCredit']) && $data['inCredit'] !== '') {
            $hasMortgage = (bool) $data['inCredit'];
        }

        $minPrice = isset($data['min_price']) && $data['min_price'] !== '' ? (float) $data['min_price'] : (isset($data['minPrice']) && $data['minPrice'] !== '' ? (float) $data['minPrice'] : null);
        $maxPrice = isset($data['max_price']) && $data['max_price'] !== '' ? (float) $data['max_price'] : (isset($data['maxPrice']) && $data['maxPrice'] !== '' ? (float) $data['maxPrice'] : null);
        $minArea = isset($data['min_area']) && $data['min_area'] !== '' ? (int) $data['min_area'] : (isset($data['minArea']) && $data['minArea'] !== '' ? (int) $data['minArea'] : null);
        $maxArea = isset($data['max_area']) && $data['max_area'] !== '' ? (int) $data['max_area'] : (isset($data['maxArea']) && $data['maxArea'] !== '' ? (int) $data['maxArea'] : null);
        $minLandArea = isset($data['min_land_area']) && $data['min_land_area'] !== '' ? (int) $data['min_land_area'] : (isset($data['fieldAreaMin']) && $data['fieldAreaMin'] !== '' ? (int) $data['fieldAreaMin'] : null);
        $maxLandArea = isset($data['max_land_area']) && $data['max_land_area'] !== '' ? (int) $data['max_land_area'] : (isset($data['fieldAreaMax']) && $data['fieldAreaMax'] !== '' ? (int) $data['fieldAreaMax'] : null);
        $rooms = isset($data['rooms']) && $data['rooms'] !== '' ? (int) $data['rooms'] : (isset($data['roomCount']) && $data['roomCount'] !== '' ? (int) $data['roomCount'] : null);
        $minFloor = isset($data['min_floor']) && $data['min_floor'] !== '' ? (int) $data['min_floor'] : (isset($data['floorMin']) && $data['floorMin'] !== '' ? (int) $data['floorMin'] : null);
        $maxFloor = isset($data['max_floor']) && $data['max_floor'] !== '' ? (int) $data['max_floor'] : (isset($data['floorMax']) && $data['floorMax'] !== '' ? (int) $data['floorMax'] : null);
        $cityId = isset($data['city_id']) && $data['city_id'] !== '' ? (int) $data['city_id'] : (isset($data['cityId']) && $data['cityId'] !== '' ? (int) $data['cityId'] : null);
        
        $districtIds = [];
        $rawDistricts = $data['district_ids'] ?? ($data['districts'] ?? ($data['district_id'] ?? ($data['district'] ?? [])));
        if (!empty($rawDistricts)) {
            if (is_array($rawDistricts)) {
                $districtIds = array_values(array_filter(array_map('intval', $rawDistricts)));
            } elseif (is_string($rawDistricts) && str_contains($rawDistricts, ',')) {
                $districtIds = array_values(array_filter(array_map('intval', explode(',', $rawDistricts))));
            } elseif ((int)$rawDistricts > 0) {
                $districtIds = [(int)$rawDistricts];
            }
        }
        $districtId = count($districtIds) === 1 ? $districtIds[0] : (isset($data['district_id']) && (int)$data['district_id'] > 0 ? (int)$data['district_id'] : null);

        $code = !empty($data['code']) ? trim($data['code']) : (!empty($data['adNo']) ? trim($data['adNo']) : null);
        $propertyType = !empty($data['property_type']) ? PropertyType::tryFrom($data['property_type']) : (!empty($data['buildingType']) ? PropertyType::tryFrom($data['buildingType']) : null);

        return new self(
            propertyType: $propertyType,
            dealType: $dealType,
            dealTypes: $dealTypes,
            buildingType: !empty($data['building_type']) ? BuildingType::tryFrom($data['building_type']) : null,
            repairType: !empty($data['repair_type']) ? RepairType::tryFrom($data['repair_type']) : (!empty($data['propertyCondition']) ? RepairType::tryFrom($data['propertyCondition']) : null),
            sellerType: $sellerType,
            status: !empty($data['status']) ? PropertyStatus::tryFrom($data['status']) : PropertyStatus::Published,
            hasDocument: $hasDocument,
            hasMortgage: $hasMortgage,
            hasInternalCredit: isset($data['has_internal_credit']) && $data['has_internal_credit'] !== '' ? (bool) $data['has_internal_credit'] : null,
            minPrice: $minPrice,
            maxPrice: $maxPrice,
            minArea: $minArea,
            maxArea: $maxArea,
            minLandArea: $minLandArea,
            maxLandArea: $maxLandArea,
            rooms: $rooms,
            minFloor: $minFloor,
            maxFloor: $maxFloor,
            notFirstFloor: (bool) ($data['not_first_floor'] ?? false),
            notLastFloor: (bool) ($data['not_last_floor'] ?? false),
            onlyLastFloor: (bool) ($data['only_last_floor'] ?? false),
            cityId: $cityId,
            districtId: $districtId,
            districtIds: $districtIds,
            landmark: $data['landmark'] ?? null,
            agencyId: isset($data['agency_id']) && $data['agency_id'] !== '' ? (int) $data['agency_id'] : null,
            agentId: isset($data['agent_id']) && $data['agent_id'] !== '' ? (int) $data['agent_id'] : null,
            code: $code,
            onlyComplexes: (bool) ($data['only_complexes'] ?? false),
            isFeatured: isset($data['is_featured']) && $data['is_featured'] !== '' ? (bool) $data['is_featured'] : null,
            isVip: isset($data['is_vip']) && $data['is_vip'] !== '' ? (bool) $data['is_vip'] : null,
            filterOptionIds: $filterOptionIds,
            search: $data['q'] ?? ($data['search'] ?? null),
            sortBy: $data['sort_by'] ?? 'created_at',
            sortDirection: in_array(strtolower($data['sort_direction'] ?? ''), ['asc', 'desc'])
                ? strtolower($data['sort_direction'])
                : 'desc',
        );
    }
}
