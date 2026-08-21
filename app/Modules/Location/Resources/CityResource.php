<?php

namespace App\Modules\Location\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name['az'] ?? $this->slug,
            'districts' => DistrictResource::collection($this->activeDistricts),
        ];
    }
}
