<?php

namespace App\Application\Api\Requests\Order;

use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\PaymentMethod;
use App\Support\Enum\ServiceStrategy;
use App\Support\Rules\IsValidPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'per_page' =>['sometimes', 'integer', 'max:20'],
            'status' =>['required', 'string', 'in:upcoming,past'],
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
//            'client_phone' =>  ltrim($this->client_phone, "0"),
        ]);
    }
}
