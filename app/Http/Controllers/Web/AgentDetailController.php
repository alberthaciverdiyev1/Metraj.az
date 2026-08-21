<?php

namespace App\Http\Controllers\Web;

use App\Core\Infrastructure\Persistence\Eloquent\Models\Agent;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AgentDetailController extends Controller
{
    public function __invoke(int $id): View
    {
        $agent = Agent::with(['user', 'agency'])->findOrFail($id);

        $properties = $agent->properties()
            ->with(['filterOptions.filter'])
            ->where('status', 'published')
            ->latest('id')
            ->paginate(12);

        return view('agents.show', compact('agent', 'properties'));
    }
}
