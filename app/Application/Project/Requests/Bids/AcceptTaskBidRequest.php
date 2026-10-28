<?php

namespace App\Application\Project\Requests\Bids;

use Illuminate\Foundation\Http\FormRequest;

class AcceptTaskBidRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'approval_message' => 'nullable|string|max:1000',
        ];
    }
}
