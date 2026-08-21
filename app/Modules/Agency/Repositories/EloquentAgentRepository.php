<?php

namespace App\Modules\Agency\Repositories;

use App\Modules\Agency\Repositories\AgentRepositoryInterface;
use App\Modules\Agency\Models\Agent;

class EloquentAgentRepository implements AgentRepositoryInterface
{
    public function findWithUserAndAgency(int $id): ?Agent
    {
        return Agent::with(['user', 'agency'])->find($id);
    }
}
