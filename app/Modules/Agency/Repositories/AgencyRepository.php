<?php

namespace App\Modules\Agency\Repositories;

use App\Modules\Agency\Enums\AgencyStatus;
use App\Modules\Agency\Contracts\AgencyRepositoryInterface;
use App\Modules\Agency\Models\Agency;
use Illuminate\Database\Eloquent\Collection;

class AgencyRepository implements AgencyRepositoryInterface
{
    public function activeWithPropertiesCount(): Collection
    {
        return Agency::withCount('properties')
            ->where('status', AgencyStatus::Active)
            ->get();
    }

    public function findActive(int|string $idOrSlug): ?Agency
    {
        $query = Agency::with(['agents.user', 'owner'])->where('status', AgencyStatus::Active);

        return ctype_digit((string) $idOrSlug)
            ? $query->where('id', (int) $idOrSlug)->first()
            : $query->where('slug', $idOrSlug)->first();
    }
}
