<?php

namespace App\Application\Api\Requests\Restaurant;

use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Foundation\Http\FormRequest;

class ShowSingleRestaurantRequest extends FormRequest
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
            'latitude' => ['nullable', new LatitudeRule],
            'longitude' => ['nullable', new LongitudeRule],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
