<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'language_id' => 'required|exists:languages,id',
            'level' => 'required|string|in:beginner,intermediate,advanced,native',
            'is_primary' => 'boolean',
        ];
    }
}
