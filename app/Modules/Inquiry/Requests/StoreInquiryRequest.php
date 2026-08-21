<?php

namespace App\Modules\Inquiry\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Əmlak detal səhifəsindən göndərilən müştəri müraciəti (lead) sorğusu.
 */
class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // İctimai forma — hər kəs müraciət göndərə bilər.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'property_id' => ['required', 'exists:properties,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
