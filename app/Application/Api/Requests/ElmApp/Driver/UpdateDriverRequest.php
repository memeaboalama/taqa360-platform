<?php

namespace App\Application\Api\Requests\ElmApp\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
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
            'driver_id' => 'required|exists:drivers,id',
            'elm_id' => ['nullable', 'string', 'max:255'],
            'elm_upload_status' =>  ['required','in:0,1,2,3,10'],
            'elm_errors' => 'nullable|array',
            'IdentityTypeId' => 'nullable|string',
            'Id_expire_date' => 'nullable|string',
            'dateOfBirth' => 'nullable|string',
            'city_id' => 'nullable|integer',
            'region_id' => 'nullable|integer',
            'car_type_id' => 'nullable|integer',
            'carNumber' => 'nullable|string',
            'vehicleSequenceNumber'=> 'nullable|string',
            'registrationDate' => 'nullable|string',
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
