<?php

namespace App\Application\Project\Requests\Project;

use App\Application\Project\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;

class ChangeProjectStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:'.implode(',', ProjectStatus::values()),
            'details' => 'nullable|string|max:1000',
        ];
    }
}
