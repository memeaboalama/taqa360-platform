<?php

namespace App\Application\Api\Requests\Wallet;

use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class WalletDepositRequest extends FormRequest
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
            'amount' =>  ['required', 'numeric', 'min:1.0'],
            'channel' => ['required','in:'.implode(',',[ChannelTypesEnum::MOBILE->value,ChannelTypesEnum::WEB->value])],
            // 'payment_method' => ['required','in:'.implode(',',[PaymentMethod::MADA->value,PaymentMethod::APPLE->value, PaymentMethod::COD->value])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
