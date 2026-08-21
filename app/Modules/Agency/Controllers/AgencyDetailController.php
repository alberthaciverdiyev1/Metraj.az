<?php

namespace App\Modules\Agency\Controllers;

use App\Modules\Agency\Services\AgencyService;
use App\Modules\Property\DTOs\PropertyFilterDTO;
use App\Modules\Property\Services\PropertyService;
use App\Modules\Agency\Models\Agency;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AgencyDetailController extends Controller
{
    public function __construct(
        protected AgencyService $agencyService,
        protected PropertyService $propertyService,
    ) {}

    public function __invoke(string $agency): View
    {
        // /agency/{id} → ID ilə, /agentlik/{slug} → slug ilə rezolyusiya
        $agency = $this->agencyService->show($agency);

        abort_unless($agency instanceof Agency, 404);

        $properties = $this->propertyService->paginate(
            PropertyFilterDTO::fromArray(['agency_id' => $agency->id]),
            12
        );

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Agencies'), 'url' => '/agencies'],
            ['label' => $agency->name],
        ];

        return view('agencies.show', compact('agency', 'properties', 'breadcrumbs'));
    }
}
