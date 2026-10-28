<?php

namespace App\Application\Api\Requests\ElmApp\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
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
        $ALLOWED_STATUSES = ['draft', 'pending', 'confirmed', 'cancelled', 'ready', 'on-way', 'completed', 'expired'];

        return [
            'order_id' => ['required','exists:merchant_orders,id'],
            'status' => "required|string|in:" . implode(',', $ALLOWED_STATUSES),
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
