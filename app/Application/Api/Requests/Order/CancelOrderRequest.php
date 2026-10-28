<?php

namespace App\Application\Api\Requests\Order;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Support\Rules\Order\CheckStatusRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CancelOrderRequest extends FormRequest
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
                    ->where('user_id', auth()->guard('user-api')->user()->id),
                new CheckStatusRule(request()->order, [MerchantOrderStatuses::PENDING->value])],
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
