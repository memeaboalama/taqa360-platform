<?php

namespace App\Application\Api\Requests\Edu\Service;

use App\Support\Enum\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'array'],
            'description.en' => ['nullable', 'string', 'max:500'],
            'description.ar' => ['nullable', 'string', 'max:500'],
            'scope_of_work' => ['sometimes', 'nullable', 'array'],
            'scope_of_work.en' => ['nullable', 'string', 'max:5000'],
            'scope_of_work.ar' => ['nullable', 'string', 'max:5000'],
            'status' => ['sometimes', 'required', 'string', Rule::in(StatusEnum::values())],
        ];
    }
}
