<?php

namespace App\Application\Project\Requests\Project;

use App\Application\Project\Enums\CreationType;
use Domain\Project\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'creation_type' => 'required|in:' . implode(',', CreationType::values()),
            'educational_request_id' => 'required_if:creation_type,' . CreationType::FROM_REQUEST->value . '|nullable|uuid|exists:educational_requests,uuid',
            'tags' => 'nullable|array',
        ];
    }

}
