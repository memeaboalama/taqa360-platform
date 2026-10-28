<?php

namespace App\Application\Api\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class ApplyPromocodeRequest extends FormRequest
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
            'promocode'     =>  ['required','exists:promocodes,code','string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
