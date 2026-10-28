<?php

namespace App\Support\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use Illuminate\Translation\PotentiallyTranslatedString;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class LatitudeRule implements ValidationRule
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
        $regex = '/^[-]?((([0-8]?[0-9])(\.(\d{1,20}))?)|(90(\.0+)?))$/';
        if(!preg_match($regex, $value)){
            $fail($this->message());
        }
    }

    public function message()
    {
        return trans('validation.invalid_latitude');
    }

}
