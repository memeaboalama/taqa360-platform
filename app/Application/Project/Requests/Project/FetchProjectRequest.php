<?php

namespace App\Application\Project\Requests\Project;

use App\Application\Project\Enums\CreationType;
use App\Application\Project\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;

class FetchProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'nullable','string'],
            'status' => ['sometimes', 'nullable','in:'.implode(',', ProjectStatus::values())],
            'creation_type' => ['sometimes', 'nullable', 'in:'.implode(',',CreationType::values())],
            'created_by' => ['sometimes', 'nullable', 'string'],
            'sorting' => ['sometimes', 'nullable','array'],
            'sorting.*' => ['required_with:sorting', 'in:status,creation_type,created_at'],
            'paginated' => ['sometimes', 'string', 'in:true,false'],
            'per_page' => ['sometimes', 'integer', 'max:100']
        ];
    }
}
