<?php

namespace App\Application\Api\Requests\OTP;

use App\Support\Rules\IsValidPhone;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePhoneOtpRequest extends FormRequest
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
            'phone'     =>  ['required','numeric','digits_between:9,10',new isValidPhone,'unique:users,phone,'.auth()->id()],
            'otp'       =>  ['required' , 'string' ,'min:4','max:6']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' =>  ltrim($this->phone, "0"),
        ]);
    }
}
