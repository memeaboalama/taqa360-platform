<?php

namespace App\Application\Api\Requests\Driver\Profile;

use App\Support\Rules\IsValidPhone;
use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompleteProfileRequest extends FormRequest
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
            'Id_number' => ['required', 'numeric'],
            'Id_expire_date' => ['required', 'date'],
            'license_image' => ['required', 'image', "mimes:jpeg,jpg,png", 'max:2024'],
            'car_image' => ['required', 'image', "mimes:jpeg,jpg,png", 'max:2024'],
            'id_image' => ['required', 'image', "mimes:jpeg,jpg,png", 'max:2024'],


        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
