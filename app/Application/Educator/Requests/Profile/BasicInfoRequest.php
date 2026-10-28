<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BasicInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $educatorId = $this->user()->id;
        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('educators')->ignore($educatorId)],
            'phone' => ['sometimes', 'string', Rule::unique('educators')->ignore($educatorId)],
            'nationality_id' => 'sometimes|exists:nationalities,uuid',
            'gender' => 'sometimes|in:male,female',
            'country_id' => 'sometimes|exists:countries,uuid',
            'city_id' => 'sometimes|exists:cities,uuid',
            'identity_type' => 'sometimes|in:national_id,passport',
            'identity_id' => 'sometimes|string|max:50',
            'experience_years' => 'sometimes|integer|min:0|max:60',
            'identityImageFront' => 'sometimes|file|mimes:jpg,jpeg,png,gif,bmp,webp,pdf',
            'identityImageBack' => 'sometimes|file|mimes:jpg,jpeg,png,gif,bmp,webp,pdf',
        ];

        if ($this->input('identity_type') === 'national_id') {
            $rules['identityImageBack'] = 'sometimes|file|mimes:jpg,jpeg,png,gif,bmp,webp,pdf';
        }

        return $rules;
    }
}
