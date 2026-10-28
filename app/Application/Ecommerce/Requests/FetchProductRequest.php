<?php

namespace App\Application\Ecommerce\Requests;

use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Foundation\Http\FormRequest;

class FetchProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $user=auth('sanctum')->user();

        return [
            'category' => ['sometimes', 'nullable','exists:merchant_services,id'],
            'sub_category' => ['sometimes', 'nullable','exists:market_place_categories,id'],
            'keyword' => ['sometimes', 'nullable','string'],
            'main_category' => ['sometimes', 'nullable','array'],
            'main_category.*' => ['required_with:main_category', 'exists:main_categories,id'],
            'sorting' => ['sometimes', 'nullable','in:rating,price,delivery'],
            'paginated' => ['sometimes', 'string', 'in:true,false'],
            'per_page' => ['sometimes', 'integer', 'max:20'],
            'latitude' => ['sometimes', new LatitudeRule],
            'longitude' => ['sometimes', new LongitudeRule],
        ];
    }
}
