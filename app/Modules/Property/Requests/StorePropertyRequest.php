<?php

namespace App\Modules\Property\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Saytın ictimai "Elan Əlavə Et" formasından gələn elan yaratma sorğusu.
 * Validasiya qaydaları burada mərkəzləşdirilir.
 */
class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        // İctimai forma — hər kəs elan əlavə edə bilər.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'property_type_id' => 'required|exists:filter_options,id',
            'deal_type_id' => 'required|exists:filter_options,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'price_gbp' => 'required|numeric|min:1',
            'prices' => 'nullable|array',
            'area' => 'nullable|numeric|min:1',
            'land_area' => 'nullable|numeric|min:0.1',
            'rooms' => 'nullable|integer|min:1',
            'floor' => 'nullable|integer|min:1',
            'total_floors' => 'nullable|integer|min:1',
            'building_type_id' => 'nullable|exists:filter_options,id',
            'repair_type_id' => 'nullable|exists:filter_options,id',
            'heating_system_id' => 'nullable|exists:filter_options,id',
            'window_view_id' => 'nullable|exists:filter_options,id',
            'description' => 'nullable|string',
            'has_document' => 'nullable|boolean',
            'has_mortgage' => 'nullable|boolean',
            'has_internal_credit' => 'nullable|boolean',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'advertiser' => 'required|in:owner,agent',
            'advertiser_name' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
        ];
    }
}
