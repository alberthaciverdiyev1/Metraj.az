<?php

namespace App\Http\Controllers\Web;

use App\Core\Domain\Agency\Enums\AgencyStatus;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Agency;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AgencyDetailController extends Controller
{
    public function __invoke(string $agency): View
    {
        // /agency/{id} → ID ilə, /agentlik/{slug} → slug ilə rezolyusiya
        $query = Agency::with(['agents.user', 'owner'])->where('status', AgencyStatus::Active);
        $agency = ctype_digit($agency)
            ? $query->where('id', (int) $agency)->firstOrFail()
            : $query->where('slug', $agency)->firstOrFail();

        $properties = $agency->properties()
            ->with(['images', 'filterOptions.filter'])
            ->where('status', 'published')
            ->latest('id')
            ->paginate(12);

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Agencies'), 'url' => '/agencies'],
            ['label' => $agency->name],
        ];

        return view('agencies.show', compact('agency', 'properties', 'breadcrumbs'));
    }
}
