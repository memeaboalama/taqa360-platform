<?php

namespace App\Application\Educator\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class SocialLinksRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'social_links' => 'required|array',
            'social_links.*.platform' => 'required|string',
            'social_links.*.url' => 'required|url',
        ];
    }
}
