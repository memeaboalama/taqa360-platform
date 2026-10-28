<?php

namespace App\Application\Api\Requests\Order\Payment;

use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\PaymentMethod;
use App\Support\Rules\IsValidPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePaymentRequest extends FormRequest
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
            'channel' => ['required','in:'.implode(',',[ChannelTypesEnum::MOBILE->value,ChannelTypesEnum::WEB->value])],
            'payment_method' => ['required','in:'.implode(',',[PaymentMethod::MADA->value,PaymentMethod::APPLE->value])],
            'order_id' => ['required',Rule::exists('merchant_orders','id')->where('user_id',auth()->guard('user-api')->user()->id)],
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
        ]);
    }
}
