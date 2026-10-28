<?php

namespace App\Application\Api\Requests\ElmApp\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ManuallyAssignDriverRequest extends FormRequest
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
            'order_id' => ['required','exists:merchant_orders,id'],
            'driver_id' => ['required','exists:drivers,id'],
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
