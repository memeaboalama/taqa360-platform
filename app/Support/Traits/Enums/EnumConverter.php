<?php

namespace Support\Traits\Enums;

use ReflectionClass;

trait EnumConverter
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }

    public static function getConstantByName($dayName)
    {
        return constant('self::'.$dayName);
    }
    public static function getConstantByValue($value)
    {
        $arr = array_combine(self::names(), self::values());
        return array_flip($arr)[$value];

    }
}
