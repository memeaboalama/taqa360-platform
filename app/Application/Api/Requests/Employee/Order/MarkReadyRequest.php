<?php

namespace App\Application\Api\Requests\Employee\Order;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Support\Rules\Order\CheckStatusRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Domain\MarketPlace\Models\MerchantOrder;

class MarkReadyRequest extends FormRequest
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
        $order = MerchantOrder::find(request()->order);
        if($order->service_type_id=='2' )
        {
        return [
            'order' => ['required',
                Rule::exists('merchant_orders', 'id')
                    ->where('merchant_id', auth()->guard('employee-api')->user()->merchant_id),
                new CheckStatusRule(request()->order, [MerchantOrderStatuses::CURRENT->value])],
        ];
    }
        else
        {
        return [
            'order' => ['required',
                Rule::exists('merchant_orders', 'id')
                    ->where('merchant_id', auth()->guard('employee-api')->user()->merchant_id),
                new CheckStatusRule(request()->order, [MerchantOrderStatuses::UPCOMING->value])],
        ];
    }
    }

    protected function prepareForValidation(): void
    {

    }
}
