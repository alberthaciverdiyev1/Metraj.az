<?php

namespace App\Modules\Agency\Services;

use App\Modules\Agency\Repositories\AgentRepository;
use App\Modules\Agency\Models\Agent;

/**
 * Rieltor (agent) profili ilə bağlı iş məntiqi.
 */
class AgentService
{
    public function __construct(
        protected AgentRepository $agentRepository,
    ) {}

    public function show(int $id): ?Agent
    {
        return $this->agentRepository->findWithUserAndAgency($id);
    }
}
