<?php

namespace App\Application\Api\Requests\Order;

use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\PaymentMethod;
use App\Support\Rules\IsValidPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ElmOrderRequest extends FormRequest
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
            'order_id' => ['required','exists:merchant_orders,id'],
            'elm_id' => ['nullable', 'string', 'max:255'],
            'elm_upload_status' =>  ['required','in:0,1,2,3,4,5,6,7,10'],
            'elm_errors' => 'nullable|array',
        ];
    }
}
