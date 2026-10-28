<?php

namespace App\Application\Api\Requests\Employee\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FetchOrderRequest extends FormRequest
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
            'per_page' => ['nullable', 'integer', 'max:20'],
            'status' =>['nullable', 'string', 'in:deliveries,new,accepted,history'],
            'accepted' =>['nullable', 'string', 'in:confirmed,started-work,cooking,ready,on-way'],
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
