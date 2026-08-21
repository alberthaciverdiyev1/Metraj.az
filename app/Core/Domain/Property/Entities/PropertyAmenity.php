<?php

namespace App\Core\Domain\Property\Entities;

/**
 * Əmlakın bir təchizatının (amenity) saf domen obyekti.
 * Ad çoxdilli massivdir: {"az": "Lift", "ru": "Лифт", ...}
 */
final readonly class PropertyAmenity
{
    public function __construct(
        public int $id,
        public array $name,
        public ?string $icon = null,
    ) {}
}
