<?php

namespace App\Support\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidSaudiIban implements ValidationRule
{
    public static function normalize(?string $value): string
    {
        return strtoupper(preg_replace('/\s+/', '', (string) $value));
    }

    public static function isValid(?string $value): bool
    {
        $iban = self::normalize($value);

        // Saudi IBAN: SA + 2 check digits + 20 alphanumeric/numeric BBAN chars = 24 chars
        if (!preg_match('/^SA\d{2}[A-Z0-9]{20}$/', $iban)) {
            return false;
        }

        // ISO 13616 MOD-97 check: move first 4 chars to end, convert A-Z -> 10-35, mod 97 === 1
        $rearranged = substr($iban, 4) . substr($iban, 0, 4);
        $numeric = '';
        $length = strlen($rearranged);

        for ($i = 0; $i < $length; $i++) {
            $ch = $rearranged[$i];
            if (ctype_alpha($ch)) {
                $numeric .= (string) (ord($ch) - 55);
            } else {
                $numeric .= $ch;
            }
        }

        return self::mod97($numeric) === 1;
    }

    private static function mod97(string $digits): int
    {
        $remainder = 0;
        $length = strlen($digits);

        for ($i = 0; $i < $length; $i++) {
            $remainder = ($remainder * 10 + (int) $digits[$i]) % 97;
        }

        return $remainder;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!self::isValid((string) $value)) {
            $fail(__('marketplace::home.invalid_saudi_iban'));
        }
    }
}
