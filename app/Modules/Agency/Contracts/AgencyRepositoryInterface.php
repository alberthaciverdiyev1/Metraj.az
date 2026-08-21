<?php

namespace App\Modules\Agency\Contracts;

use App\Modules\Agency\Models\Agency;
use Illuminate\Database\Eloquent\Collection;

interface AgencyRepositoryInterface
{
    /**
     * Aktiv agentlikləri elan sayı ilə birlikdə qaytarır.
     *
     * @return Collection<int, Agency>
     */
    public function activeWithPropertiesCount(?string $search = null): Collection;

    /**
     * Aktiv agentliyi ID və ya slug ilə (əlaqələri yüklü) qaytarır.
     */
    public function findActive(int|string $idOrSlug): ?Agency;
}
