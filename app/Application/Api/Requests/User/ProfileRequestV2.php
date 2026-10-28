<?php

namespace App\Application\Api\Requests\User;

use App\Support\Rules\IsValidPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequestV2 extends FormRequest
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
            'name'    =>  ['required','string','max:150'],
            'email'    =>  ['required','email','unique:users,email,'.auth()->id()],
            'gender'=>['in:male,female,'],
            'nationality'=>[''],
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
