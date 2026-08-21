<?php

namespace App\Modules\Agency\Controllers;

use App\Modules\Agency\Services\AgentService;
use App\Modules\Property\DTOs\PropertyFilterDTO;
use App\Modules\Property\Services\PropertyService;
use App\Modules\Agency\Models\Agent;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AgentDetailController extends Controller
{
    public function __construct(
        protected AgentService $agentService,
        protected PropertyService $propertyService,
    ) {}

    public function __invoke(int $id): View
    {
        $agent = $this->agentService->show($id);

        abort_unless($agent instanceof Agent, 404);

        $properties = $this->propertyService->paginate(
            PropertyFilterDTO::fromArray(['agent_id' => $agent->id]),
            12
        );

        $breadcrumbs = [
            ['label' => __('Ana səhifə'), 'url' => '/'],
            ['label' => __('Agencies'), 'url' => '/agencies'],
        ];

        if ($agent->agency) {
            $breadcrumbs[] = [
                'label' => $agent->agency->name,
                'url' => '/agency/' . $agent->agency->id,
            ];
        }

        $breadcrumbs[] = [
            'label' => $agent->user?->name ?? __('Rieltor Profili'),
            'url' => null,
        ];

        return view('agents.show', compact('agent', 'properties', 'breadcrumbs'));
    }
}
