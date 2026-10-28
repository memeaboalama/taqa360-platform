<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class BankInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'iban' => ['required','string','max:34'],
            'name' => ['required','string','max:255'],
            'address' => ['nullable','string','max:500'],
            'bank_country' => ['required','string','max:255'],
            'bank_code' => ['nullable','string','max:20'],
            'bank_name' => ['required','string','max:255'],
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            foreach ($rules as $field => $ruleSet) {
                $rules[$field] = array_map(fn($rule) => str_contains($rule,'required') ? 'sometimes' : $rule, $ruleSet);
            }
        }

        return $rules;
    }
}
