<?php

namespace App\Application\Api\Requests\ElmApp\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FetchDriversRequest extends FormRequest
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
            'per_page' => ['nullable', 'integer', 'max:20'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'network_status' => ['nullable', 'in:0,1'],
            'eats_drivers' => ['sometimes','nullable','in:true,false'],
            'company_id' => ['required_if:eats_drivers,==,false','sometimes','nullable', 'exists:companies,id'],
            'elm_status' =>['nullable', 'string', 'in:accepted,rejected,needs_updating,errors'],
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
