<?php

namespace App\Application\Api\Requests\Favourite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RemoveFromFavouriteRequest extends FormRequest
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
            'fav_id'     =>  ['required', Rule::exists('favorites', 'id')->where('user_id', auth()->guard('user-api')->user()->id)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
