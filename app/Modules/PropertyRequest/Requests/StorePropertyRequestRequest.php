<?php

namespace App\Modules\PropertyRequest\Requests;

use App\Modules\PropertyRequest\Enums\RequestType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePropertyRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'request_type' => ['required', new Enum(RequestType::class)],
            'property_type' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:15',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'required|numeric|min:1',
            'currency' => 'nullable|string|max:10',
            'bills_included' => 'nullable|boolean',
            'rooms' => 'nullable|string|max:20',
            'area_min' => 'nullable|numeric|min:0',
            'area_max' => 'nullable|numeric|min:0',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'location_note' => 'nullable|string|max:255',
            'has_deed' => 'nullable|boolean',
            'mortgage_eligible' => 'nullable|boolean',
            'repair_status' => 'nullable|string|max:50',
            'furnished_status' => 'nullable|string|max:50',
            'occupancy_type' => 'nullable|string|max:50',
            'gender_preference' => 'nullable|string|max:20',
            'occupation_preference' => 'nullable|string|max:20',
            'smoker_allowed' => 'nullable|boolean',
            'pet_allowed' => 'nullable|boolean',
            'stay_duration' => 'nullable|string|max:100',
            'move_in_date' => 'nullable|date',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'contact_name' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:30',
            'contact_whatsapp' => 'nullable|string|max:30',
            'contact_email' => 'nullable|email|max:100',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
        ];
    }
}
