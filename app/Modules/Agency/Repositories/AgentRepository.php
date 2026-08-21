<?php

namespace App\Modules\Agency\Repositories;

use App\Modules\Agency\Contracts\AgentRepositoryInterface;
use App\Modules\Agency\Models\Agent;

class AgentRepository implements AgentRepositoryInterface
{
    public function __construct(
        protected Agent $model,
    ) {
    }

    public function findWithUserAndAgency(int $id): ?Agent
    {
        return $this->model->with(['user', 'agency'])->find($id);
    }
}
