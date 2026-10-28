<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBasicInfoRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:32',
            'identity_type' => 'sometimes|in:national_id,passport',
            'identity_id' => 'sometimes|nullable|string|max:255',
            'gender' => 'sometimes|nullable|string|max:32',
            'birth_date' => 'sometimes|nullable|date',
            'nationality_id' => 'sometimes|nullable|integer|exists:nationalities,id',
            'country_id' => 'sometimes|nullable|integer|exists:countries,id',
            'region_id' => 'sometimes|nullable|integer|exists:regions,id',
            'city_id' => 'sometimes|nullable|integer|exists:cities,id',
            'education_level' => 'sometimes|nullable|string',
            'experience_years' => 'sometimes|nullable|integer|min:0',
            'specialization' => 'sometimes|nullable|string',
            'employment_status' => 'sometimes|nullable|string',
            'identityImageFront' => 'sometimes|file|mimes:jpg,jpeg,png,webp|max:5120',
            'identityImageBack' => 'sometimes|file|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
