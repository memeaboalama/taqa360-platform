<?php

namespace App\Application\Marketplace\requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class SaveClickPayRequest extends FormRequest
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
            'acquirerMessage' => ['string'],
            'acquirerRRN' => ['string'],
            // 'cartId' => ['required', 'exists:merchant_orders,id'],
            'cartId' => ['required', 'integer'],
            'respCode' => ['required', 'string'],
            'respMessage' => ['required', 'string'],
            'respStatus' => ['required', 'string'],
            'token' => ['string'],
            'tranRef' => ['required', 'string'],
            'customerEmail' => ['integer'],
            //            'amount' => ['required', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
