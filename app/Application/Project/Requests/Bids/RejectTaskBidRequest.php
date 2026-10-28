<?php

namespace App\Application\Project\Requests\Bids;

use Illuminate\Foundation\Http\FormRequest;

class RejectTaskBidRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rejection_reason' => 'nullable|string|max:1000',
        ];
    }
}
