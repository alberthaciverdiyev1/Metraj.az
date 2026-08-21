<?php

namespace App\Modules\Agency\Services;

use App\Modules\Agency\Repositories\AgencyRepository;
use App\Modules\Property\Enums\PropertyStatus;
use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use Illuminate\Database\Eloquent\Collection;

/**
 * Agentlik siyahısı və profili ilə bağlı iş məntiqi.
 */
class AgencyService
{
    public function __construct(
        protected AgencyRepository $agencyRepository,
    ) {}

    /**
     * Aktiv agentlikləri elan sayı ilə qaytarır.
     *
     * @return Collection<int, Agency>
     */
    public function activeAgencies(?string $search = null): Collection
    {
        return $this->agencyRepository->activeWithPropertiesCount($search);
    }

    /**
     * Heç bir agentliyə bağlı olmayan müstəqil rieltorlar.
     *
     * @return Collection<int, Agent>
     */
    public function independentAgents(?string $search = null): Collection
    {
        $query = Agent::with('user')
            ->withCount(['properties as published_properties_count' => fn ($q) => $q->where('status', PropertyStatus::Published)])
            ->whereNull('agency_id')
            ->where('is_active', true);

        if (!empty($search)) {
            $term = trim($search);
            $query->where(function ($q) use ($term) {
                $q->whereHas('user', fn ($uq) => $uq->where('name', 'ilike', "%{$term}%"))
                    ->orWhere('position', 'ilike', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        return $query->orderByDesc('published_properties_count')->get();
    }

    /**
     * Aktiv agentliyi ID və ya slug ilə tapır (agents.user, owner əlaqələri yüklü).
     */
    public function show(int|string $idOrSlug): ?Agency
    {
        return $this->agencyRepository->findActive($idOrSlug);
    }
}
