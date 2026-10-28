<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $rules = [
            'language_id' => ['required', 'string', 'exists:languages,uuid'],
            'level' => ['required', 'string', 'max:255'],
            'is_primary' => ['required', 'boolean'],
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
