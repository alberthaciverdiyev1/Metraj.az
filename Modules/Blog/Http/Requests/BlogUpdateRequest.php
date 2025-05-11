<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'author_name' => 'required|string',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('Blog name is required!'),
            'description.required' => __('Blog description is required!'),
            'category_id.required' => __('Blog category is required!'),
            'category_id.exists' => __('Blog category is not exists!'),
            'tags.*.exists' => __('Some selected tags do not exist!'),
            'author_name.required' => __('Author name is required!'),
        ];
    }
}
