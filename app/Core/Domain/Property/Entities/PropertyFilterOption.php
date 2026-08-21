<?php

namespace App\Core\Domain\Property\Entities;

use App\Core\Domain\Filter\Enums\FilterKey;

/**
 * Əmlaka təyin edilmiş dinamik filtr seçiminin saf domen obyekti.
 * Ad çoxdilli massivdir: {"az": "Satılır", "ru": "Продажа", ...}
 */
final readonly class PropertyFilterOption
{
    public function __construct(
        public int $id,
        public int $filterId,
        public ?FilterKey $filterKey,
        public string $value,
        public array $name,
        public ?int $parentId = null,
    ) {}
}
