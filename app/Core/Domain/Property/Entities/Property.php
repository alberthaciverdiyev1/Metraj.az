<?php

namespace App\Core\Domain\Property\Entities;

use App\Core\Domain\Property\Enums\PropertyStatus;
use App\Core\Domain\Property\Enums\SellerType;
use DateTimeImmutable;

/**
 * Əmlak elanının saf domen entity'si.
 * Heç bir framework/Elouqent bağımlılığı yoxdur — yalnız domen tipləri və PHP.
 *
 * Əlaqəli məlumatlar (şəkillər, filtrlər, təchizatlar, əlaqə şəxsləri)
 * sadə value object massivləri kimi gömülür.
 */
final readonly class Property
{
    /**
     * @param  PropertyImage[]          $images
     * @param  PropertyFilterOption[]   $filterOptions
     * @param  PropertyAmenity[]        $amenities
     * @param  array<string, string>|null  $cityName    Çoxdilli şəhər adı
     * @param  array<string, string>|null  $districtName Çoxdilli rayon adı
     */
    public function __construct(
        public int $id,
        public string $code,
        public string $slug,
        public string $title,
        public string $description,
        public SellerType $sellerType,
        public bool $hasDocument,
        public bool $hasMortgage,
        public bool $hasInternalCredit,
        public float $price,
        public string $currency,
        public array $prices,
        public ?int $area,
        public ?int $landArea,
        public ?int $rooms,
        public ?int $floor,
        public ?int $totalFloors,
        public ?int $cityId,
        public ?int $districtId,
        public ?string $landmark,
        public ?string $address,
        public ?float $latitude,
        public ?float $longitude,
        public ?int $agencyId,
        public ?int $agentId,
        public ?int $userId,
        public PropertyStatus $status,
        public bool $isFeatured,
        public bool $isVip,
        public int $viewsCount,
        public ?DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt,
        public array $images = [],
        public array $filterOptions = [],
        public array $amenities = [],
        public ?PropertyContact $agent = null,
        public ?PropertyContact $agency = null,
        public ?PropertyContact $owner = null,
        public ?array $cityName = null,
        public ?array $districtName = null,
    ) {}

    /**
     * Müəyyən filter_id-yə aid olan filtr seçimini qaytarır.
     */
    public function filterOptionByFilterId(int $filterId): ?PropertyFilterOption
    {
        foreach ($this->filterOptions as $option) {
            if ($option->filterId === $filterId) {
                return $option;
            }
        }

        return null;
    }

    /**
     * Müəyyən FilterKey-ə aid olan filtr seçimini qaytarır.
     */
    public function filterOptionByKey(string|\App\Core\Domain\Filter\Enums\FilterKey $key): ?PropertyFilterOption
    {
        $keyValue = $key instanceof \App\Core\Domain\Filter\Enums\FilterKey ? $key->value : $key;

        foreach ($this->filterOptions as $option) {
            if ($option->filterKey?->value === $keyValue) {
                return $option;
            }
        }

        return null;
    }

    /**
     * Elanın ilk şəklinin tam URL-i (yoxdursa fallback).
     */
    public function firstImageUrl(): string
    {
        return $this->images[0]->url ?? PropertyImage::FALLBACK_IMAGE;
    }

    /**
     * Bütün şəkil URL-ləri (kart slider üçün).
     *
     * @return string[]
     */
    public function imageUrls(): array
    {
        return array_map(fn (PropertyImage $image) => $image->url, $this->images);
    }

    /**
     * Elanın kirayə olub-olmadığını filtr seçimlərindən yoxlayır.
     */
    public function isRent(): bool
    {
        $dealType = $this->filterOptionByFilterId(2);
        if ($dealType === null) {
            return false;
        }

        $name = mb_strtolower($dealType->name['az'] ?? $dealType->value);

        return str_contains($name, 'kirayə')
            || str_contains($name, 'kira')
            || str_contains($dealType->value, 'rent');
    }

    /**
     * Elanın satış olub-olmadığını filtr seçimlərindən yoxlayır.
     */
    public function isSale(): bool
    {
        return $this->filterOptionByFilterId(2)?->value === 'sale';
    }

    /**
     * Elanın torpaq elanı olub-olmadığını əmlak növündən yoxlayır.
     */
    public function isLand(): bool
    {
        $propertyType = $this->filterOptionByFilterId(3);

        return $propertyType !== null
            && str_contains(mb_strtolower($propertyType->name['az'] ?? $propertyType->value), 'torpaq');
    }

    /**
     * Oxşar elanlarda istifadə üçün lokallaşdırılmış şəhər adı.
     */
    public function cityLabel(): ?string
    {
        if (empty($this->cityName)) {
            return null;
        }

        return $this->cityName['az'] ?? reset($this->cityName);
    }

    /**
     * Oxşar elanlarda istifadə üçün lokallaşdırılmış rayon adı.
     */
    public function districtLabel(): ?string
    {
        if (empty($this->districtName)) {
            return null;
        }

        return $this->districtName['az'] ?? reset($this->districtName);
    }

    /**
     * Kartda göstərilən tarix etiketi: bu gün üçün "Bugün HH:mm", əks halda "d.m.Y".
     */
    public function createdAtLabel(): string
    {
        if ($this->createdAt === null) {
            return '';
        }

        $now = new DateTimeImmutable('now');

        if ($this->createdAt->format('Y-m-d') === $now->format('Y-m-d')) {
            return 'Bugün ' . $this->createdAt->format('H:i');
        }

        return $this->createdAt->format('d.m.Y');
    }
}
