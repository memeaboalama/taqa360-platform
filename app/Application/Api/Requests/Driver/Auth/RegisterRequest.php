<?php

namespace App\Application\Api\Requests\Driver\Auth;

use App\Support\Rules\IsValidPhone;
use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'phone'    =>  ['required','numeric','digits_between:9,10',new isValidPhone, Rule::unique('drivers', 'phone')],
            'latitude' => ['nullable', new LatitudeRule],
            'longitude' => ['nullable', new LongitudeRule],
            'password' => 'required|string|min:6|max:50',
            'city_id' => 'required|exists:cities,id',
            'Id_number' => ['nullable'],
            'Id_expire_date'    =>  ['nullable'],
            'iban'    =>  ['nullable'],
            'name'    =>  ['required'],
            'email' => 'required|string|min:10|max:200|unique:drivers,email',
            'platform'    =>  ['nullable','in:web,ios,android'],
            'files.*.license_copy' => 'required|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:1000',
            'files.*.car_copy' => 'required|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:1000',
            'files.*.residence_permit_copy' => 'required|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:1000',
            'files.*.iban_copy' => 'required|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:1000',
            'files.*.car_registration_copy' => 'required|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:1000',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' =>  ltrim($this->phone, "0"),
        ]);
    }
}
