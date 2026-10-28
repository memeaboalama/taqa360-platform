<?php

namespace App\Application\Api\Requests\Driver\Profile;

use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FetchLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'driver_id' => ['required', 'numeric','exists:drivers,id'],
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
