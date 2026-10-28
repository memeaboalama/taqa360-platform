<?php

namespace App\Application\Api\Requests\ElmApp\Order;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FetchOrdersRequest extends FormRequest
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
         $ALLOWED_STATUSES = ['draft', 'pending', 'confirmed', 'cancelled', 'ready', 'on-way', 'completed', 'expired'];
         $ALLOWED_ELM_STATUSES = ['accepted', 'rejected', 'needs_updating', 'accepted_by_merchant', 'driver_assigned', 'executed', 'cancelled','errors'];
         $ALLOWED_DATE_CONDITIONS = ['equal', 'gt', 'between'];

        return [
            'per_page' => 'nullable|integer|max:20',
            'status' => "nullable|string|in:" . implode(',', $ALLOWED_STATUSES),
            'elm_status' => "nullable|string|in:" . implode(',', $ALLOWED_ELM_STATUSES),
            'date_condition' => "nullable|string|in:" . implode(',', $ALLOWED_DATE_CONDITIONS),
            'date' => 'required_with:date_condition|date_format:Y-m-d',
            'to_date' => 'required_if:date_condition,==,between|date_format:Y-m-d',
        ];
    }


    protected function prepareForValidation(): void
    {

    }
}
