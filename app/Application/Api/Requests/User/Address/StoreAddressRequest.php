<?php

namespace App\Application\Api\Requests\User\Address;

use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAddressRequest extends FormRequest
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
            'title'     =>  ['required','string','max:255','min:3'],
            'address'     =>  ['required','string','max:255','min:3'],
            'is_default'     =>  ['required','in:0,1'],
//            'city_id'     =>  ['required','integer','exists:cities,id'],
//            'distract_id'     =>  ['required','integer','exists:distracts,id'],
            'latitude'     =>  ['required','string',new LatitudeRule, Rule::unique('user_addresses')->where(function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })],
            'longitude'     =>  ['required','string',new LongitudeRule, Rule::unique('user_addresses')->where(function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }

    public function messages()
    {
       return [
         'title.required' => __('api::trans.title.required')
       ];
    }
}
