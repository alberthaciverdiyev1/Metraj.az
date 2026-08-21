<?php

namespace App\Http\Controllers\Web;

use App\Core\Domain\Agency\Enums\AgencyStatus;
use App\Core\Domain\Property\Enums\PropertyStatus;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Agency;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Agent;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AgencyListController extends Controller
{
    public function __invoke(): View
    {
        $agencies = Agency::withCount('properties')
            ->where('status', AgencyStatus::Active->value)
            ->get();

        // Heç bir agentliyə bağlı olmayan müstəqil rieltorlar da görsənsin
        $independentAgents = Agent::with('user')
            ->withCount(['properties as published_properties_count' => function ($q) {
                $q->where('status', PropertyStatus::Published->value);
            }])
            ->whereNull('agency_id')
            ->where('is_active', true)
            ->orderByDesc('published_properties_count')
            ->get();

        return view('pages.agency.list', compact('agencies', 'independentAgents'));
    }
}
