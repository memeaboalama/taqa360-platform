<?php

namespace App\Application\Api\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceTypeQtyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'cart_item_id'     =>  ['required', 'exists:cart_items,id'],
            'qty'     =>  ['required', 'numeric', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
