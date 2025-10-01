<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string'],
            'status' => ['required', 'string', 'in:Planning,Active,On Hold,Completed'],
            'start_date' => 'required_with:end_date|date_format:Y-m-d h:i:s A|before:end_date',
            'end_date' => 'required_with:start_date|date_format:Y-m-d h:i:s A|after:start_date',
        ];
    }
}
