<?php

namespace App\Modules\Agency\Controllers;

use App\Modules\Agency\Services\AgencyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AgencyListController extends Controller
{
    public function __construct(
        protected AgencyService $agencyService,
    ) {}

    public function __invoke(Request $request): View|JsonResponse
    {
        $type = $request->get('type', 'all'); // 'all', 'agency', 'agent'
        $search = $request->get('search');

        // Total counts for badges
        $totalAgenciesCount = $this->agencyService->activeAgencies()->count();
        $totalAgentsCount = $this->agencyService->independentAgents()->count();

        $agencies = ($type === 'agent') ? collect() : $this->agencyService->activeAgencies($search);
        $independentAgents = ($type === 'agency') ? collect() : $this->agencyService->independentAgents($search);

        $agencyItems = $agencies->map(function ($agency) {
            return (object) [
                'type' => 'agency',
                'id' => $agency->id,
                'url' => '/agency/' . $agency->id,
                'name' => $agency->name,
                'subtitle' => $agency->address ?? __('Daşınmaz Əmlak Agentliyi'),
                'is_address' => !empty($agency->address),
                'banner' => $agency->banner_url ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600&q=80',
                'avatar' => $agency->logo_url,
                'initial' => strtoupper(substr($agency->name ?? 'A', 0, 1)),
                'properties_count' => $agency->properties_count ?? 0,
                'phone' => $agency->phone,
            ];
        });

        $agentItems = $independentAgents->map(function ($agent) {
            $name = $agent->user?->name ?? __('Rieltor');
            return (object) [
                'type' => 'agent',
                'id' => $agent->id,
                'url' => '/agent/' . $agent->id,
                'name' => $name,
                'subtitle' => $agent->position ?? __('Müstəqil Rieltor'),
                'is_address' => false,
                'banner' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&q=80',
                'avatar' => $agent->avatar_url,
                'initial' => strtoupper(substr($name, 0, 1)),
                'properties_count' => $agent->published_properties_count ?? 0,
                'phone' => $agent->phone,
            ];
        });

        // Interleave / mix agencies and independent agents together
        $items = collect();
        if ($type === 'agency') {
            $items = $agencyItems->values();
        } elseif ($type === 'agent') {
            $items = $agentItems->values();
        } else {
            $agencyList = $agencyItems->values();
            $agentList = $agentItems->values();
            $max = max($agencyList->count(), $agentList->count());

            for ($i = 0; $i < $max; $i++) {
                if ($agencyList->has($i)) {
                    $items->push($agencyList->get($i));
                }
                if ($agentList->has($i)) {
                    $items->push($agentList->get($i));
                }
            }
        }

        $agenciesCount = $totalAgenciesCount;
        $agentsCount = $totalAgentsCount;
        $activeType = in_array($type, ['agency', 'agent']) ? $type : 'all';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('pages.agency.partials.grid', compact('items'))->render(),
                'total' => $items->count(),
                'type' => $activeType,
            ]);
        }

        return view('pages.agency.list', compact('items', 'agenciesCount', 'agentsCount', 'activeType', 'search', 'agencies', 'independentAgents'));
    }
}
