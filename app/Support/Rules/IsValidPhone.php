<?php

namespace App\Support\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class IsValidPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = ltrim((string) $value, '0');

        if (!is_numeric($value) || !preg_match('/^5\d{8}$/', $value)) {
            $fail($this->message());

            return;
        }

        try {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            $phoneNumberObject = $phoneNumberUtil->parse($value, 'SA');

            if (!$phoneNumberUtil->isValidNumberForRegion($phoneNumberObject, 'SA')) {
                $fail($this->message());
            }
        } catch (NumberParseException $e) {
            $fail($this->message());
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.phone_valid');
    }

}
