<?php

namespace App\Modules\Agency\Contracts;

use App\Modules\Agency\Models\Agent;

interface AgentRepositoryInterface
{
    public function findWithUserAndAgency(int $id): ?Agent;
}
