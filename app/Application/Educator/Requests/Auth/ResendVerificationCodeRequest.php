<?php

namespace App\Application\Educator\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResendVerificationCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // No `exists` rule on purpose: the controller answers unknown emails
            // with the same success response so the endpoint cannot be used to
            // discover which educators are registered.
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }
}