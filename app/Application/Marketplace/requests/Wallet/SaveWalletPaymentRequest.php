<?php

namespace App\Application\Marketplace\requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class SaveWalletPaymentRequest extends FormRequest
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
//            'id' => ['required', 'string'],
//            'status' => ['required', 'string'],
            'key' => ['required', 'exists:wallet_transactions,id'],
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
