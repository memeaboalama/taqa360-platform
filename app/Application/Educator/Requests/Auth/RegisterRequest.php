<?php

namespace App\Application\Educator\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:50'],
            'last_name'  => ['required', 'string', 'min:2', 'max:50'],
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique('educators', 'email')],
            'password'   => ['required', 'string', 'confirmed', 'min:8'],
            'agree_terms' => ['required', 'boolean'],
            'platform'   => ['nullable', 'in:web,ios,android'],
            'device_token' => ['nullable', 'string'],
        ];
    }
}
