<?php

namespace App\Core\Domain\Property\Repositories;

use App\Core\Application\Property\DTOs\CreatePropertyDTO;
use App\Core\Application\Property\DTOs\PropertyFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface PropertyRepositoryInterface
{
    public function findById(int $id): ?Model;

    public function findBySlug(string $slug): ?Model;

    public function create(CreatePropertyDTO $dto): Model;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function paginate(PropertyFilterDTO $filter, int $perPage = 15): LengthAwarePaginator;

    public function getFeatured(int $limit = 8);

    public function incrementViews(int $id): void;
}
