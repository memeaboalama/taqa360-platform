<?php

namespace App\Application\Educator\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    =>  ['required','email','exists:educators,email'],
            'password'    =>  ['required','string'],
            'device_token'    =>  ['nullable'],
            'platform'    =>  ['nullable','in:web,ios,android'],
        ];
    }
}
