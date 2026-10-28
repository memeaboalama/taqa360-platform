<?php

namespace App\Application\Ecommerce\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyPromocodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'promocode' => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'promocode.required' => __('ecommerce::ecommerce.promocode_required'),
            'promocode.exists' => __('ecommerce::ecommerce.promocode_invalid'),
        ];
    }
}
