<?php

namespace App\Application\Api\Requests\Employee\Password;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|confirmed|min:4',
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
