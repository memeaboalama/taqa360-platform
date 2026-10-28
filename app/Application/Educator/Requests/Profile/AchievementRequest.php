<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class AchievementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:work_experience,certificate,course,workshop', 'max:255'],
            'description' => ['required', 'string'],
            'organization' => ['required', 'string', 'max:255'],
            'issue_date' => ['nullable','required_if:type,certificate,course,workshop', 'date'],
            'start_date' => ['nullable','required_if:type,work_experience|', 'date'],
            'end_date' => ['nullable','required_with:start_date', 'date', 'after_or_equal:start_date'],
            'attachment' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,gif,bmp,webp,pdf,doc,docx,zip'],
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            foreach ($rules as $field => $ruleSet) {
                $rules[$field] = array_map(function ($rule) {
                    return $rule === 'required' ? 'sometimes' : $rule;
                }, $ruleSet);
            }
        }

        return $rules;
    }
}
