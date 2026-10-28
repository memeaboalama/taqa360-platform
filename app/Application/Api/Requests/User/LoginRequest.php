<?php

namespace App\Application\Api\Requests\User;

use App\Support\Rules\IsValidPhone;
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
            'phone'    =>  ['required','numeric','digits_between:9,10',new isValidPhone],
            'device_token'    =>  ['nullable'],
            'platform'    =>  ['nullable','in:web,ios,android'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' =>  ltrim($this->phone, "0"),
        ]);
    }

}
