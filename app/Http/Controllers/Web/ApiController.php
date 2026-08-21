<?php

namespace App\Http\Controllers\Web;

use App\Core\Infrastructure\Persistence\Eloquent\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function cities(): JsonResponse
    {
        $cities = City::with('activeDistricts')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($city) => [
                'id' => $city->id,
                'name' => $city->name['az'] ?? $city->slug,
                'districts' => $city->activeDistricts->map(fn ($dist) => [
                    'id' => $dist->id,
                    'name' => $dist->name['az'] ?? $dist->slug,
                ]),
            ]);

        return response()->json($cities);
    }

    public function subway(): JsonResponse
    {
        return response()->json([]);
    }

    public function nearby(): JsonResponse
    {
        return response()->json([]);
    }
}
