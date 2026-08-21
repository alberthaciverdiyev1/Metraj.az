<?php

namespace App\Core\Domain\Property\Entities;

/**
 * Əmlakın bir şəklinin saf domen obyekti.
 * Heç bir framework/Elouqent bağımlılığı yoxdur.
 */
final readonly class PropertyImage
{
    /**
     * Elanda şəkil yoxdursa istifadə olunan standart şəkil.
     */
    public const string FALLBACK_IMAGE = 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80';

    public function __construct(
        public int $id,
        public string $url,
        public int $sortOrder,
    ) {}
}
