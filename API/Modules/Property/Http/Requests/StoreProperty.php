<?php

namespace Modules\Property\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProperty extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */

    public function rules(): array
    {
        return [
            'add_no' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:properties,slug',
            'town_id' => 'nullable|exists:towns,id',
            'subway_id' => 'nullable|exists:subways,id',
            'district_id' => 'nullable|exists:districts,id',
            'city_id' => 'nullable|exists:cities,id',
            'address' => 'nullable|string|max:255',
            'property_type' => 'nullable|string|max:255',
            'add_type' => 'nullable|string|max:255',
            'number_of_floors' => 'nullable|integer|min:0',
            'number_of_rooms' => 'nullable|integer|min:0',
            'floor_located' => 'nullable|integer|min:0',
            'area' => 'nullable|integer|min:0',
            'field_area' => 'nullable|integer|min:0',
            'advertiser' => 'nullable|string|max:255',
            'advertiser_name' => 'nullable|string|max:255',
            'phone_1' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'phone_3' => 'nullable|string|max:20',
            'phone_4' => 'nullable|string|max:20',
            'mail' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'in_credit' => 'nullable|boolean',
            'document' => 'nullable|string|max:255',
            'note_to_admin' => 'nullable|string',
            'building_type' => 'nullable|string|max:255',
            'has_video' => 'nullable|boolean',
            'google_map_location' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'is_premium' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
            'realtor_id' => 'nullable|exists:users,id',
        ];
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
