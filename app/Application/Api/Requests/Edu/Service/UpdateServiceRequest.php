<?php

namespace App\Application\Api\Requests\Edu\Service;

use App\Support\Enum\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'array'],
            'description.en' => ['nullable', 'string', 'max:500'],
            'description.ar' => ['nullable', 'string', 'max:500'],
            'scope_of_work' => ['sometimes', 'nullable', 'array'],
            'scope_of_work.en' => ['nullable', 'string', 'max:5000'],
            'scope_of_work.ar' => ['nullable', 'string', 'max:5000'],
            'details' => ['sometimes', 'nullable', 'array'],
            'details.category' => ['sometimes', 'array'],
            'details.category.en' => ['nullable', 'string', 'max:100'],
            'details.category.ar' => ['nullable', 'string', 'max:100'],
            'details.icon' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9-]+$/', 'max:100'],
            'details.deliverables' => ['sometimes', 'array'],
            'details.deliverables.en' => ['sometimes', 'array', 'max:20'],
            'details.deliverables.en.*' => ['string', 'max:500'],
            'details.deliverables.ar' => ['sometimes', 'array', 'max:20'],
            'details.deliverables.ar.*' => ['string', 'max:500'],
            'details.exclusions' => ['sometimes', 'array'],
            'details.exclusions.en' => ['sometimes', 'array', 'max:20'],
            'details.exclusions.en.*' => ['string', 'max:500'],
            'details.exclusions.ar' => ['sometimes', 'array', 'max:20'],
            'details.exclusions.ar.*' => ['string', 'max:500'],
            'details.requirements' => ['sometimes', 'array'],
            'details.requirements.en' => ['sometimes', 'array', 'max:20'],
            'details.requirements.en.*' => ['string', 'max:500'],
            'details.requirements.ar' => ['sometimes', 'array', 'max:20'],
            'details.requirements.ar.*' => ['string', 'max:500'],
            'details.duration.en' => ['nullable', 'string', 'max:500'],
            'details.duration.ar' => ['nullable', 'string', 'max:500'],
            'details.pricing.en' => ['nullable', 'string', 'max:500'],
            'details.pricing.ar' => ['nullable', 'string', 'max:500'],
            'details.requirements_note.en' => ['nullable', 'string', 'max:1000'],
            'details.requirements_note.ar' => ['nullable', 'string', 'max:1000'],
            'details.cta_title.en' => ['nullable', 'string', 'max:255'],
            'details.cta_title.ar' => ['nullable', 'string', 'max:255'],
            'details.cta_help.en' => ['nullable', 'string', 'max:1000'],
            'details.cta_help.ar' => ['nullable', 'string', 'max:1000'],
            'details.disclaimer.en' => ['nullable', 'string', 'max:1000'],
            'details.disclaimer.ar' => ['nullable', 'string', 'max:1000'],
            'details.process' => ['sometimes', 'array', 'max:12'],
            'details.process.*.icon' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9-]+$/', 'max:100'],
            'details.process.*.title' => ['sometimes', 'array'],
            'details.process.*.title.en' => ['nullable', 'string', 'max:255'],
            'details.process.*.title.ar' => ['nullable', 'string', 'max:255'],
            'details.process.*.description' => ['sometimes', 'array'],
            'details.process.*.description.en' => ['nullable', 'string', 'max:1000'],
            'details.process.*.description.ar' => ['nullable', 'string', 'max:1000'],
            'request_config' => ['sometimes', 'nullable', 'array'],
            'request_config.mode' => ['sometimes', 'required_with:request_config', 'in:onsite,remote,both'],
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
            'status' => ['sometimes', 'required', 'string', Rule::in(StatusEnum::values())],
        ];
    }
}
