<?php

namespace App\Application\Educator\Requests\Bids;

use App\Application\Project\Enums\BidStatus;
use Domain\Project\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class FetchBidsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'nullable', 'in:' . implode(',', BidStatus::values())],
            'project_id' => ['sometimes', 'nullable', 'string'],
            'created_at' => ['sometimes', 'nullable', 'date'],
            'sorting' => ['sometimes', 'nullable', 'array'],
            'sorting.*.field' => ['required_with:sorting', 'in:created_at,updated_at,total_amount,delivery_days,project_name'],
            'sorting.*.direction' => ['required_with:sorting', 'in:asc,desc'],

            'paginated' => ['sometimes'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100']
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (isset($validated['project_id'])) {
            $validated['project_id'] = Project::where('uuid', $validated['project_id'])->value('id');
        }

        return $validated;
    }
}
