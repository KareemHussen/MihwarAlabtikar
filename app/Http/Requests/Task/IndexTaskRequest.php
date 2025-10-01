<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class IndexTaskRequest extends FormRequest
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
            'per_page' => 'integer',
            'query' => 'string',
            'sort' => 'required_with:order_by|string|in:asc,desc',
            'order_by' => 'required_with:sort|string|in:name,status,priority',
            'project_id' => 'required|integer|exists:projects,id',
            'status' => 'string|in:Todo,In Progress,Review,Done',
            'priority' => 'string|in:Low,Medium,High,Urgent',
        ];
    }
}
