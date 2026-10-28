<?php

namespace App\Application\Api\Requests\Order;

use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\PaymentMethod;
use App\Support\Rules\IsValidPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class PlaceOrderServiceTypeRequest extends FormRequest
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
            'gift' => ['required', 'in:1,0'],
            'gift_confidential' => ['required_if:gift,==,1', 'in:1,0'],
            'ask_receipt' => ['required_if:gift,==,1', 'in:1,0'],
            'date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:' . date('Y-m-d')],
            'time' => ['required_with:date', 'nullable', 'date_format:h:i A'],
            'promocode'     =>  ['nullable', 'exists:promocodes,code', 'string'],
            'client_phone' => ['required_if:gift,==,1', 'numeric', 'digits_between:9,10', new isValidPhone],
            'client_long' => ['required_if:ask_receipt,==,0'],
            'client_lat' => ['required_if:ask_receipt,==,0'],
            'dialcode' => ['nullable'],
            'note' => ['nullable'],
            'schedulable' => ['required', 'in:1,0'],
            'channel' => ['required', 'in:' . implode(',', [ChannelTypesEnum::MOBILE->value, ChannelTypesEnum::WEB->value])],
            'wallet' => ['required', 'in:1,0'],
            'merchantBalance' => ['required', 'in:1,0'],
            'payment_method' => ['required', 'in:' . implode(',', [PaymentMethod::MADA->value, PaymentMethod::APPLE->value, PaymentMethod::COD->value])],
            'client_name' => ['required_with:client_phone', 'nullable', 'string', 'max:255'],
            // 'address' => ['required_if:gift,==,0',Rule::exists('user_addresses','id')->where('user_id',auth()->guard('user-api')->user()->id)],
            'address' => ['required_if:gift,==,0'],
            'address.longitude' => ['required_if:gift,==,0'],
            'address.latitude' => ['required_if:gift,==,0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->payment_method == PaymentMethod::COD->value) {

                    $validator->errors()->add(
                        'payment_method',
                        'خدمة الدفع لاحقا غير متوفره حاليا!'
                    );
                }
            }
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'client_phone' =>  ltrim($this->client_phone, "0"),
        ]);
    }
}
