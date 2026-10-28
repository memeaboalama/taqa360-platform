<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ServicesUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'services' => 'required|array',
            'services.*.id' => 'sometimes|nullable|integer',
            'services.*.service_id' => 'sometimes|nullable|integer|exists:services,id',
            'services.*.custom_name' => 'sometimes|nullable|string|max:255',
            'services.*.custom_description' => 'sometimes|nullable|string|max:1000',
            'services.*.is_custom' => 'sometimes|boolean',
        ];
    }
}
