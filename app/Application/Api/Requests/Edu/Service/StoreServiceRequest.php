<?php

namespace App\Application\Api\Requests\Edu\Service;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(\App\Support\Services\ServiceDefinitionRules::rules(), [
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'array'],
            'description.en' => ['nullable', 'string', 'max:500'],
            'description.ar' => ['nullable', 'string', 'max:500'],
            'scope_of_work' => ['sometimes', 'nullable', 'array'],
            'scope_of_work.en' => ['nullable', 'string', 'max:5000'],
            'scope_of_work.ar' => ['nullable', 'string', 'max:5000'],
            'request_config' => ['sometimes', 'nullable', 'array'],
            'request_config.mode' => ['required_with:request_config', 'in:onsite,remote,both'],
            'request_config.solar' => ['sometimes', 'boolean'],
            'request_config.show_urgency' => ['sometimes', 'boolean'],
            'request_config.fields' => ['sometimes', 'array', 'max:20'],
            'request_config.fields.*.key' => ['required_with:request_config.fields', 'regex:/^[a-zA-Z0-9_]+$/', 'max:50'],
            'request_config.fields.*.label' => [
                'required_with:request_config.fields',
                function ($attribute, $value, $fail) {
                    if (! is_string($value) && ! is_array($value)) {
                        $fail('The field label must be text or translated text.');
                    }
                },
            ],
            'request_config.fields.*.label.en' => ['nullable', 'string', 'max:255'],
            'request_config.fields.*.label.ar' => ['nullable', 'string', 'max:255'],
            'request_config.fields.*.type' => ['required_with:request_config.fields', 'in:text,textarea,number,select'],
            'request_config.fields.*.options' => ['sometimes', 'array', 'max:50'],
            'request_config.fields.*.options.*' => [
                function ($attribute, $value, $fail) {
                    $options = is_array($value) ? $value : [$value];
                    if (count($options) > 50 || array_filter($options, fn ($option) => ! is_string($option))) {
                        $fail('The field options must be text values.');
                    }
                },
            ],
            'request_config.fields.*.options.*.*' => ['string', 'max:255'],
            'request_config.fields.*.help' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! is_string($value) && ! is_array($value)) {
                        $fail('The field help must be text or translated text.');
                    }
                },
            ],
            'request_config.fields.*.help.en' => ['nullable', 'string', 'max:500'],
            'request_config.fields.*.help.ar' => ['nullable', 'string', 'max:500'],
            'request_config.fields.*.required' => ['sometimes', 'boolean'],
        ]);
    }
}
