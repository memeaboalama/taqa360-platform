<?php

namespace App\Application\Api\Requests\Driver\Profile;

use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use App\Support\Rules\OldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePasswordRequest extends FormRequest
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
            'old_password' => ['required', 'string',new OldPassword()],
            'password' => "required|confirmed|min:8|string",

        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
