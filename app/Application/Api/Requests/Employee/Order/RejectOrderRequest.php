<?php

namespace App\Application\Api\Requests\Employee\Order;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Support\Rules\Order\CheckStatusRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RejectOrderRequest extends FormRequest
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
            'order' => ['required',
                Rule::exists('merchant_orders', 'id')
                    ->where('merchant_id', auth()->guard('employee-api')->user()->merchant_id),
                new CheckStatusRule(request()->order, [MerchantOrderStatuses::PENDING->value])],
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
