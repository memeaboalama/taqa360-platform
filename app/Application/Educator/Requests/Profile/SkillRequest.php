<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'skill_id' => ['nullable','required_without:custom_name','exists:skills,uuid'],
            'custom_name' => ['nullable','required_without:skill_id','string','max:255'],
            'is_custom' => ['boolean'],
            'level' => ['nullable','integer','min:1','max:5'],
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            foreach ($rules as $field => $ruleSet) {
                $rules[$field] = array_map(fn($rule) => str_contains($rule,'required') ? 'sometimes' : $rule, $ruleSet);
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'skill_id.required_without' => 'The skill must be selected if custom name is not provided.',
            'custom_name.required_without' => 'The custom name is required if no skill is selected.',
            'level.integer' => 'Level must be an integer between 1 and 5.',
        ];
    }
}
