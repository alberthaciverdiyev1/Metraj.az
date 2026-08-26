<?php

namespace App\Modules\Agency\Controllers;

use App\Modules\Agency\Services\AgencyService;
use App\Modules\Shared\Concerns\CachesGuestPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AgencyListController extends Controller
{
    use CachesGuestPage;

    public function __construct(
        protected AgencyService $agencyService,
    ) {}

    public function __invoke(Request $request): \Illuminate\Http\Response|View|JsonResponse
    {
        $type = $request->get('type', 'all'); // 'all', 'agency', 'agent'
        $search = $request->get('search');

        if ($request->ajax() || $request->wantsJson()) {
            [$agencies, $independentAgents] = $this->buildCollections($type, $search);
            $items = $this->buildItems($type, $agencies, $independentAgents);

            return response()->json([
                'html' => view('pages.agency.partials.grid', compact('items'))->render(),
                'total' => $items->count(),
                'type' => in_array($type, ['agency', 'agent']) ? $type : 'all',
            ]);
        }

        // Tam səhifə keşi (qonaqlar üçün)
        if (! $request->has('_cache_bust')) {
            return $this->cacheGuestPage($request, 'agencies_list', 60, function () use ($type, $search) {
                return $this->renderList($type, $search);
            });
        }

        return response($this->renderList($type, $search));
    }

    protected function renderList(string $type, ?string $search): string
    {
        [$agencies, $independentAgents] = $this->buildCollections($type, $search);
        $items = $this->buildItems($type, $agencies, $independentAgents);

        $agenciesCount = $agencies->count();
        $agentsCount = $independentAgents->count();
        $activeType = in_array($type, ['agency', 'agent']) ? $type : 'all';

        return view('pages.agency.list', compact('items', 'agenciesCount', 'agentsCount', 'activeType', 'search', 'agencies', 'independentAgents'))->render();
    }

    /**
     * Aktiv agentlikləri və müstəqil rieltorları bir dəfəyə çəkir
     * (təkrarlanan sorğuların qarşısını almaq üçün).
     *
     * @return array{0: \Illuminate\Support\Collection, 1: \Illuminate\Support\Collection}
     */
    protected function buildCollections(string $type, ?string $search): array
    {
        $agencies = ($type === 'agent') ? collect() : $this->agencyService->activeAgencies($search);
        $independentAgents = ($type === 'agency') ? collect() : $this->agencyService->independentAgents($search);

        return [$agencies, $independentAgents];
    }

    protected function buildItems(string $type, \Illuminate\Support\Collection $agencies, \Illuminate\Support\Collection $independentAgents): \Illuminate\Support\Collection
    {
        $agencyItems = $agencies->map(function ($agency) {
            return (object) [
                'type' => 'agency',
                'id' => $agency->id,
                'url' => '/agency/' . $agency->id,
                'name' => $agency->name,
                'subtitle' => $agency->address ?? __('agency.agency_default_subtitle'),
                'is_address' => !empty($agency->address),
                'banner' => $agency->banner_url ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600&q=80',
                'avatar' => $agency->logo_url,
                'initial' => strtoupper(substr($agency->name ?? 'A', 0, 1)),
                'properties_count' => $agency->properties_count ?? 0,
                'phone' => $agency->phone,
            ];
        });

        $agentItems = $independentAgents->map(function ($agent) {
            $name = $agent->user?->name ?? __('agency.agent_default_title');
            return (object) [
                'type' => 'agent',
                'id' => $agent->id,
                'url' => '/agent/' . $agent->id,
                'name' => $name,
                'subtitle' => $agent->position ?? __('agency.agent_independent_subtitle'),
                'is_address' => false,
                'banner' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&q=80',
                'avatar' => $agent->avatar_url,
                'initial' => strtoupper(substr($name, 0, 1)),
                'properties_count' => $agent->published_properties_count ?? 0,
                'phone' => $agent->phone,
            ];
        });

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

        return $items;
    }
}
