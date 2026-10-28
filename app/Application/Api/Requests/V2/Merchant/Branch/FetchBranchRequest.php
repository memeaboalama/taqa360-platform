<?php

namespace App\Application\Api\Requests\V2\Merchant\Branch;

use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Foundation\Http\FormRequest;

class FetchBranchRequest extends FormRequest
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
        $user=auth('sanctum')->user();

        return [
            'category' => ['sometimes', 'nullable','exists:market_place_categories,id'],
            'keyword' => ['sometimes', 'nullable','string'],
            'menu' => ['sometimes', 'nullable','array'],
            'menu.*' => ['required_with:menu', 'exists:merchant_services,id'],
            'main_category' => ['sometimes', 'nullable','array'],
            'main_category.*' => ['required_with:main_category', 'exists:main_categories,id'],
            'sorting' => ['sometimes', 'nullable','array'],
            'sorting.*' => ['required_with:sorting', 'in:rating,price,delivery'],
            'paginated' => ['sometimes', 'string', 'in:true,false'],
            'per_page' => ['sometimes', 'integer', 'max:20'],
            'latitude' => ['sometimes', new LatitudeRule],
            'longitude' => ['sometimes', new LongitudeRule],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
