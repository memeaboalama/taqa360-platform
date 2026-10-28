<?php

namespace App\Application\Educational\Requests;

use App\Application\Educational\Enums\EducationalRequestStatus;
use Illuminate\Foundation\Http\FormRequest;


class FetchEducationalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'nullable','string'],
            'status' => ['sometimes', 'nullable','in:'.implode(',', EducationalRequestStatus::values())],
            'sorting' => ['sometimes', 'nullable','array'],
            'sorting.*' => ['required_with:sorting', 'in:status,creation_type,created_at'],
            'paginated' => ['sometimes', 'string', 'in:true,false'],
            'per_page' => ['sometimes', 'integer', 'max:20']
        ];
    }
}
