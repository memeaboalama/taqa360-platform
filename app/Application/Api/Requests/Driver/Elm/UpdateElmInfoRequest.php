<?php

namespace App\Application\Api\Requests\Driver\Elm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateElmInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'elm_id' => ['nullable', 'string', 'max:255'],
            'elm_upload_status' =>  ['required','in:0,1,2,3,10'],
            'elm_errors' => 'nullable|array'
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
