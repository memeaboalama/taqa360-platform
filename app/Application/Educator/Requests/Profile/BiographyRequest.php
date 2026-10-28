<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class BiographyRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'bio' => 'sometimes|array',
            'bio.title' => 'sometimes|string|nullable',
            'bio.description' => 'sometimes|string|nullable',
            'social_links' => 'sometimes|array',
            'social_links.*.platform' => 'required_with:social_links|string',
            'social_links.*.url' => 'required_with:social_links|url',
            'profileImage' => 'sometimes|file|mimes:jpg,jpeg,png,webp',
        ];
    }

    // protected function prepareForValidation()
    // {
    //     if ($this->has('bio') && is_string($this->input('bio'))) {
    //         $this->merge(['bio' => ['description' => $this->input('bio')]]);
    //     }
    // }
}
