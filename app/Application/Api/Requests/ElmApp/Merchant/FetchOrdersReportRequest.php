<?php

namespace App\Application\Api\Requests\ElmApp\Merchant;

use Illuminate\Foundation\Http\FormRequest;

class FetchOrdersReportRequest extends FormRequest
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
            'merchant_id'   =>  ['required','exists:merchants,id'],
            'paginated'     =>  ['sometimes','string','in:true,false'],
            'per_page'      =>  ['sometimes','integer','max:20'],
        ];
    }
}
