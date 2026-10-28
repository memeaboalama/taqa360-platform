<?php

namespace App\Support\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class LongitudeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regex = '/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))(\.(\d{1,20}))?)|180(\.0+)?)$/';
        if(!preg_match($regex, $value)){
            $fail($this->message());
        }
    }

    public function message()
    {
        return trans('validation.invalid_longitude');
    }

}
