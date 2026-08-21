<?php

namespace App\Core\Domain\Property\Repositories;

use App\Core\Application\Property\DTOs\CreatePropertyDTO;
use App\Core\Application\Property\DTOs\PropertyFilterDTO;
use App\Core\Domain\Property\Entities\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PropertyRepositoryInterface
{
    public function findById(int $id): ?Property;

    public function findBySlug(string $slug): ?Property;

    public function findByCode(string $code): ?Property;

    public function create(CreatePropertyDTO $dto): Property;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function paginate(PropertyFilterDTO $filter, int $perPage = 15): LengthAwarePaginator;

    /**
     * @return Property[]
     */
    public function getFeatured(int $limit = 6): array;

    /**
     * @return Property[]
     */
    public function getVip(int $limit = 6): array;

    public function incrementViews(int $id): void;
}
