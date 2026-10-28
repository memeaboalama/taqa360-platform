<?php

namespace App\Application\Project\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            // 'deadline' => 'nullable|date|after:today',
            'deadline' => 'nullable|date',
        ];
    }
}
