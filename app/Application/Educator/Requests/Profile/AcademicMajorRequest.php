<?php

namespace App\Application\Educator\Requests\Profile;

use App\Support\Enum\Edu\EducationLevelEnum;
use Illuminate\Foundation\Http\FormRequest;

class AcademicMajorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'degree' => ['required', 'in:' . implode(',', EducationLevelEnum::values())],
            'is_custom' => ['required', 'boolean'],
            'academic_major_id' => ['required_if:is_custom,false', 'exists:academic_majors,uuid'],
            'custom_name' => ['required_if:is_custom,true', 'string', 'max:255'],
            'graduation_institution' => ['required', 'string', 'max:255'],
            'graduation_year' => ['required', 'digits:4', 'integer', 'min:1950', 'max:' . date('Y')],
            'certificate' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,bmp,webp,pdf,doc,docx,zip'],
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            foreach ($rules as $field => $ruleSet) {
                $rules[$field] = array_map(function ($rule) {
                    if (str_contains($rule, 'required')) {
                        return 'sometimes';
                    }
                    return $rule;
                }, $ruleSet);
            }
        }

        return $rules;
    }
}
