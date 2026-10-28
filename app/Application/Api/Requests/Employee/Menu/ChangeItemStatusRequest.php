<?php

namespace App\Application\Api\Requests\Employee\Menu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeItemStatusRequest extends FormRequest
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
            'item_id' => ['required',Rule::exists('items', 'id')->where('merchant_id', auth()->guard('employee-api')->user()->merchant_id)],
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function prepareForValidation(): void
    {

    }
}
