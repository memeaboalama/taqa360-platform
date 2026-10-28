<?php

namespace App\Application\Api\Requests\WalletCoupon;

use Illuminate\Foundation\Http\FormRequest;

class ApplyWalletCouponRequest extends FormRequest
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
            'walletCoupon' =>  ['required','exists:wallet_coupons,code','string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
