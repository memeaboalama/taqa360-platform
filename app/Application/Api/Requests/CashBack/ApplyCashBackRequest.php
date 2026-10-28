<?php

namespace App\Application\Api\Requests\CashBack;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCashBackRequest extends FormRequest
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
            'cash_back_name'     =>  ['required','exists:cash_backs,name','string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
