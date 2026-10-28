<?php

namespace App\Application\Educator\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'educator_id' => ['required', 'exists:educators,id'],
            'otp' => ['required', 'digits:6'],
        ];
    }
}
