<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class PaymentInfoRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'bank_name' => 'required|string|max:255',
            'name_on_card' => 'required|string|max:255',
            'card_number' => 'required|string|max:32',
            'cvv' => 'sometimes|nullable|string|max:8',
            'expire_month' => 'sometimes|nullable|integer|min:1|max:12',
            'expire_year' => 'sometimes|nullable|integer|min:2000|max:2100',
            'bank_id' => 'sometimes|nullable|integer|exists:banks,id',
        ];
    }
}
