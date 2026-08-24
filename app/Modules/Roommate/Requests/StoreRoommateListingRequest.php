<?php

namespace App\Modules\Roommate\Requests;

use App\Modules\Roommate\Enums\GenderPreference;
use App\Modules\Roommate\Enums\OccupationPreference;
use App\Modules\Roommate\Enums\RoommateListingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreRoommateListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'listing_type' => ['required', new Enum(RoommateListingType::class)],
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric|min:1',
            'currency' => 'nullable|string|max:10',
            'bills_included' => 'nullable|boolean',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'location_note' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'gender_preference' => ['required', new Enum(GenderPreference::class)],
            'occupation_preference' => ['nullable', new Enum(OccupationPreference::class)],
            'smoker_allowed' => 'nullable|boolean',
            'pet_allowed' => 'nullable|boolean',
            'stay_duration' => 'nullable|string|max:100',
            'available_from' => 'nullable|date',
            'total_roommates' => 'nullable|integer|min:1|max:20',
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
