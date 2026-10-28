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
            'name' => ['sometimes', 'required', 'array'],
            'name.en' => ['sometimes', 'required', 'string', 'max:255'],
            'name.ar' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'array'],
            'description.en' => ['sometimes', 'required', 'string'],
            'description.ar' => ['sometimes', 'required', 'string'],
            'status' => ['sometimes', 'required', 'string', Rule::in(StatusEnum::values())],
        ];
    }
}
