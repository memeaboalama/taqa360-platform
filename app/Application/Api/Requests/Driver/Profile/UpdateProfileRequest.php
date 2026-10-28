<?php

namespace App\Application\Api\Requests\Driver\Profile;

use App\Support\Rules\IsValidPhone;
use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255',Rule::unique('drivers', 'email')->ignore(request()->user())],
            'phone' => ['required', 'numeric', 'digits_between:9,10', new isValidPhone, Rule::unique('drivers', 'phone')->ignore(request()->user())],
            'image'    =>  ['nullable','image'],
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
