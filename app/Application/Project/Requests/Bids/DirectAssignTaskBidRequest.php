<?php

namespace App\Application\Project\Requests\Bids;

use Illuminate\Foundation\Http\FormRequest;

class DirectAssignTaskBidRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'educator_id' => 'required|string|exists:educators,uuid',
            'agreement_notes' => 'nullable|string|max:1000',
        ];
    }
}
