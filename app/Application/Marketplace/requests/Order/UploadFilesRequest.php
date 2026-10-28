<?php

namespace App\Application\Marketplace\requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UploadFilesRequest extends FormRequest
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
            'company_logo' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'owner_id' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'menu_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'cr_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'national_address_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
            'signed_contract_copy' => 'nullable|mimes:jpeg,jpg,png,gif,pdf,xls,csv|max:10240',
        ];
    }
}
