<?php

namespace App\Application\Educator\Requests;

use App\Application\Educator\Enums\EducatorStatus;
use Illuminate\Foundation\Http\FormRequest;

class FetchEducatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'nullable','string'],
            'status' => ['sometimes', 'nullable','in:'.implode(',', EducatorStatus::values())],
            'sorting' => ['sometimes', 'nullable','array'],
            'sorting.*' => ['required_with:sorting', 'in:status,creation_type,created_at'],
            'paginated' => ['sometimes', 'string', 'in:true,false'],
            'per_page' => ['sometimes', 'integer', 'max:100']
        ];
    }
}
