<?php

namespace App\Core\Application\Property\UseCases;

use App\Core\Application\Property\DTOs\CreatePropertyDTO;
use App\Core\Domain\Property\Repositories\PropertyRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CreatePropertyUseCase
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository
    ) {}

    public function execute(CreatePropertyDTO $dto): Model
    {
        return $this->propertyRepository->create($dto);
    }
}
