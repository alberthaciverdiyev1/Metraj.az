<?php

namespace App\Modules\Property\DTOs;

use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Property\Enums\SellerType;

readonly class CreatePropertyDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $code = '',
        public string $slug = '',
        public bool $hasDocument = false,
        public bool $hasMortgage = false,
        public bool $hasInternalCredit = false,
        public float $price = 0.0,
        public ?string $currency = 'GBP',
        public array $prices = [],
        public int $viewsCount = 0,
        public ?int $area = null,
        public ?int $landArea = null,
        public ?int $rooms = null,
        public ?int $floor = null,
        public ?int $totalFloors = null,
        public ?int $cityId = null,
        public ?int $districtId = null,
        public ?string $landmark = null,
        public ?string $address = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?int $agencyId = null,
        public ?int $agentId = null,
        public ?int $userId = null,
        public ?SellerType $sellerType = null,
        public PropertyStatus $status = PropertyStatus::PendingApproval,
        public bool $isFeatured = false,
        public bool $isVip = false,
        public ?string $video = null,
        public array $filterOptionIds = [],
        public array $amenityIds = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? '',
            code: (string) ($data['code'] ?? ''),
            slug: (string) ($data['slug'] ?? ''),
            hasDocument: (bool) ($data['has_document'] ?? false),
            hasMortgage: (bool) ($data['has_mortgage'] ?? false),
            hasInternalCredit: (bool) ($data['has_internal_credit'] ?? false),
            price: (float) $data['price'],
            currency: $data['currency'] ?? 'GBP',
            prices: $data['prices'] ?? [],
            viewsCount: (int) ($data['views_count'] ?? 0),
            area: isset($data['area']) ? (int) $data['area'] : null,
            landArea: isset($data['land_area']) ? (int) $data['land_area'] : null,
            rooms: isset($data['rooms']) ? (int) $data['rooms'] : null,
            floor: isset($data['floor']) ? (int) $data['floor'] : null,
            totalFloors: isset($data['total_floors']) ? (int) $data['total_floors'] : null,
            cityId: isset($data['city_id']) ? (int) $data['city_id'] : null,
            districtId: isset($data['district_id']) ? (int) $data['district_id'] : null,
            landmark: $data['landmark'] ?? null,
            address: $data['address'] ?? null,
            latitude: isset($data['latitude']) ? (float) $data['latitude'] : null,
            longitude: isset($data['longitude']) ? (float) $data['longitude'] : null,
            agencyId: isset($data['agency_id']) ? (int) $data['agency_id'] : null,
            agentId: isset($data['agent_id']) ? (int) $data['agent_id'] : null,
            userId: isset($data['user_id']) ? (int) $data['user_id'] : null,
            sellerType: !empty($data['seller_type']) 
                ? ($data['seller_type'] instanceof SellerType ? $data['seller_type'] : SellerType::tryFrom($data['seller_type'])) 
                : null,
            status: isset($data['status']) 
                ? ($data['status'] instanceof PropertyStatus ? $data['status'] : PropertyStatus::from($data['status']))
                : PropertyStatus::PendingApproval,
            isFeatured: (bool) ($data['is_featured'] ?? false),
            isVip: (bool) ($data['is_vip'] ?? false),
            video: $data['video'] ?? null,
            filterOptionIds: $data['filter_option_ids'] ?? [],
            amenityIds: $data['amenity_ids'] ?? [],
        );
    }
}
