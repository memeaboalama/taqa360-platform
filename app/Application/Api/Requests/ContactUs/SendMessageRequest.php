<?php

namespace App\Application\Api\Requests\ContactUs;

use App\Support\Rules\IsValidPhone;
use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'     =>  ['required','string','max:255'],
            'email'     =>  ['required','email','max:255'],
            'phone'     =>  ['required','numeric','digits_between:9,10', new isValidPhone],
            'message'     =>  ['required','string','max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
