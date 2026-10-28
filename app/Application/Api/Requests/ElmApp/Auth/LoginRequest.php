<?php

namespace App\Application\Api\Requests\ElmApp\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'    =>  ['required','email','exists:admins,email'],
            'password'    =>  ['required','string'],
            'device_token'    =>  ['nullable'],
            'platform'    =>  ['nullable','in:web,ios,android'],
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
