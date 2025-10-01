<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'title' => ['required', 'string', 'between:1,255'],
            'description' => 'string',
            'status' => 'string|in:Todo,In Progress,Review,Done',
            'priority' => 'string|in:Low,Medium,High,Urgent',
            'due_date' => 'date_format:Y-m-d h:i:s A',
            'project_id' => 'required|integer|exists:projects,id',
        ];
    }
}
