<?php

namespace App\Application\Ecommerce\Requests;

use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\PaymentMethod;
use App\Support\Rules\IsValidPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PlaceWebOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'gift' => ['nullable', 'in:1,0'],
            'gift_confidential' => ['nullable', 'required_if:gift,==,1', 'in:1,0'],
            'ask_receipt' => ['nullable', 'required_if:gift,==,1', 'in:1,0'],
            'date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:' . date('Y-m-d')],
            'time' => ['nullable', 'required_with:date', 'date_format:h:i A'],
            'promocode' => ['nullable', 'exists:promocodes,code', 'string'],
            'client_phone' => ['nullable', 'required_if:gift,==,1', 'numeric', 'digits_between:9,10', new IsValidPhone],
            'client_long' => ['nullable'],
            'client_lat' => ['nullable'],
            'dialcode' => ['nullable'],
            'note' => ['nullable', 'string', 'max:1000'],
            'schedulable' => ['nullable', 'in:1,0'],
            'channel' => ['required', 'in:' . implode(',', [ChannelTypesEnum::MOBILE->value, ChannelTypesEnum::WEB->value])],
            'wallet' => ['nullable', 'in:1,0'],
            'merchantBalance' => ['nullable', 'in:1,0'],
            'payment_method' => ['required', 'in:' . implode(',', [PaymentMethod::MADA->value, PaymentMethod::APPLE->value, PaymentMethod::COD->value, 'credit_card', 'wallet'])],
            'client_name' => ['nullable', 'required_with:client_phone', 'string', 'max:255'],

            'address_id' => ['required', function ($attribute, $value, $fail) {
                if ($value !== 'new' && !auth()->user()->addresses()->where('id', $value)->exists()) {
                    $fail(__('ecommerce::ecommerce.invalid_address'));
                }
            }],
        ];

        if ($this->address_id === 'new') {
            $rules['new_address_title'] = ['required', 'string', 'max:100'];
            $rules['new_address_country_id'] = ['required', 'exists:countries,id'];
            $rules['new_address_city_id'] = ['required', 'exists:cities,id'];
            $rules['new_address_district_id'] = ['nullable'];
            $rules['new_address_address'] = ['required', 'string', 'max:500'];
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'channel' => ChannelTypesEnum::WEB->value,
            'gift' => $this->gift ?? 0,
            'schedulable' => $this->schedulable ?? 0,
            'wallet' => $this->wallet ?? 0,
            'merchantBalance' => $this->merchantBalance ?? 0,
        ]);

        if ($this->client_phone) {
            $this->merge([
                'client_phone' => ltrim($this->client_phone, "0"),
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'address_id' => __('ecommerce::ecommerce.address'),
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->payment_method == PaymentMethod::COD->value) {
                    // $validator->errors()->add(
                    //     'payment_method',
                    // );
                }
            }
        ];
    }
}
