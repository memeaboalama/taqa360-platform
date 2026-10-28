<?php

namespace App\Application\Educator\Requests\Profile;

use App\Support\Enum\Edu\EducationLevelEnum;
use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicMajorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'degree' => 'required|in:' . implode(',', EducationLevelEnum::values()),
            'is_custom' => 'boolean',
            'academic_major_id' => 'nullable|required_if:is_custom,false|exists:academic_majors,id',
            'custom_name' => 'nullable|required_if:is_custom,true|string|max:255',
            'graduation_institution' => 'required|string|max:255',
            'graduation_year' => 'required|digits:4|integer|min:1950|max:' . date('Y'),
            'certificate' => 'nullable|file|mimes:jpg,jpeg,png,gif,bmp,webp,pdf',
        ];
    }
}
