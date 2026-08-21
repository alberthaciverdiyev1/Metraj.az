<?php

namespace App\Modules\Inquiry\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Ümumi əlaqə forması (əmlak məlumatı olmadan) sorğusu.
 */
class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        // İctimai forma — hər kəs göndərə bilər.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
