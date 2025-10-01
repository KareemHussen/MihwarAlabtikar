<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class IndexOrganizationRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'query' => 'string|max:255',
            'per_page' => 'integer|min:1|max:30',
            'page' => 'integer|min:1',
            'sort' => 'required_with:order_by|string|in:asc,desc',
            'order_by' => 'string|in:id,name',
        ];
    }
}
