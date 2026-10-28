<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class LanguagesBulkRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'languages' => 'required|array',
            'languages.*.id' => 'sometimes|nullable|integer',
            'languages.*.language_id' => 'required|integer|exists:languages,id',
            'languages.*.level' => 'required|string|max:255',
            'languages.*.is_primary' => 'sometimes|boolean',
        ];
    }
}
