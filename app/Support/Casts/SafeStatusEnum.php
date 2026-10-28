<?php

namespace App\Support\Casts;

use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Null-safe replacement for the plain `StatusEnum::class` cast.
 *
 * The plain enum cast throws a ValueError (breaking the whole Livewire page/table)
 * when legacy rows hold an unexpected value (e.g. '' coerced from NULL on a NOT NULL
 * enum column). This cast returns null instead, so `?->value` readers and badges
 * keep working. Writes pass through untouched (DB/validation own them).
 */
class SafeStatusEnum implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?StatusEnum
    {
        if ($value === null || $value === '') {
            return null;
        }

        return StatusEnum::tryFrom($value);
    }

    public function set($model, string $key, $value, array $attributes): mixed
    {
        if ($value instanceof StatusEnum) {
            return $value->value;
        }

        return $value;
    }
}
