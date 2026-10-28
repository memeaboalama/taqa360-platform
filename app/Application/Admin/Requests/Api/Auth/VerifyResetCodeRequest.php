<?php

namespace App\Application\Admin\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyResetCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:admins,email'],
            'code' => ['required', 'numeric', 'digits:6'],
        ];
    }
}
