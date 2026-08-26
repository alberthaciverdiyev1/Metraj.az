<?php

namespace App\Modules\Location\Controllers;

use App\Modules\Location\Services\LocationService;
use App\Http\Controllers\Controller;
use App\Modules\Location\Resources\CityResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiController extends Controller
{
    public function __construct(
        protected LocationService $locationService,
    ) {}

    public function cities(): AnonymousResourceCollection
    {
        return CityResource::collection($this->locationService->activeCities());
    }
}
