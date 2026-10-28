<?php

namespace App\Application\Api\Requests\Favourite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddToFavouriteRequest extends FormRequest
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
            'merchant_id' => ['required',  Rule::exists('merchants', 'id')->whereNull('deleted_at')],
            'merchant_branch_id' => ['required', Rule::exists('merchant_branches', 'id')->where('merchant_id', request()->merchant_id)->whereNull('deleted_at')],
        ];

    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
