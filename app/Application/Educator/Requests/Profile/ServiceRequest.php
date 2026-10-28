<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'service_id' => ['nullable', 'required_without:custom_name', 'exists:services,uuid'],
            'custom_name' => ['nullable', 'required_without:service_id', 'string', 'max:255'],
            'custom_description' => ['nullable', 'string', 'max:1000'],
            'is_custom' => ['boolean'],
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

    public function messages(): array
    {
        return [
            'service_id.required_without' => 'The service must be selected if custom name is not provided.',
            'custom_name.required_without' => 'The custom name is required if no service is selected.',
            'custom_description.required' => 'The description field is required.',
        ];
    }
}
