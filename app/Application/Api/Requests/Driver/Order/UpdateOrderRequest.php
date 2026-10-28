<?php

namespace App\Application\Api\Requests\Driver\Order;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use App\Support\Rules\MaxWordCount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', Rule::exists('merchant_orders', 'id')],
            'status' => ['required','in:'.implode(',',[MerchantOrderStatuses::ON_WAY->value,MerchantOrderStatuses::PREVIOUS->value,"accept","reject"])],


        ];
    }


}
