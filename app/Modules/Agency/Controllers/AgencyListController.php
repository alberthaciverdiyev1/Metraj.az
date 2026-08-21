<?php

namespace App\Modules\Agency\Controllers;

use App\Modules\Agency\Services\AgencyService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AgencyListController extends Controller
{
    public function __construct(
        protected AgencyService $agencyService,
    ) {}

    public function __invoke(): View
    {
        $agencies = $this->agencyService->activeAgencies();

        // Heç bir agentliyə bağlı olmayan müstəqil rieltorlar da görsənsin
        $independentAgents = $this->agencyService->independentAgents();

        return view('pages.agency.list', compact('agencies', 'independentAgents'));
    }
}
