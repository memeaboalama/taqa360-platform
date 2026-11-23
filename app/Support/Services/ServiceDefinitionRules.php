<?php

namespace App\Support\Services;

class ServiceDefinitionRules
{
    public static function rules(): array
    {
        $rules = [
            'pricing_mode' => ['sometimes', 'required', 'in:quote,fixed,from'],
            'price' => ['nullable', 'required_if:pricing_mode,fixed,from', 'numeric', 'min:0', 'max:9999999999.99'],
            'duration' => ['sometimes', 'nullable', 'array:ar,en'],
            'duration.ar' => ['nullable', 'string', 'max:500'],
            'duration.en' => ['nullable', 'string', 'max:500'],
        ];
        foreach (['deliverables', 'exclusions', 'requirements'] as $field) {
            $rules[$field] = ['sometimes', 'nullable', 'array:ar,en'];
            foreach (['ar', 'en'] as $locale) {
                $rules["$field.$locale"] = ['sometimes', 'array', 'max:20'];
                $rules["$field.$locale.*"] = ['required', 'string', 'max:500'];
            }
        }

        return $rules;
    }
}
