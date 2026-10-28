<?php

namespace App\Application\Api\Requests\Cart;

use App\Support\Rules\Cart\CheckChoiceRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddItemRequest extends FormRequest
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
        $rules = [
            'item_id' => ['required', 'exists:items,id'],
            'merchant_id' => ['required',  Rule::exists('merchants', 'id')->whereNull('deleted_at')],
            'merchant_branch_id' => ['required', Rule::exists('merchant_branches', 'id')->where('merchant_id', request()->merchant_id)->whereNull('deleted_at')],
            'qty' => ['required', 'numeric', 'min:1'],
            'additional' => ['nullable', 'array'],
        ];
//        return [
//
//           // 'additional.*'     =>  ['nullable','exists:addition_group_choice,id', new CheckChoiceRule(request()->merchant_id)],
//        ];


        if (isset(request()->additional) && !empty(array_filter(request()->additional))) {
            $rules['additional.*.group_id'] = 'required|numeric|exists:addition_groups,id';
            $rules['additional.*.choices'] = 'required|array|min:1';
            $rules['additional.*.choices.*.choice_id'] = 'required|numeric|exists:choices,id';
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
